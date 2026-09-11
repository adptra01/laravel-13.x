<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Volt::route('/profile', 'profile/edit')
        ->name('profile.edit');

    Volt::route('/users', 'users/index')
        ->name('users.index');
});

require __DIR__.'/auth.php';
