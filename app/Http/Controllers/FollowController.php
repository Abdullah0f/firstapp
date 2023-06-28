<?php

namespace App\Http\Controllers;

use App\Models\Follow;
use App\Models\User;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    //
    function createFollow(User $user)
    {
        // cant follow yourself
        if ($user->id == auth()->id()) {
            return back()->with("failure", "You can't follow yourself");
        }
        // check if already following
        if (Follow::where("user_id", auth()->id())->where("followeduser", $user->id)->exists()) {
            return back()->with("failure", "You are already following this user");
        }
        $follow = new Follow;
        $follow->user_id = auth()->id();
        $follow->followeduser = $user->id;
        $follow->save();
        return back()->with("success", "You are now following this user");
    }
    function removeFollow(User $user)
    {
        if (!Follow::where("user_id", auth()->id())->where("followeduser", $user->id)->exists()) {
            return back()->with("failure", "You are not following this user");
        }
        $follow = Follow::where("user_id", auth()->id())->where("followeduser", $user->id)->first();
        $follow->delete();
        return back()->with("success", "You are no longer following this user");
    }
}
