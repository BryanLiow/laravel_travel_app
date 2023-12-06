<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Http\Requests\StoreLikeRequest;
use App\Http\Requests\UnlikePostRequest;
use Auth;

class LikeController extends Controller
{
    public function likePost(StoreLikeRequest $request)
    {
        $userId = Auth::id();
        $postId = $request->post_id;

        // Check if the user already liked the post
        $existingLike = Like::where('user_id', $userId)->where('post_id', $postId)->first();

        if ($existingLike) {
            // User already liked the post, so you might want to unlike it or do nothing
            // For unlike: $existingLike->delete();
            return response()->json(['message' => 'Post already liked.'], 409);
        }

        // Create a new like record
        Like::create([
            'user_id' => $userId,
            'post_id' => $postId
        ]);

        return response()->json(['message' => 'Post liked successfully.'], 200);
    }

    public function unlikePost(UnlikePostRequest $request)
    {
        $userId = Auth::id();
        $postId = $request->input('post_id');

        // Find the like record
        $like = Like::where('user_id', $userId)->where('post_id', $postId)->first();

        if (!$like) {
            return response()->json(['message' => 'Like not found.'], 404);
        }

        // Delete the like record
        $like->delete();

        return response()->json(['message' => 'Successfully unliked the post.'], 200);
    }
}
