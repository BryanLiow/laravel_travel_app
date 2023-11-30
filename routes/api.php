<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ForgetController;
use App\Http\Controllers\ResetController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


// Login Routes 
Route::post('/login', [AuthController::class, 'Login']);

// Register Routes 
Route::post('/register', [AuthController::class, 'Register']);

// Forget Password Routes 
Route::post('/forgetpassword', [ForgetController::class, 'ForgetPassword']);

// Reset Password Routes 
Route::post('/resetpassword', [ResetController::class, 'ResetPassword']);
