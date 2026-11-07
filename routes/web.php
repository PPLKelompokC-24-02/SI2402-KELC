<?php

use App\Http\Controllers\Web\Admin\ActionPlanTemplateController as AdminActionPlanTemplateController;
use App\Http\Controllers\Web\Admin\CareerEventController;
use App\Http\Controllers\Web\Admin\CareerPathController;
use App\Http\Controllers\Web\Admin\ProfessionController;
use App\Http\Controllers\Web\AuthenticationController;
use App\Http\Controllers\Web\CareerActionPlanController;
use App\Http\Controllers\Web\CareerExplorationController;
use App\Http\Controllers\Web\CvBuilderController;
use App\Http\Controllers\Web\FavoriteProfessionController;
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

Route::middleware(['auth:web', 'role:mahasiswa'])->group(function () {
    Route::name('careers.')->group(function () {
        Route::get('career-exploration', [CareerExplorationController::class, 'index'])->name('index');
        Route::get('professions', [CareerExplorationController::class, 'professions'])->name('professions.index');
        Route::get('professions/{profession}', [CareerExplorationController::class, 'show'])->whereNumber('profession')->name('show');
        Route::get('professions/{profession}/illustration', [CareerExplorationController::class, 'illustration'])->whereNumber('profession')->name('illustration');
        Route::get('professions/{profession}/tools/{key}/logo', [CareerExplorationController::class, 'toolLogo'])->whereNumber('profession')->whereUuid('key')->name('tool-logo');

        Route::post('professions/{profession}/favorite', [FavoriteProfessionController::class, 'store'])->whereNumber('profession')->name('favorite.store');
        Route::delete('professions/{profession}/favorite', [FavoriteProfessionController::class, 'destroy'])->whereNumber('profession')->name('favorite.destroy');
        Route::post('professions/{profession}/favorite/toggle', [FavoriteProfessionController::class, 'toggle'])->whereNumber('profession')->name('favorite.toggle');
        Route::delete('saved-professions/{savedProfession}', [FavoriteProfessionController::class, 'destroyPivot'])->whereNumber('savedProfession')->name('saved-professions.destroy');
    });

    Route::prefix('career-action-plan')->name('action-plan.')->group(function () {
        Route::get('/', [CareerActionPlanController::class, 'index'])->name('index');
        Route::post('from-template/{action_template}', [CareerActionPlanController::class, 'storeFromTemplate'])->name('from-template')->whereNumber('action_template');
        Route::delete('{action_plan}', [CareerActionPlanController::class, 'destroy'])->name('destroy')->whereNumber('action_plan');
    });

    Route::prefix('cv-builder')->name('cv.')->group(function () {
        Route::get('/', [CvBuilderController::class, 'index'])->name('index');
        Route::get('edit', [CvBuilderController::class, 'edit'])->name('edit');
        Route::put('/', [CvBuilderController::class, 'update'])->name('update');
        Route::get('preview', [CvBuilderController::class, 'preview'])->name('preview');
    });
});

Route::prefix('admin')->name('admin.')->middleware(['auth:web', 'role:admin'])->group(function () {
    Route::get('professions/{profession}/illustration', [ProfessionController::class, 'illustration'])->whereNumber('profession')->name('professions.illustration');
    Route::get('professions/{profession}/tools/{key}/logo', [ProfessionController::class, 'toolLogo'])->whereNumber('profession')->whereUuid('key')->name('professions.tool-logo');
    Route::resource('professions', ProfessionController::class)->whereNumber('profession');
    Route::post('action-templates/{action_template}/toggle-status', [AdminActionPlanTemplateController::class, 'toggleStatus'])->name('action-templates.toggle-status')->whereNumber('action_template');
    Route::resource('action-templates', AdminActionPlanTemplateController::class)->parameters(['action-templates' => 'action_template'])->whereNumber('action_template');

    Route::get('events/{event}/poster', [CareerEventController::class, 'poster'])->whereNumber('event')->name('events.poster');
    Route::resource('events', CareerEventController::class)->whereNumber('event');

    Route::get('professions/{profession}/career-paths', [CareerPathController::class, 'stages'])->whereNumber('profession')->name('professions.career-paths.index');
    Route::post('professions/{profession}/career-paths', [CareerPathController::class, 'store'])->whereNumber('profession')->name('professions.career-paths.store');
    Route::put('professions/{profession}/career-paths/reorder', [CareerPathController::class, 'reorder'])->whereNumber('profession')->name('professions.career-paths.reorder');
    Route::get('professions/{profession}/career-paths/{stage}', [CareerPathController::class, 'show'])->whereNumber('profession')->whereNumber('stage')->name('professions.career-paths.show');
    Route::put('professions/{profession}/career-paths/{stage}', [CareerPathController::class, 'update'])->whereNumber('profession')->whereNumber('stage')->name('professions.career-paths.update');
    Route::delete('professions/{profession}/career-paths/{stage}', [CareerPathController::class, 'destroy'])->whereNumber('profession')->whereNumber('stage')->name('professions.career-paths.destroy');
});
