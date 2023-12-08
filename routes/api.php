<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FollowingController;
use App\Http\Controllers\ForgetController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ResetController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\CommentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


// Login Route
Route::post('/login', [AuthController::class, 'Login']);

// Register Route
Route::post('/register', [AuthController::class, 'Register']);

// Forget Password Route
Route::post('/forgetpassword', [ForgetController::class, 'ForgetPassword']);

// Reset Password Route 
Route::post('/resetpassword', [ResetController::class, 'ResetPassword']);

// Current User Route 
Route::get('/user', [UserController::class, 'User'])->middleware('auth:api');

// Current User Route 
Route::post('/userprofile', [UserController::class, 'UserProfile'])->middleware('auth:api');

// Update User Route
Route::post('/updateprofile', [UserController::class, 'UpdateProfile'])->middleware('auth:api');

// Create New Post
Route::post('/createnewpost', [PostController::class, 'CreateNewPost'])->middleware('auth:api');

// Home Post
Route::post('/homeposts', [PostController::class, 'getHomePosts'])->middleware('auth:api');

// Home Post
Route::get('/homepostswithouttoken', [PostController::class, 'getHomePosts']);

// Following
Route::post('/following', [FollowingController::class, 'Following'])->middleware('auth:api');

//Unfollow
Route::delete('/unfollow', [FollowingController::class, 'unfollow'])->middleware('auth:api');

//Like Post
Route::post('/likepost', [LikeController::class, 'likePost'])->middleware('auth:api');

//Unlike Post
Route::delete('/unlikepost', [LikeController::class, 'unlikePost'])->middleware('auth:api');

//Post Detail
Route::post('/postdetail', [PostController::class, 'postdetail'])->middleware('auth:api');

//Write comment
Route::post('/writecomment', [CommentController::class, 'writeComment'])->middleware('auth:api');

//Get post comment
Route::post('/comment', [CommentController::class, 'getComments'])->middleware('auth:api');

//post
Route::post('/post', [PostController::class, 'getPosts'])->middleware('auth:api');

//User post
Route::post('/userpost', [PostController::class, 'getUserPosts'])->middleware('auth:api');
