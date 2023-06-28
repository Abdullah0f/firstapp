<?php

namespace App\Http\Controllers;

use App\Models\Follow;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    //
    function createFollow(Request $request)
    {
        // cant follow yourself
        if ($request->userid == auth()->id()) {
            return back()->with("failure", "You can't follow yourself");
        }
        // check if already following
        if (Follow::where("user_id", auth()->id())->where("followeduser", $request->userid)->exists()) {
            return back()->with("failure", "You are already following this user");
        }
        $follow = new Follow;
        $follow->user_id = auth()->id();
        $follow->followeduser = $request->userid;
        $follow->save();
        return back()->with("success", "You are now following this user");
    }
    function removeFollow(Request $request)
    {
        $follow = Follow::where("user_id", auth()->id())->where("followeduser", $request->userid)->first();
        $follow->delete();
        return back()->with("success", "You are no longer following this user");
    }
}
