<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): Response
    {
        $user = $request->user();
        
        // Cargar los posts del usuario con comentarios y likes
        $posts = $user->posts()
            ->with(['user', 'comments.user', 'likes'])
            ->latest()
            ->get();

        // Transformar las URLs de las imágenes y avatares
        $posts->transform(function ($post) use ($user) {
            if ($post->media) {
                $post->media_url = asset('storage/' . $post->media);
            }
            
            // Agregar avatar_url al usuario del post
            if ($post->user->avatar) {
                $post->user->avatar_url = asset('storage/' . $post->user->avatar);
            }
            
            // Agregar avatar_url a los usuarios de los comentarios
            $post->comments->transform(function ($comment) {
                if ($comment->user->avatar) {
                    $comment->user->avatar_url = asset('storage/' . $comment->user->avatar);
                }
                return $comment;
            });
            
            // Agregar información de likes
            $post->likes_count = $post->likes->count();
            $post->liked_by_user = $post->likes->contains('user_id', $user->id);
            
            // Eliminar la colección de likes para no enviarla al frontend
            unset($post->likes);
            
            return $post;
        });

        // Preparar datos del usuario
        $userData = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'bio' => $user->bio,
            'avatar' => $user->avatar,
            'avatar_url' => $user->avatar ? asset('storage/' . $user->avatar) : null,
            'posts_count' => $user->posts()->count(),
            'comments_count' => $user->comments()->count(),
            'followers_count' => $user->followers()->count(),
            'following_count' => $user->following()->count(),
            'created_at' => $user->created_at,
        ];

        // Estadísticas para el componente
        $userStats = [
            'posts' => $userData['posts_count'],
            'followers' => $userData['followers_count'],
            'following' => $userData['following_count'],
        ];
        
        // Cambiar la ruta del componente para que coincida con tu estructura
        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $user instanceof MustVerifyEmail,
            'status' => session('status'),
            'user' => $userData,
            'userStats' => $userStats,
            'posts' => $posts,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        // Validar los datos directamente en el controlador
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required', 
                'string', 
                'lowercase', 
                'email', 
                'max:255', 
                Rule::unique('users')->ignore($user->id)
            ],
            'bio' => ['nullable', 'string', 'max:500'],
        ], [
            'bio.max' => 'La biografía no debe exceder los 500 caracteres.',
            'name.required' => 'El nombre es obligatorio.',
            'email.required' => 'El email es obligatorio.',
            'email.email' => 'El email debe tener un formato válido.',
            'email.unique' => 'Este email ya está en uso.',
        ]);

        try {
            $user->fill($validated);

            if ($user->isDirty('email')) {
                $user->email_verified_at = null;
            }

            $user->save();

            return Redirect::back()->with('success', 'Perfil actualizado exitosamente');

        } catch (\Exception $e) {
            return Redirect::back()
                ->withErrors(['error' => 'Error al actualizar el perfil: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Update the user's avatar.
     */
    public function updateAvatar(Request $request): RedirectResponse
    {
        $request->validate([
            'avatar' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
        ], [
            'avatar.image' => 'El archivo debe ser una imagen.',
            'avatar.mimes' => 'La imagen debe ser de tipo: jpeg, png, jpg, gif, webp.',
            'avatar.max' => 'La imagen no debe ser mayor a 2MB.',
        ]);

        $user = $request->user();

        try {
            // Eliminar el avatar anterior si existe
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            
            $file = $request->file('avatar');
            $filename = time() . '_' . $file->getClientOriginalName();
            $avatarPath = $file->storeAs('avatars', $filename, 'public');
            
            $user->update(['avatar' => $avatarPath]);

            return Redirect::back()->with('success', 'Avatar actualizado exitosamente');

        } catch (\Exception $e) {
            return Redirect::back()
                ->withErrors(['error' => 'Error al actualizar el avatar: ' . $e->getMessage()]);
        }
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ], [
            'current_password.current_password' => 'La contraseña actual es incorrecta.',
            'password.confirmed' => 'La confirmación de contraseña no coincide.',
        ]);

        try {
            $request->user()->update([
                'password' => Hash::make($validated['password']),
            ]);

            return Redirect::back()->with('success', 'Contraseña actualizada exitosamente');

        } catch (\Exception $e) {
            return Redirect::back()
                ->withErrors(['error' => 'Error al actualizar la contraseña: ' . $e->getMessage()]);
        }
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $user = $request->user();

        try {
            // Eliminar el avatar si existe
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }

            // Eliminar posts del usuario
            $user->posts()->delete();

            // Eliminar comentarios del usuario
            $user->comments()->delete();

            // Eliminar relaciones de seguimiento si existen
            if (method_exists($user, 'followers')) {
                $user->followers()->detach();
            }
            if (method_exists($user, 'following')) {
                $user->following()->detach();
            }

            // Eliminar likes del usuario si existe la relación
            if (method_exists($user, 'likes')) {
                $user->likes()->delete();
            }

            Auth::logout();

            $user->delete();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return Redirect::to('/')->with('success', 'Cuenta eliminada exitosamente');

        } catch (\Exception $e) {
            return Redirect::back()
                ->withErrors(['error' => 'Error al eliminar la cuenta: ' . $e->getMessage()]);
        }
    }
}
