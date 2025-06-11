<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'post_id' => 'required|exists:posts,id',
            'content' => 'required|string|max:1000|min:1',
        ]);

        try {
            $comment = Comment::create([
                'post_id' => $validated['post_id'],
                'user_id' => Auth::id(),
                'content' => trim($validated['content']),
            ]);

            $comment->load('user');

            return redirect()->back()->with('comment', [
                'id' => $comment->id,
                'content' => $comment->content,
                'created_at' => $comment->created_at->toISOString(),
                'user' => [
                    'id' => $comment->user->id,
                    'name' => $comment->user->name,
                ]
            ]);

        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['comment' => 'Error al guardar el comentario']);
        }
    }

    public function destroy(Comment $comment)
    {
        if ($comment->user_id !== Auth::id()) {
            return redirect()->back()->withErrors(['comment' => 'No tienes permisos para eliminar este comentario']);
        }

        try {
            $comment->delete();
            return redirect()->back()->with('success', 'Comentario eliminado exitosamente');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['comment' => 'Error al eliminar el comentario']);
        }
    }
}