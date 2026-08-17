<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/register', function () {
    return view('register');
})->name('register');

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');


Route::get('/posts', function () {
    return view('posts.index');
})->name('posts.index');


Route::get('/posts/create', function () {
    return view('posts.create');
})->name('posts.create');


Route::get('/posts/{id}/edit', function ($id) {
    return view('posts.edit', compact('id'));
})->name('posts.edit');
