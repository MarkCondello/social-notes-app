<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function showCreateForm()
    {
        // if (!auth()->check()) { // we use route middleware instead
        //     return redirect('/');
        // }
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
    }
    
    public function viewPost(Post $post) // type hinting with Post model which is passed in as a parameter from the route
    {
        $post['body'] = strip_tags(Str::markdown($post->body), '<p><h3><h4><h5><h6><ul><ol><li><blockquote><pre><code><em><strong><del><sup><sub><table><thead><tbody><tr><th><td>');
        return view('single-post', ['post' => $post]);
    }
}
