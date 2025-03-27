<?php

use App\Http\Controllers\UserPostController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/', function () {
    return view('login');
});

// Route::get('index', [UserPostController::class, 'index']);
Route::view('index', 'index');

Route::view('register', 'register')->name('register');
Route::view('store', 'store')->name('addPost');
