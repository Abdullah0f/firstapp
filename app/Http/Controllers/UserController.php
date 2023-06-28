<?php

namespace App\Http\Controllers;

use App\Models\Follow;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class UserController extends Controller
{
    //
    function homepage()
    {
        if (!auth()->check()) {
            return view('homepage');
        }
        return view('homepage-feed', ["posts" => auth()->user()->feedPosts()->latest()->paginate(4)]);
    }
    function register(Request $request)
    {
        $incomingData = $request->validate([
            'username' => 'required|min:5|max:20|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:3|max:20|confirmed'
        ]);
        $user = User::create($incomingData);
        auth()->login($user);
        return redirect('/')->with('success', 'You have been registered');
    }

    function login(Request $request)
    {
        // regenerates the session id
        $request->session()->regenerate();
        $incomingData = $request->validate([
            'loginusername' => 'required',
            'loginpassword' => 'required|min:3|max:20'
        ]);
        if (!auth()->attempt(["username" => $incomingData['loginusername'], "password" => $incomingData['loginpassword']])) {
            return redirect('/')->with("failure", "Invalid credentials");
        }
        return redirect('/')->with('success', 'You have been logged in');
    }
    function logout()
    {
        auth()->logout();
        return redirect('/')->with('success', 'You have been logged out');
    }
    function profile($user)
    {
        // sharedData
        $doesFollow = false;
        if (auth()->check()) {
            $doesFollow = Follow::where("user_id", auth()->id())->where("followeduser", $user->id)->exists();
        }
        $sharedData = [
            "doesFollow" => $doesFollow,
            "user" => $user,
            "posts_count" => $user->posts()->count(),
            "followers_count" => $user->followers()->count(),
            "following_count" => $user->following()->count()
        ];
        view()->share("sharedData", $sharedData);
    }
    function showProfile(User $username)
    {
        // use sharedData that in this->profile
        $this->profile($username);

        $posts = $username->posts()->get();

        return view('profile-posts', ["posts" => $posts, "active" => "posts"]);
    }
    function showProfileFollowers(User $username)
    {
        $this->profile($username);
        $follows = $username->followers()->get();


        return view('profile-followers', ["follows" => $follows, "active" => "followers"]);
    }
    function showProfileFollowing(User $username)
    {
        $this->profile($username);
        $follows = $username->following()->get();


        return view('profile-following', ["follows" => $follows, "active" => "following"]);
    }
    function showAvatarForm()
    {
        return view('avatar-form');
    }
    function updateAvatar(Request $request)
    {
        $incomingData = $request->validate([
            'avatar' => 'required|image|max:3000'
        ]);
        $user = auth()->user();
        $filename = $user->id . "-" . uniqid() . ".jpg";
        $img = Image::make($request->file('avatar'))->fit(120, 120)->encode('jpg');
        Storage::put("public/avatars/" . $filename, $img);

        $oldAvatar = $user->avatar;

        $user->avatar = $filename;
        $user->save();
        if ($oldAvatar != "default-avatar.jpg") {
            Storage::delete(str_replace("/storage/avatars/", "public/avatars/", $oldAvatar));
        }
        return redirect('/profile/' . auth()->user()->username)->with('success', 'Avatar updated successfully');
    }
}
