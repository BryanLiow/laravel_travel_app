<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CreateNewPostRequest; // Use your custom request for validation
use App\Models\Post;
use App\Models\PostImage;
use Storage;
use Illuminate\Support\Facades\DB;
use Exception;

class CreateNewPostController extends Controller
{
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
}
