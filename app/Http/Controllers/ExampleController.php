<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ExampleController extends Controller
{
    public function homepage()
    {
        return view('welcome');
    }
    public function post()
    {
        return view('single-post');
    }
}
