<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ValidationController;
use App\Http\Controllers\VerificationController;
use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class)->group(function () {
    Route::middleware('guest')->group(function () {
        Route::post('/register', 'register')->name('register');
        Route::post('/login', 'login')->name('login');
        Route::post('/forgot-password', 'forgotPassword')->name('password.email');
        Route::post('/reset-password', 'resetPassword')->name('password.update');
    });
    Route::post('/logout', 'logout')->name('logout');
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/user', 'getUser')->name('getUser');
    });
});

Route::controller(VerificationController::class)->name('verification')->group(function () {
    Route::get('/email/verify/{id}/{hash}', 'verify')->middleware('signed')->name('.verify');
    Route::post('/email/verify', 'verifyEmail')->middleware(['auth:sanctum', 'throttle:6,1'])->name('.verifyEmail');
});

Route::controller(ValidationController::class)->group(function () {
    Route::get('/check-unique', 'checkUnique')->name('checkUnique');
});
