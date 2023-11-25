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
Route::get('/', [UserController::class, 'homePage']);
Route::post('/register', [UserController::class, 'store']);
Route::post('/login', [UserController::class, 'login']);
Route::post('/logout', [UserController::class, 'logout']);

//blog routes
Route::get('/create-post', [PostController::class, 'showCreateForm']);
Route::post('/store-post', [PostController::class, 'storePost']);
Route::get('/posts/{post}', [PostController::class, 'viewPost']);