<?php

namespace App\Http\Controllers;

use App\Jobs\SendNewPostEmail;
use App\Models\Post;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function updatePost(Post $post, Request $request)
    {
        $fields = $request->validate([
            'title' => 'required',
            'body' => 'required',
        ]);
        $fields['title'] = strip_tags($fields['title']);
        $fields['body'] = strip_tags($fields['body']);
        $post->update($fields);
        
        return back()->with('success', 'Your post was updated.');
    }
    public function showEditForm(Post $post)
    {
        return view('edit-post', ['post' => $post]);
    }
    public function deletePost(Post $post)
    {
        // if (auth()->user()->cannot('delete', $post)) { // we use route pplicy middleware instead
        //     return 'Ah ah, No no no.';
        // }
        $post->delete();
        return redirect('/profile/' . auth()->user()->username)
            ->with('success', 'Post was deleted...');
    }

    public function deletePostApi(Post $post)
    {
        $postTitle = $post->title;
        $post->delete();
        return "The post $postTitle was succefully deleted.";
    }
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

        dispatch(new SendNewPostEmail([
            'sendTo' => auth()->user()->email,
            'name' => auth()->user()->username,
            'title' => $post->title,
        ]));

        return redirect('/posts/' . $post->id)->with('success', 'Your post was created.');
    }

    public function storePostApi(Request $request)
    {
        $fields = $request->validate([
            'title' => 'required',
            'body' => 'required',
        ]);
        $fields['title'] = strip_tags($fields['title']);
        $fields['body'] = strip_tags($fields['body']);
        $fields['user_id'] = auth()->user()->id;
        $post = Post::create($fields);

        dispatch(new SendNewPostEmail([
            'sendTo' => auth()->user()->email,
            'name' => auth()->user()->username,
            'title' => $post->title,
        ]));

        return $post->id;
    }
    
    public function viewPost(Post $post) // type hinting with Post model which is passed in as a parameter from the route
    {
        $post['body'] = strip_tags(Str::markdown($post->body), '<p><h3><h4><h5><h6><ul><ol><li><blockquote><pre><code><em><strong><del><sup><sub><table><thead><tbody><tr><th><td>');
        return view('single-post', ['post' => $post]);
    }

    public function search($term)
    {
        return Post::search($term)->get()->load('user:username,id,avatar'); // Scout\Searchable
        // return Post::where('title', 'LIKE', '%'. $term . '%')->orWhere('body', 'LIKE', '%'. $term . '%')->get();
    }
}
