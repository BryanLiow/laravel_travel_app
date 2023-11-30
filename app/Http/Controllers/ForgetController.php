<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\ForgetRequest;
use App\Models\PasswordResetToken; // Make sure this model exists and is properly set up
use Illuminate\Support\Facades\Mail;
use App\Mail\ForgetMail;
use Illuminate\Support\Str; // Use Str for generating a more secure token
use Exception; // Make sure to import the Exception class

class ForgetController extends Controller
{
    public function ForgetPassword(ForgetRequest $request)
    {
        $email = $request->email;

        // Check if the user with the provided email exists
        if (User::where('email', $email)->doesntExist()) {
            return response([
                'message' => 'Email Invalid'
            ], 401);
        }

        // Generate a random token using Laravel's Str helper for a more secure token
        $token = Str::random(60);

        try {
            // Insert the token into the database using the PasswordResetToken model
            PasswordResetToken::create([
                'email' => $email,
                'token' => $token,
            ]);

            // Send an email to the user with the token
            Mail::to($email)->send(new ForgetMail($token, $email));

            // Return a success response
            return response([
                'message' => 'Reset password email has been sent to your email address.'
            ], 200);
        } catch (Exception $exception) {
            // Return a response with the exception message in case of an error
            return response([
                'message' => $exception->getMessage()
            ], 400);
        }
    }
}
