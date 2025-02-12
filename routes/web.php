<?php

use App\Livewire\Home;
use App\Livewire\Profile\Reels;
use App\Livewire\Profile\Saved;
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
    Route::get('profile/{user}/reels', Reels::class)->name('profile.reels');
    Route::get('profile/{user}/saved', Saved::class)->name('profile.saved');
});

require __DIR__.'/auth.php';
