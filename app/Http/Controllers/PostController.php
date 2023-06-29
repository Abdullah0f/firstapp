<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class PostController extends Controller
{
    //
    function search($term)
    {
        $posts = Post::search($term)->get();
        $posts->load('user');
        return view('search', ["posts" => $posts]);
    }
    function showCreatePost()
    {
        return view('create-post');
    }

    function storeNewPost(Request $request)
    {
        $incomingData = $request->validate([
            'title' => 'required|min:5|max:100',
            'body' => 'required|min:5|max:1000'
        ]);
        $incomingData['title'] = strip_tags($incomingData['title']);
        $incomingData['body'] = strip_tags($incomingData['body']);
        $incomingData['user_id'] = auth()->id();
        $post = Post::create($incomingData);
        // redirect to the homepage
        return redirect('/posts/' . $post->id)->with('success', 'new post created successfully');
    }
    function showPost(Post $post)
    {
        $post['body'] = Str::markdown($post->body);
        return view('single-post', ["post" => $post]);
    }
    function delete(Post $post)
    {
        $post->delete();
        return redirect('/profile/' . auth()->user()->username)->with('success', 'Post deleted successfully');
    }
    function showEditPost(Post $post)
    {
        return view('edit-post', ["post" => $post]);
    }
    function update(Post $post)
    {
        $incomingData = request()->validate([
            'title' => 'required|min:5|max:100',
            'body' => 'required|min:5|max:1000'
        ]);
        $incomingData['title'] = strip_tags($incomingData['title']);
        $incomingData['body'] = strip_tags($incomingData['body']);
        $post->update($incomingData);
        return redirect('/posts/' . $post->id)->with('success', 'Post updated successfully');
    }
}
