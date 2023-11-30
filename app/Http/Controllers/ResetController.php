<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ResetRequest;
use App\Models\User;
use App\Models\PasswordResetToken; // Assuming this model exists for password reset tokens
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Lang; // Use for localization

class ResetController extends Controller
{
	public function ResetPassword(ResetRequest $request)
	{
		$email = $request->email;
		$token = $request->token;
		$password = Hash::make($request->password);
	
		$tokenData = PasswordResetToken::where('email', $email)->where('token', $token)->first();
	
		if (!$tokenData) {
			return response()->json([
				'message' => Lang::get('responses.token_or_email_invalid')
			], 401);
		}
	
		User::where('email', $email)->update(['password' => $password]);
	
		PasswordResetToken::where('email', $email)->delete();
	
		// Invalidate user session after password reset for security
	
		return response()->json([
			'message' => Lang::get('responses.password_change_success')
		], 200);
	}
	
}
