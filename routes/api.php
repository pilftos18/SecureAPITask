<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Post;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PostController;

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

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');
Route::get('/posts', [PostController::class, 'index']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::post('/posts', [PostController::class, 'store']);
    Route::get('/posts/{id}', [PostController::class, 'show']);
    Route::put('/posts/{id}', [PostController::class, 'update']);  // ✅ PUT route
    Route::patch('/posts/{id}', [PostController::class, 'update']); // Optional: support PATCH too
    Route::delete('/posts/{id}', [PostController::class, 'destroy']);
});

// Route::middleware('auth:sanctum')->group(function () {
//     Route::get('/user', [AuthController::class, 'user']);
//     Route::post('/logout', [AuthController::class, 'logout']);

//     Route::apiResource('posts', PostController::class);
//     Route::get('/posts/image/{path}', [PostController::class, 'viewImage'])
//         ->middleware('signed')
//         ->name('image.view');
// });


