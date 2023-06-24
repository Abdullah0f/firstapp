<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    //
    function register(Request $request)
    {
        $incomingData = $request->validate([
            'username' => 'required|min:5|max:20|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:3|max:20|confirmed'
        ]);
        User::create($incomingData);
        return "Hello from register";
    }
}
