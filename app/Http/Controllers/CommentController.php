<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Http\Requests\StoreCommentRequest;
use Auth;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function writeComment(StoreCommentRequest $request)
    {
        $comment = Comment::create([
            'user_id' => Auth::id(),
            'post_id' => $request->post_id,
            'comment' => $request->comment,
        ]);

        return response()->json(['message' => 'Comment added successfully.', 'comment' => $comment], 201);
    }

    public function getComments(Request $request)
    {
        $postId = $request->post_id;

        // Fetch comments with user data, sorted by ID in descending order
        $comments = Comment::with('user') // Ensure the 'user' relationship is defined in the Comment model
            ->where('post_id', $postId)
            ->orderBy('id', 'desc') // Sort by ID in descending order
            ->get()
            ->map(function ($comment) {
                return [
                    'id' => $comment->id,
                    'username' => $comment->user->username, // Replace 'username' with the actual field name in the User model
                    'userId' => $comment->user_id,
                    'postId' => $comment->post_id,
                    'comment' => $comment->comment,
                    'created' => $comment->created_at
                ];
            });

        return response()->json(['comments' => $comments]);
    }
}
