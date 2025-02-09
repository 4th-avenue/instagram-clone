<?php

use App\Livewire\Home;
use Illuminate\Support\Facades\Route;
use App\Livewire\Profile\Home as ProfileHome;

Route::get('/', Home::class)
    ->middleware('auth')
    ->name('/');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    // Route::view('profile', 'profile'->name('profile');
    Route::get('profile/{user}', ProfileHome::class)->name('profile.home');
});

require __DIR__.'/auth.php';
