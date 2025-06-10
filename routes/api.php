<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ValidationController;
use App\Http\Controllers\VerificationController;
use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class)->group(function () {
    Route::post('/register', 'register')->name('register');
    Route::post('/login', 'login')->name('login');
    Route::post('/logout', 'logout')->name('logout');
    Route::get('/user', 'getUser')->middleware('auth:sanctum')->name('getUser');
    Route::post('/forgot-password', 'forgotPassword')->middleware('guest')->name('password.email');
    Route::post('/reset-password', 'resetPassword')->middleware('guest')->name('password.update');
});

Route::controller(VerificationController::class)->name('verification')->group(function () {
    Route::get('/email/verify/{id}/{hash}', 'verify')->name('.verify');
    Route::post('/email/verify', 'verifyEmail')->middleware(['auth:sanctum'])->name('.verifyEmail');
});

Route::controller(ValidationController::class)->group(function () {
    Route::get('/check-unique', 'checkUnique')->name('checkUnique');
});
