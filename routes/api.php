<?php

use App\Http\Controllers\Api\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::get('/posts', [PostController::class, 'index']);
Route::get('/posts/trash', [PostController::class, 'trash']);
Route::get('/posts/{post}', [PostController::class, 'show']);
Route::post('/posts/store', [PostController::class, 'store']);
Route::put('/posts/{post}', [PostController::class, 'update']);
Route::patch('posts/{post}/restore', [PostController::class, 'restore']);
Route::delete('/posts/{post}', [PostController::class, 'destroy']);
Route::delete('/posts/{post}/drop', [PostController::class, 'drop']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
