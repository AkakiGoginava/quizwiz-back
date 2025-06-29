<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\InfoController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\ValidationController;
use App\Http\Controllers\VerificationController;
use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class)->group(function () {
    Route::middleware('guest')->group(function () {
        Route::post('/register', 'register')->name('register');
        Route::post('/login', 'login')->name('login');
        Route::post('/forgot-password', 'forgotPassword')->name('password.email');
        Route::post('/reset-password', 'resetPassword')->name('password.update');
        Route::post('/check-password-token', 'checkPasswordToken')->name('password.checkToken');
    });
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', 'logout')->name('logout');
        Route::get('/user', 'getUser')->name('getUser');
    });
});

Route::controller(VerificationController::class)->prefix('/email')->name('verification')->group(function () {
    Route::post('/verify', 'verifyEmail')->middleware(['auth:sanctum', 'throttle:6,1'])->name('.verifyEmail');
    Route::post('/check-verify-token', 'checkVerifyToken')->name('.checkVerifyToken');
});

Route::controller(ValidationController::class)->group(function () {
    Route::get('/check-unique', 'checkUnique')->name('checkUnique');
});

Route::controller(QuizController::class)->prefix('/quizzes')->name('quiz.')->group(function () {
    Route::get('/', 'getQuizzes')->name('quizzes');
    Route::get('/{id}', 'getQuiz')->name('quiz');
    Route::post('/{id}/start', 'startQuiz')->name('start');
    Route::post('/{id}/end', 'endQuiz')->name('end');
});

Route::controller(InfoController::class)->group(function () {
    Route::get('/categories', 'getCategories')->name('categories');
    Route::get('/difficulties', 'getDifficulties')->name('difficulties');
    Route::get('/socials', 'getSocials')->name('socials');
    Route::get('/landing-info', 'getLandingInfo')->name('landingInfo');
});
