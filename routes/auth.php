<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Auth\SocialAuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    // Login Routes
    Route::get('login', [LoginController::class, 'create'])
        ->name('login');
    Route::post('login', [LoginController::class, 'store']);

    // Registration Routes
    Route::get('register', [RegisterController::class, 'create'])
        ->name('register');
    Route::post('register', [RegisterController::class, 'store']);

    // Password Reset Routes
    Route::controller(PasswordResetController::class)->group(function () {
        Route::get('forgot-password', 'create')
            ->name('password.request');
        Route::post('forgot-password', 'store')
            ->name('password.email');
        Route::get('reset-password/{token}', 'edit')
            ->name('password.reset');
        Route::post('reset-password', 'update')
            ->name('password.update');
    });

    // Social Authentication Routes
    Route::controller(SocialAuthController::class)
        ->prefix('auth')
        ->name('auth.')
        ->group(function () {
            Route::get('{provider}', 'redirect')
                ->name('redirect');
            Route::get('{provider}/callback', 'callback')
                ->name('callback');
        });
});

Route::middleware('auth')->group(function () {
    // Logout Route
    Route::post('logout', [LoginController::class, 'destroy'])
        ->name('logout');

    // Email Verification Routes
    Route::controller(EmailVerificationController::class)
        ->prefix('email')
        ->name('verification.')
        ->group(function () {
            Route::get('verify', 'notice')
                ->name('notice');
            Route::get('verify/{id}/{hash}', 'verify')
                ->middleware(['signed', 'throttle:6,1'])
                ->name('verify');
            Route::post('verification-notification', 'send')
                ->middleware('throttle:6,1')
                ->name('send');
        });
});
