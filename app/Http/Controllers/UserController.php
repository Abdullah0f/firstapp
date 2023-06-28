<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class UserController extends Controller
{
    //
    function homepage()
    {
        if (auth()->check()) {
            return view('homepage-feed');
        }
        return view('homepage');
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

    function showProfile(User $username)
    {
        $posts = $username->posts()->get();

        return view('profile-posts', ["user" => $username, "posts" => $posts, "posts_count" => $posts->count()]);
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
