<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostDetailRequest;
use App\Models\Following;
use App\Models\Like;
use App\Models\PostImage;
use App\Models\Post;
use Auth;

class PostDetailController extends Controller
{
    public function postdetail(PostDetailRequest $request)
    {
        $userId = Auth::id();
        $postId = $request->post_id;
        $baseUrl = 'https://bryantravelapp.s3.eu-west-1.amazonaws.com/post-photos/';

        // Check if the user has followed the author of the post
        $post = Post::with('user')->findOrFail($postId);
        $isFollowing = Following::where('user_id', $userId)
            ->where('following_user_id', $post->user_id)
            ->exists();

        // Check if the user has liked the post
        $isLiked = Like::where('user_id', $userId)
            ->where('post_id', $postId)
            ->exists();

        // Like count of the post
        $postLikesCount = Like::where('post_id', $postId)->count();


        // Get image paths from post_images table and prepend base URL
        $imagePaths = PostImage::where('post_id', $postId)
            ->pluck('image_path')
            ->map(function ($path) use ($baseUrl) {
                return $baseUrl . $path;
            });

        return response()->json([
            'isFollowing' => $isFollowing,
            'isLiked' => $isLiked,
            'postLikesCount' => $postLikesCount,
            'imagePaths' => $imagePaths
        ]);
    }
}
