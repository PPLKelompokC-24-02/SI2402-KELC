<?php

use App\Http\Controllers\Web\Admin\ProfessionController;
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

Route::prefix('admin')->name('admin.')->middleware(['auth:web', 'role:admin'])->group(function () {
    Route::get('professions/{profession}/illustration', [ProfessionController::class, 'illustration'])->whereNumber('profession')->name('professions.illustration');
    Route::get('professions/{profession}/tools/{key}/logo', [ProfessionController::class, 'toolLogo'])->whereNumber('profession')->whereUuid('key')->name('professions.tool-logo');
    Route::resource('professions', ProfessionController::class)->whereNumber('profession');
});
