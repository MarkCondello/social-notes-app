<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;


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

// blog routes
Route::get('/create-post', [PostController::class, 'showCreateForm'])->middleware('mustBeLoggedIn');
Route::post('/store-post', [PostController::class, 'storePost'])->middleware('mustBeLoggedIn');
Route::get('/posts/{post}', [PostController::class, 'viewPost']);
Route::delete('/posts/{post}', [PostController::class, 'deletePost'])->middleware('can:delete,post');
Route::get('/posts/{post}/edit', [PostController::class, 'showEditForm'])->middleware('can:update,post');
Route::put('/posts/{post}/update', [PostController::class, 'updatePost'])->middleware('can:update,post');

// profile routes
// {user:username} tells laravel to look for a user with the username value passed in the url
Route::get('/profile/{user:username}', [UserController::class, 'viewProfile'])->middleware('mustBeLoggedIn');


Route::get('/admin', function(){
  return 'ADMINS ONLY';
})->middleware('can:accessAdminPages');