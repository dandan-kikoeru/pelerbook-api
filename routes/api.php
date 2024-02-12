<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
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

Route::middleware('auth:sanctum')->get('/user', [UserController::class, 'show']);
Route::post('/user/register', [UserController::class, 'register']);
Route::post('/user/login', [UserController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function () {
  Route::prefix('/user')->group(function () {
    Route::post('/logout', [UserController::class, 'logout']);
    Route::post('/update', [UserController::class, 'update']);
  });

  Route::prefix('/post')->group(function () {
    Route::post('/store', [PostController::class, 'store']);
    Route::post('/update/{id}', [PostController::class, 'update']);
    Route::post('/destroy/{id}', [PostController::class, 'destroy']);
    Route::get('/', [PostController::class, 'index']);
    Route::get('/{id}', [PostController::class, 'show']);
    Route::post('/like/{id}', [LikeController::class, 'post']);
  });

  Route::get('/profile/posts/{id}', [ProfileController::class, 'index']);
  Route::get('/profile/{id}', [ProfileController::class, 'show']);

  Route::prefix('/comment')->group(function () {
    Route::post('/store/{postId}', [CommentController::class, 'store']);
    Route::post('/update/{id}', [CommentController::class, 'update']);
    Route::post('/destroy/{id}', [CommentController::class, 'destroy']);
    Route::post('/like/{id}', [LikeController::class, 'comment']);
  });

  Route::prefix('/reply')->group(function () {
    Route::post('/store/{commentId}', [CommentController::class, 'store']);
    Route::post('/update/{id}', [CommentController::class, 'update']);
    Route::post('/destroy/{id}', [CommentController::class, 'destroy']);
  });
});
