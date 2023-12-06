<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreFollowingRequest;
use App\Http\Requests\UnfollowRequest;
use App\Models\Following;
use Auth;

class FollowingController extends Controller
{
    public function Following(StoreFollowingRequest $request)
    {
        $userId = Auth::id();
        $followingUserId = $request->following_user_id;

        // Check if already following
        $existingFollow = Following::where('user_id', $userId)
            ->where('following_user_id', $followingUserId)
            ->first();

        if ($existingFollow) {
            return response()->json(['message' => 'Already following this user.'], 409);
        }

        // Create a new following record
        Following::create([
            'user_id' => $userId,
            'following_user_id' => $followingUserId
        ]);

        return response()->json(['message' => 'Successfully followed the user.'], 200);
    }

    public function unfollow(UnfollowRequest $request)
    {
        $userId = Auth::id();
        $followingUserId = $request->following_user_id;

        // Find the following record
        $following = Following::where('user_id', $userId)
            ->where('following_user_id', $followingUserId)
            ->first();

        if (!$following) {
            return response()->json(['message' => 'Following relationship not found.'], 404);
        }

        // Delete the following record
        $following->delete();

        return response()->json(['message' => 'Successfully unfollowed the user.'], 200);
    }
}
