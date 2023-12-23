<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Follow;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    public function storeFollower(User $user)
    {
        if ($user->id === auth()->user()->id) {
            return back()->with('error', "You can not follow yourself.");
        }

        $existingFollowing = Follow::where([['user_id', '=', auth()->user()->id], ['followeduser', '=', $user->id]])->count();
        
        // dd($existingFollowing);
        if ($existingFollowing) {
            return back()->with('error', "You are already following $user->username.");
        }

        $follow = new Follow();
        $follow->user_id = auth()->user()->id;
        $follow->followeduser = $user->id;
        $follow->save(); // using the save method instead of ::create static method does not require allowed mass assignments to be added to the model
        return back()->with('success', "You are now following $user->username.");

    }
    public function deleteFollower(User $user)
    {
        if ($user->id === auth()->user()->id) {
            return back()->with('error', "You can not unfollow yourself.");
        }

        $existingFollowing = Follow::where([['user_id', '=', auth()->user()->id], ['followeduser', '=', $user->id]])->count();
        if ($existingFollowing) {
            Follow::where([['user_id', '=', auth()->user()->id], ['followeduser', '=', $user->id]])->delete();
            return back()->with('success', "You are now not following $user->username.");
        }

        return back()->with('error', "You are not following $user->username to unfollow them.");

    }
}
