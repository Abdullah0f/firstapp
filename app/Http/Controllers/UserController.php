<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

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
}
