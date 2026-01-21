<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::livewire('/', 'pages::welcome')->name('home');

Route::middleware('guest')->group(function () {
    Route::livewire('/login', 'pages::auth.login')->name('login');
    Route::livewire('/register', 'pages::auth.register')->name('register');

    Route::livewire('/forgot-password', 'pages::auth.forgot-password')->name('forgot-password');
    Route::livewire('/reset-password/{token}', 'pages::auth.reset-password')->name('password.reset');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', function () {
        Auth::logout();

        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('login');
    })->name('logout');

    Route::post('/email/verification-notification', ['pages::auth.verify', 'sendVerifyMail'])
        ->middleware('throttle:6,1')->name('verification.send');

    Route::livewire('/email/verify', 'pages::auth.verify')->name('verification.notice');
});
