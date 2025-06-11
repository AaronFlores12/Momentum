<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserProfileController extends Controller
{
    /**
     * Display the user's profile.
     */
    public function show(User $user)
    {
        $currentUser = auth()->user();
        
        // Cargar los posts del usuario con comentarios y likes
        $posts = $user->posts()
            ->with(['user', 'comments.user', 'likes'])
            ->latest()
            ->get();

        // Transformar las URLs de las imágenes y avatares
        $posts->transform(function ($post) use ($currentUser) {
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
            $post->liked_by_user = $currentUser ? $post->likes->contains('user_id', $currentUser->id) : false;
            
            // Eliminar la colección de likes para no enviarla al frontend
            unset($post->likes);
            
            return $post;
        });

        // Preparar datos del usuario
        $userData = [
            'id' => $user->id,
            'name' => $user->name,
            'bio' => $user->bio,
            'avatar_url' => $user->avatar_url,
            'posts_count' => $user->posts()->count(),
            'comments_count' => $user->comments()->count(),
            'followers_count' => $user->followers()->count(),
            'following_count' => $user->following()->count(),
            'created_at' => $user->created_at,
            'is_following' => $currentUser ? $currentUser->isFollowing($user) : false,
        ];

        return Inertia::render('Users/Profile', [
            'user' => $userData,
            'posts' => $posts,
            'isCurrentUser' => $currentUser ? $currentUser->id === $user->id : false,
        ]);
    }
}
