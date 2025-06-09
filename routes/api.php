<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ValidationController;
use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class)->group(function () {
    Route::post('/register', 'register')->name('register');
    Route::post('/login', 'login')->name('login');
    Route::post('/logout', 'logout')->name('logout');
    Route::get('/user', 'getUser')->middleware('auth:sanctum')->name('getUser');
});

Route::controller(ValidationController::class)->group(function () {
    Route::get('/check-unique', 'checkUnique')->name('checkUnique');
});
