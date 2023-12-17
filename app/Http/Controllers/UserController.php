<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{

    function homePage()
    {
        if (auth()->check()) {
            return view('home-page-feed');
        } else {
            return view('home-page');
        }
    }

    function store(Request $request)
    {
        $fields = $request->validate([
            'username' => ['required', 'min:3', 'max:20', Rule::unique('users', 'username')],
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'password' => ['required', 'min:8', 'confirmed'],
        ]);

        $fields['password'] = bcrypt($fields['password']);
        $user = User::create($fields);
        auth()->login($user); // this adds the cookie for the new user sesssion.
        return redirect('/')->with('success', 'You registered.');
    }

    function login(Request $request)
    {
        $fields = $request->validate([
            'loginusername' => ['required',],
            'loginpassword' => ['required',],
        ]);
        if (auth()->attempt([
            'username' => $fields['loginusername'],
            'password' => $fields['loginpassword'],
        ])) {
            $request->session()->regenerate(); // creates a laravel_session cookie
            // return 'You logged in';
            return redirect('/')->with('success', 'You logged in.');
        } else {
            // return 'You can NOT log in';
            return redirect('/')->with('error', 'Invalid login.');
        }
    }

    function logout()
    {
        auth()->logout();
        return redirect('/')->with('success', 'You logged out.');
    }

    function viewProfile(User $user)
    {
        return view('profile-posts', [
            'user' => $user,
            'posts' => $user->posts()->latest()->get(),
            'postCount' => $user->posts()->count(),
        ]);
    }

    public function showAvatarForm()
    {
        return view('avatar-form');
    }
    public function uploadAvatar(Request $request)
    {
        $request->file('avatar')->store('avatars'); // this stores images in /storage/app/avatars/
        return 'HEY';
    }
}
