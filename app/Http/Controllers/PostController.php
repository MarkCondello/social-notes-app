<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    //
    public function showCreateForm()
    {
        return view('create-post');
    }

    public function storePost(Request $request)
    {
        $fields = $request->validate([
            'title' => 'required',
            'body' => 'required',
        ]);
        $fields['title'] = strip_tags($fields['title']);
        $fields['body'] = strip_tags($fields['body']);
        $fields['user_id'] = auth()->user()->id;
        
        $post = Post::create($fields);
        
        return redirect('/posts/' . $post->id)->with('success', 'Your post was created.');
        return 'Reached storePost method in PostController';
    }
    
    public function viewPost(Post $post) // type hinting with Post model which is passed in as a parameter from the route
    {
        return view('single-post', ['post' => $post]);
    }

}
