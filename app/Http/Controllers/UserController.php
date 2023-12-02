<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller; // Ensure you're using the correct base controller
use Exception;

class UserController extends Controller // Extend the base controller class
{
    public function User()
    {
        return Auth::user();
    }

    public function UpdateProfile(UpdateProfileRequest $request)
    {
        Log::info('UpdateProfile method called');

        try {
            Log::info('UpdateProfile function called.'); // Log that the function was called

            // Retrieve the authenticated user
            $user = Auth::user();

            Log::info('Authenticated user: ' . $user->id); // Log the authenticated user's ID

            // Update user's profile with the validated data
            $user->name = $request->name;
            $user->gender = $request->gender;
            $user->headline = $request->headline ?? $user->headline; 
            $user->country = $request->country ?? $user->country;

            // Log the new values
            Log::info('New name: ' . $request->name);
            Log::info('New gender: ' . $request->gender);
            Log::info('New headline: ' . ($request->headline ?? 'Not provided'));
            Log::info('New country: ' . ($request->country ?? 'Not provided'));

            // Save the changes
            $user->save();

            // Return the updated user profile
            return response()->json([
                'message' => 'Profile updated successfully!',
                'user' => $user
            ]);
        } catch (Exception $exception) {
            Log::error($exception);
            return response([
                'message' => $exception->getMessage()
            ], 400);
        }
    }
}
