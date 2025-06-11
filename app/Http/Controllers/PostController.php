<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $filter = $request->get('filter', 'all'); // 'all' o 'following'
        
        $query = Post::with(['user', 'comments.user', 'likes']);
        
        // Si el filtro es 'following', mostrar solo posts de usuarios seguidos
        if ($filter === 'following' && $user) {
            $followingIds = $user->following()->pluck('users.id')->toArray();
            // Incluir posts propios solo si se desea
            // $followingIds[] = $user->id;
            
            if (empty($followingIds)) {
                // Si no sigue a nadie, devolver una colección vacía
                $posts = collect([]);
            } else {
                $posts = $query->whereIn('user_id', $followingIds)->latest()->get();
            }
        } else {
            $posts = $query->latest()->get();
        }

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
            $post->liked_by_user = $user ? $post->likes->contains('user_id', $user->id) : false;
            
            // Eliminar la colección de likes para no enviarla al frontend
            unset($post->likes);
            
            return $post;
        });

        return Inertia::render('Posts/Index', [
            'posts' => $posts,
            'filter' => $filter,
            'followingCount' => $user ? $user->following()->count() : 0,
        ]);
    }

    public function create()
    {
        return Inertia::render('Posts/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255|min:3',
            'body' => 'nullable|string|max:5000',
            'media' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
        ]);

        try {
            $mediaPath = null;
            
            if ($request->hasFile('media')) {
                $file = $request->file('media');
                $filename = time() . '_' . $file->getClientOriginalName();
                $mediaPath = $file->storeAs('posts', $filename, 'public');
            }

            $post = Post::create([
                'title' => $validated['title'],
                'body' => $validated['body'],
                'media' => $mediaPath,
                'user_id' => Auth::id(),
            ]);

            return redirect()->route('post.index')->with('success', 'Post creado exitosamente');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Error al crear el post: ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function show(string $id)
    {
        $post = Post::with('user', 'comments.user')->findOrFail($id);

        // Transformar URL de imagen si existe
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

        return Inertia::render('Posts/Show', [
            'post' => $post,
        ]);
    }

    public function edit(string $id)
    {
        $post = Post::where('user_id', Auth::id())->findOrFail($id);

        if ($post->media) {
            $post->media_url = asset('storage/' . $post->media);
        }

        return Inertia::render('Posts/Edit', [
            'post' => $post,
        ]);
    }

    public function update(Request $request, string $id)
    {
        $post = Post::where('user_id', Auth::id())->findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255|min:3',
            'body' => 'nullable|string|max:5000',
            'media' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
        ]);

        try {
            $mediaPath = $post->media;

            if ($request->hasFile('media')) {
                if ($post->media) {
                    Storage::disk('public')->delete($post->media);
                }
                
                $file = $request->file('media');
                $filename = time() . '_' . $file->getClientOriginalName();
                $mediaPath = $file->storeAs('posts', $filename, 'public');
            }

            $post->update([
                'title' => $validated['title'],
                'body' => $validated['body'],
                'media' => $mediaPath,
            ]);

            return redirect()->route('post.index')->with('success', 'Post actualizado exitosamente');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Error al actualizar el post: ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function destroy(string $id)
    {
        $post = Post::where('user_id', Auth::id())->findOrFail($id);

        try {
            if ($post->media) {
                Storage::disk('public')->delete($post->media);
            }

            $post->delete();

            return redirect()->route('post.index')->with('success', 'Post eliminado exitosamente');

        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Error al eliminar el post: ' . $e->getMessage()]);
        }
    }
}
