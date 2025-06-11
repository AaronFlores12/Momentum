<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    /**
     * Toggle like status for a post
     */
    public function toggle(Post $post)
    {
        $user = Auth::user();
        $like = Like::where('user_id', $user->id)
                    ->where('post_id', $post->id)
                    ->first();

        if ($like) {
            // Si ya existe un like, lo eliminamos
            $like->delete();
            $liked = false;
        } else {
            // Si no existe, creamos uno nuevo
            Like::create([
                'user_id' => $user->id,
                'post_id' => $post->id
            ]);
            $liked = true;
        }

        // Devolver el estado actualizado y el número de likes
        return response()->json([
            'liked' => $liked,
            'count' => $post->likes()->count()
        ]);
    }
}
