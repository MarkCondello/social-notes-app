<?php

use App\Events\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FollowController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// user routes
Route::get('/', [UserController::class, 'homePage'])->name('login'); // we set this route as the login for auth failure requests
Route::post('/register', [UserController::class, 'store'])->middleware('guest');
Route::post('/login', [UserController::class, 'login'])->middleware('guest');
Route::post('/logout', [UserController::class, 'logout'])->middleware('mustBeLoggedIn');
Route::get('/edit-avatar', [UserController::class, 'showAvatarForm'])->middleware('mustBeLoggedIn');
Route::post('/upload-avatar', [UserController::class, 'uploadAvatar'])->middleware('mustBeLoggedIn');

// follow routes
Route::post('/store-follower/{user:username}', [FollowController::class, 'storeFollower'])->middleware('mustBeLoggedIn');
Route::delete('/delete-follower/{user:username}', [FollowController::class, 'deleteFollower'])->middleware('mustBeLoggedIn');

// blog routes
Route::get('/create-post', [PostController::class, 'showCreateForm'])->middleware('mustBeLoggedIn');
Route::post('/store-post', [PostController::class, 'storePost'])->middleware('mustBeLoggedIn');
Route::get('/posts/{post}', [PostController::class, 'viewPost']);
Route::delete('/posts/{post}', [PostController::class, 'deletePost'])->middleware('can:delete,post');
Route::get('/posts/{post}/edit', [PostController::class, 'showEditForm'])->middleware('can:update,post');
Route::put('/posts/{post}/update', [PostController::class, 'updatePost'])->middleware('can:update,post');

Route::get('/search/{term}', [PostController::class, 'search']);

// profile routes
// {user:username} tells laravel to look for a user with the username value passed in the url
Route::get('/profile/{user:username}', [UserController::class, 'viewProfile'])->middleware('mustBeLoggedIn');
Route::get('/profile/{user:username}/followers', [UserController::class, 'viewFollowers'])->middleware('mustBeLoggedIn');
Route::get('/profile/{user:username}/following', [UserController::class, 'viewFollowing'])->middleware('mustBeLoggedIn');

Route::middleware('cache.headers:public;max_age=20;etag')->group(function(){
  Route::get('/profile/{user:username}/raw', [UserController::class, 'viewProfileRaw'])->middleware('mustBeLoggedIn');
  Route::get('/profile/{user:username}/followers/raw', [UserController::class, 'viewFollowersRaw'])->middleware('mustBeLoggedIn');
  Route::get('/profile/{user:username}/following/raw', [UserController::class, 'viewFollowingRaw'])->middleware('mustBeLoggedIn');
});

// pusher routes
Route::post('/send-chat-message', function(Request $request){
  $fields = $request->validate([
    'textvalue' => 'required'
  ]);

  if (!trim(strip_tags($fields['textvalue']))) {
    return response()->noContent();
  }

  broadcast(new ChatMessage([
    'username' => auth()->user()->username,
    'textvalue' => strip_tags($request->textvalue),
    'avatar' => auth()->user()->avatar,
  ]))->toOthers();

  return response()->noContent();

})->middleware('mustBeLoggedIn');

// Route::get('/admin', function(){
//   return 'ADMINS ONLY';
// })->middleware('can:accessAdminPages');