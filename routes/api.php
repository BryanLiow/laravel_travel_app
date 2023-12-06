<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CreateNewPostController;
use App\Http\Controllers\FollowingController;
use App\Http\Controllers\ForgetController;
use App\Http\Controllers\GetHomePostsController;
use App\Http\Controllers\ResetController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\PostDetailController;

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

// Update User Route
Route::post('/updateprofile', [UserController::class, 'UpdateProfile'])->middleware('auth:api');

// Create New Post
Route::post('/createnewpost', [CreateNewPostController::class, 'CreateNewPost'])->middleware('auth:api');

// Home Post
Route::get('/homeposts', [GetHomePostsController::class, 'getHomePosts']);

// Following
Route::post('/following', [FollowingController::class, 'Following'])->middleware('auth:api');

//Unfollow
Route::delete('/unfollow', [FollowingController::class, 'unfollow'])->middleware('auth:api');

//Like Post
Route::post('/likepost', [LikeController::class, 'likePost'])->middleware('auth:api');

//Unlike Post
Route::delete('/unlikepost', [LikeController::class, 'unlikePost'])->middleware('auth:api');

//Post Detail
Route::post('/postdetail', [PostDetailController::class, 'postdetail'])->middleware('auth:api');
