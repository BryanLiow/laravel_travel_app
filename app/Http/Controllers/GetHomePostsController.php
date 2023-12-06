<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\PostImage;
use DB;

class GetHomePostsController extends Controller
{
    public function getHomePosts(Request $request)
    {
        $excludeIds = $request->query('exclude', []); // IDs to exclude
        $baseUrl = 'https://bryantravelapp.s3.eu-west-1.amazonaws.com/post-photos/';

        // Fetch posts with additional details
        $posts = Post::whereNotIn('id', $excludeIds)
            ->with(['user'])
            ->inRandomOrder()
            ->take(8)
            ->get(['id', 'user_id', 'title', 'caption', 'likes', 'country', 'created_at']);

        $posts->each(function ($post) use ($baseUrl) {
            // Fetch the smallest ID image for each post
            $smallestImage = $post->images()->orderBy('id', 'asc')->first();
        
            // Prepend base URL to the image path
            $post->image = $smallestImage ? $baseUrl . $smallestImage->image_path : null;
            
            // Add username and formatted createdOn to the post object
            $post->username = $post->user->username;
            $post->createdOn = $post->created_at->format('Y-m-d H:i:s');
            
            // Optionally remove the user object from the post to reduce payload size
            unset($post->user);
        });
        

        return response()->json($posts);
    }
}
