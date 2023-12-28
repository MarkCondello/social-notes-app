<?php

namespace App\Http\Controllers;

use App\Models\Post;

use App\Models\User;
use App\Models\Follow;
//  use Intervention\Image\Image;
use App\Events\ExampleEvent;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\View;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Imagick\Driver;

class UserController extends Controller
{

    function homePage()
    {
        if (auth()->check()) {
            return view('home-page-feed',['posts' => auth()->user()->feedPosts()->latest()->paginate(10),]);
        }
        $postCount = Cache::remember('postCount', 20, function(){
            // sleep(5);
            return Post::count();
        });// func only runs if value is not added to cache
        return view('home-page', ['postsCount' => $postCount]);
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
            event(new ExampleEvent(['username' => auth()->user()->username, 'action' => 'login']));
            return redirect('/')->with('success', 'You logged in.');
        } else {
            // return 'You can NOT log in';
            return redirect('/')->with('error', 'Invalid login.');
        }
    }

    function logout()
    {
        event(new ExampleEvent(['username' => auth()->user()->username, 'action' => 'logout']));
        auth()->logout();
        return redirect('/')->with('success', 'You logged out.');
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

    private function getSharedData($user)
    {
        $currentlyFollowing = 0;
        if (auth()->check()) {
            $currentlyFollowing = Follow::where([['user_id', '=', auth()->user()->id],['followeduser', '=', $user->id]])->count();
        }
        View::share('sharedData', [
            'user' => $user,
            'postCount' => $user->posts()->count(),
            'currentlyFollowing' => $currentlyFollowing,
            'followerCount' => $user->followers()->count(),
            'followingCount' => $user->following()->count(),
        ]);
    }

    public function viewProfile(User $user)
    {
        $this->getSharedData($user);
        return view('profile-posts', [
            'posts' => $user->posts()->latest()->get(),
        ]);
    }

    public function viewFollowers(User $user)
    {
        $this->getSharedData($user);
        return view('profile-followers', [
            'followers' => $user->followers()->latest()->get(),
       ]);
    }
    public function viewFollowing(User $user)
    {
        $this->getSharedData($user);
        return view('profile-following', [
            'following' => $user->following()->latest()->get(),
       ]);
    }
}
