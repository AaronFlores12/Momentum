<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FollowController extends Controller
{
    /**
     * Seguir o dejar de seguir a un usuario
     */
    public function toggle(User $user)
    {
        $currentUser = Auth::user();
        
        // No puede seguirse a sí mismo
        if ($currentUser->id === $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'No puedes seguirte a ti mismo'
            ], 400);
        }

        $isFollowing = $currentUser->isFollowing($user);
        
        if ($isFollowing) {
            $currentUser->unfollow($user);
            $following = false;
            $message = 'Has dejado de seguir a ' . $user->name;
        } else {
            $currentUser->follow($user);
            $following = true;
            $message = 'Ahora sigues a ' . $user->name;
        }

        return response()->json([
            'success' => true,
            'following' => $following,
            'followers_count' => $user->followers()->count(),
            'message' => $message
        ]);
    }

    /**
     * Mostrar la lista de seguidores de un usuario
     */
    public function followers(User $user)
    {
        $followers = $user->followers()
            ->withPivot('created_at')
            ->orderBy('follows.created_at', 'desc')
            ->get();

        return inertia('Users/Followers', [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'avatar_url' => $user->avatar_url,
            ],
            'followers' => $followers->map(function ($follower) {
                return [
                    'id' => $follower->id,
                    'name' => $follower->name,
                    'avatar_url' => $follower->avatar_url,
                    'bio' => $follower->bio,
                    'followed_at' => $follower->pivot->created_at,
                    'is_following' => auth()->check() ? auth()->user()->isFollowing($follower) : false,
                ];
            }),
        ]);
    }

    /**
     * Mostrar la lista de usuarios que sigue un usuario
     */
    public function following(User $user)
    {
        $following = $user->following()
            ->withPivot('created_at')
            ->orderBy('follows.created_at', 'desc')
            ->get();

        return inertia('Users/Following', [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'avatar_url' => $user->avatar_url,
            ],
            'following' => $following->map(function ($followedUser) {
                return [
                    'id' => $followedUser->id,
                    'name' => $followedUser->name,
                    'avatar_url' => $followedUser->avatar_url,
                    'bio' => $followedUser->bio,
                    'followed_at' => $followedUser->pivot->created_at,
                    'is_following' => auth()->check() ? auth()->user()->isFollowing($followedUser) : false,
                ];
            }),
        ]);
    }
    
    /**
     * Mostrar una lista de usuarios sugeridos para seguir
     */
    public function suggestions()
    {
        $currentUser = Auth::user();
        
        // Obtener IDs de usuarios que ya sigue
        $followingIds = $currentUser->following()->pluck('users.id')->toArray();
        $followingIds[] = $currentUser->id; // Incluir al usuario actual
        
        // Obtener usuarios que no sigue, limitado a 10
        $suggestions = User::whereNotIn('id', $followingIds)
            ->inRandomOrder()
            ->limit(10)
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'avatar_url' => $user->avatar_url,
                    'bio' => $user->bio,
                    'posts_count' => $user->posts()->count(),
                    'followers_count' => $user->followers()->count(),
                ];
            });
            
        return inertia('Users/Suggestions', [
            'suggestions' => $suggestions
        ]);
    }
}
