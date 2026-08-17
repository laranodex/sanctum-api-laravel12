<?php

use App\Http\Controllers\APIs\AuthController;
use App\Http\Controllers\APIs\PostController;
use Illuminate\Support\Facades\Route;

Route::post('/signup', [AuthController::class, 'signUp'])
    ->name('signup');

Route::post('/signin', [AuthController::class, 'signIn'])
    ->name('signin');

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/signout', [AuthController::class, 'signOut'])
        ->name('signout');

    Route::resource('/posts', PostController::class);
});
