<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
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
            'avatar_url' => $user->avatar_url,
            'posts_count' => $user->posts()->count(),
            'comments_count' => $user->comments()->count(),
            'followers_count' => $user->followers()->count(),
            'following_count' => $user->following()->count(),
            'created_at' => $user->created_at,
        ];
        
        return Inertia::render('settings/Profile', [
            'mustVerifyEmail' => $user instanceof MustVerifyEmail,
            'status' => session('status'),
            'user' => $userData,
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
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
        ], [
            'avatar.image' => 'El archivo debe ser una imagen.',
            'avatar.mimes' => 'La imagen debe ser de tipo: jpeg, png, jpg, gif, webp.',
            'avatar.max' => 'La imagen no debe ser mayor a 2MB.',
            'bio.max' => 'La biografía no debe exceder los 500 caracteres.',
        ]);

        try {
            // Manejar la subida del avatar
            if ($request->hasFile('avatar')) {
                // Eliminar el avatar anterior si existe
                if ($user->avatar) {
                    Storage::disk('public')->delete($user->avatar);
                }
                
                $file = $request->file('avatar');
                $filename = time() . '_' . $file->getClientOriginalName();
                $avatarPath = $file->storeAs('avatars', $filename, 'public');
                $validated['avatar'] = $avatarPath;
            }

            $user->fill($validated);

            if ($user->isDirty('email')) {
                $user->email_verified_at = null;
            }

            $user->save();

            return Redirect::route('profile.edit')->with('success', 'Perfil actualizado exitosamente');

        } catch (\Exception $e) {
            return Redirect::back()
                ->withErrors(['error' => 'Error al actualizar el perfil: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        try {
            // Eliminar el avatar si existe
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }

            Auth::logout();

            $user->delete();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return Redirect::to('/');

        } catch (\Exception $e) {
            return Redirect::back()
                ->withErrors(['error' => 'Error al eliminar la cuenta: ' . $e->getMessage()]);
        }
    }
}
