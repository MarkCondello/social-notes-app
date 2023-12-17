<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
//  use Intervention\Image\Image;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Imagick\Driver;

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
        $request->validate([
            'avatar' => 'required|image|max:3000',
        ]);
        $user = auth()->user();
        $manager = new ImageManager(new Driver());
        $image = $manager->read($request->file('avatar'))->coverDown(120, 120)->toJpeg();
        $filename = $user->id."-" . \uniqid() . ".jpg";
        Storage::put("/public/avatars/". $filename, $image);

        $oldAvatar = $user->avatar;

        $user->avatar = $filename;
        $user->save();

        if ($oldAvatar != "/fallback-avatar.jpg") {
            Storage::delete(str_replace('/storage', 'public/', $oldAvatar)); // need to delete the image found in public, not the storage dir
        }
        return redirect('/')->with('success', 'You updated your avatar.');

    }
}
