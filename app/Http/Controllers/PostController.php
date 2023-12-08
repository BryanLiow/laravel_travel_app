<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CreateNewPostRequest; // Use your custom request for validation
use Storage;
use Illuminate\Support\Facades\DB;
use Exception;
use App\Http\Requests\PostDetailRequest;
use App\Models\Following;
use App\Models\Like;
use App\Models\PostImage;
use App\Models\Post;
use Auth;


class PostController extends Controller
{
    public function getHomePosts(Request $request)
    {
        $excludeIds = $request->query('exclude', []);
        $baseUrl = 'https://bryantravelapp.s3.eu-west-1.amazonaws.com/post-photos/';
        $userId = auth()->id(); // Get the ID of the currently authenticated user

        // Fetch posts with additional details
        $posts = Post::whereNotIn('id', $excludeIds)
            ->with(['user', 'images', 'likes'])
            ->inRandomOrder()
            ->take(8)
            ->get(['id', 'user_id', 'title', 'caption', 'country', 'created_at']);

        // If user is authenticated, fetch liked post IDs for the user
        $likedPostIds = $userId ? Like::where('user_id', $userId)->pluck('post_id')->toArray() : [];

        $posts->each(function ($post) use ($baseUrl, $likedPostIds) {
            // Prepend base URL to the smallest image path
            $post->image = $post->images->first() ? $baseUrl . $post->images->first()->image_path : null;

            // Add additional post details
            $post->username = $post->user->username;
            $post->likesCount = $post->likes->count();
            $post->createdOn = $post->created_at->format('Y-m-d H:i:s');

            // Check if the post is liked by the user
            $post->isLiked = in_array($post->id, $likedPostIds);

            // Optionally remove unnecessary relationships
            unset($post->user, $post->images, $post->likes);
        });

        return response()->json($posts);
    }

    public function CreateNewPost(CreateNewPostRequest $request)
    {
        // Wrap the operation in a transaction
        DB::beginTransaction();

        try {
            $post = new Post();
            $post->user_id = auth()->user()->id; // Assuming user is authenticated and ID is available
            $post->title = $request->title;
            $post->caption = $request->caption;
            $post->country = $request->country;
            $post->save();

            if ($request->has('postPhoto')) {
                foreach ($request->file('postPhoto') as $file) {
                    // Store each image in AWS S3
                    $path = Storage::disk('post_photos')->put('posts', $file, 'public');

                    // Create a new Image instance and associate it with the post
                    $image = new PostImage();
                    $image->post_id = $post->id;
                    $image->image_path = $path;
                    $image->save();
                }
            }

            DB::commit();

            // Return a success response
            return response()->json(['message' => 'Post created successfully!'], 200);
        } catch (Exception $e) {
            // An error occurred; cancel the transaction...
            DB::rollback();

            // ...and return an error response
            return response()->json(['message' => 'Failed to create post', 'error' => $e->getMessage()], 500);
        }
    }
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
            'likes' => $postLikesCount,
            'imagePaths' => $imagePaths
        ]);
    }

    public function getPosts(Request $request)
    {
        $userId = Auth::id(); // Get the ID of the authenticated user
        $baseUrl = 'https://bryantravelapp.s3.eu-west-1.amazonaws.com/post-photos/';

        // Fetch posts by the user, sorted by ID in descending order
        $posts = Post::where('user_id', $userId)
            ->with(['user', 'likes']) // Load the likes relationship
            ->orderBy('id', 'desc')
            ->get(['id', 'user_id', 'title', 'caption', 'country', 'created_at']);

        $posts->each(function ($post) use ($baseUrl) {
            $smallestImage = $post->images()->orderBy('id', 'asc')->first();
            $post->image = $smallestImage ? $baseUrl . $smallestImage->image_path : null;
            $post->username = $post->user->username;
            $post->likesCount = $post->likes->count(); // Count the number of likes
            $post->createdOn = $post->created_at->format('Y-m-d H:i:s');
            unset($post->user, $post->likes); // Optionally remove the user and likes objects to reduce payload size
        });

        return response()->json($posts);
    }

    public function getUserPosts(Request $request)
    {
        $userId = $request->input('userId'); // Get the user ID from the request
        $baseUrl = 'https://bryantravelapp.s3.eu-west-1.amazonaws.com/post-photos/';

        // Fetch posts by the specified user, sorted by ID in descending order
        $posts = Post::where('user_id', $userId)
            ->with(['user', 'likes']) // Load the likes relationship
            ->orderBy('id', 'desc')
            ->get(['id', 'user_id', 'title', 'caption', 'country', 'created_at']);

        $posts->each(function ($post) use ($baseUrl) {
            $smallestImage = $post->images()->orderBy('id', 'asc')->first();
            $post->image = $smallestImage ? $baseUrl . $smallestImage->image_path : null;
            $post->username = $post->user->username;
            $post->likesCount = $post->likes->count(); // Count the number of likes
            $post->createdOn = $post->created_at->format('Y-m-d H:i:s');
            unset($post->user, $post->likes); // Optionally remove the user and likes objects to reduce payload size
        });

        return response()->json($posts);
    }
}
