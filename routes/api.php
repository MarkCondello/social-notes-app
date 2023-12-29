<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', [UserController::class, 'loginApi']);
Route::post('/createPost', [PostController::class, 'storePostApi'])->middleware('auth:sanctum'); // Laravels built in API solution, it checks if a token is being passed
Route::delete('/deletePost/{post}', [PostController::class, 'deletePostApi'])->middleware('auth:sanctum', 'can:delete,post'); // We can use the Policies for the post on API's as well
