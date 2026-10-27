<?php

use App\Http\Controllers\Web\AuthenticationController;
use App\Http\Controllers\Web\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AuthenticationController::class, 'loginPage'])->name('landing');
Route::get('/login', [AuthenticationController::class, 'loginPage'])->name('login');
Route::get('/register', [AuthenticationController::class, 'registerPage'])->name('register');

Route::middleware('guest:web')->group(function () {
    Route::post('/login', [AuthenticationController::class, 'login'])->middleware('throttle:login')->name('login.store');
    Route::post('/register', [AuthenticationController::class, 'register'])->middleware('throttle:register')->name('register.store');
});

Route::post('/logout', [AuthenticationController::class, 'logout'])->middleware('auth:web')->name('logout');

Route::get('/home', HomeController::class)->middleware(['auth:web', 'role:mahasiswa,admin,assessor'])->name('home');
