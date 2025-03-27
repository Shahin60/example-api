<?php

use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::apiResource('posts', PostController::class);
});



// // ✅ Authentication Routes
// Route::post('/register', [AuthController::class, 'register']);
// Route::post('/login', [AuthController::class, 'login']);

// // ✅ Protected Routes (Sanctum Authentication Required)
// Route::middleware('auth:sanctum')->group(function () {
//     Route::get('/user', [AuthController::class, 'userProfile']);
//     Route::post('/logout', [AuthController::class, 'logout']);

//     // ✅ Post Routes (CRUD)
//     Route::get('/posts', [PostController::class, 'index']); // Get all posts
//     Route::post('/posts', [PostController::class, 'store']); // Create post
//     Route::get('/posts/{id}', [PostController::class, 'show']); // Get single post
//     Route::put('/posts/{id}', [PostController::class, 'update']); // Update post
//     Route::delete('/posts/{id}', [PostController::class, 'destroy']); // Delete post
// });
