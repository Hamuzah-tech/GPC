<?php

use App\Http\Controllers\Admin\ContactMessageController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\LeaderController;
use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\Admin\PageContentController;
use App\Http\Controllers\Admin\PillarController;
use App\Http\Controllers\Admin\ProgramController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\StrategicApproachController;
use App\Http\Controllers\Admin\ThematicAreaController;
use App\Http\Controllers\Admin\VolunteerApplicationController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\VolunteerController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/leadership', [PageController::class, 'leadership'])->name('leadership');
Route::get('/volunteer', [PageController::class, 'volunteer'])->name('volunteer');
Route::post('/volunteer', [VolunteerController::class, 'store'])->name('volunteer.store');
Route::get('/mission', [PageController::class, 'mission'])->name('mission');
Route::get('/vision', [PageController::class, 'vision'])->name('vision');
Route::get('/pillars', [PageController::class, 'pillars'])->name('pillars');
Route::get('/thematic-areas', [PageController::class, 'thematicAreas'])->name('thematic-areas');
Route::get('/strategic-approach', [PageController::class, 'strategicApproach'])->name('strategic-approach');
Route::get('/programs', [PageController::class, 'programs'])->name('programs');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
Route::get('/news', [PageController::class, 'news'])->name('news');

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->middleware('throttle:auth-login')->name('login.store');
    Route::get('/login/verify', [LoginController::class, 'showVerify'])->name('login.verify.show');
    Route::post('/login/verify', [LoginController::class, 'verify'])->middleware('throttle:auth-verify')->name('login.verify');
    Route::post('/login/verify/resend', [LoginController::class, 'resend'])->middleware('throttle:auth-login')->name('login.verify.resend');

    Route::get('/forgot-password', [PasswordResetController::class, 'requestForm'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'sendCode'])->middleware('throttle:auth-password')->name('password.email');
    Route::get('/reset-password', [PasswordResetController::class, 'resetForm'])->name('password.reset.show');
    Route::post('/reset-password', [PasswordResetController::class, 'reset'])->middleware('throttle:auth-password')->name('password.update');
});

Route::post('/logout', [LoginController::class, 'destroy'])->middleware('auth')->name('logout');

Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::resource('news', NewsController::class)->except(['show']);

    Route::get('messages', [ContactMessageController::class, 'index'])->name('messages.index');
    Route::get('messages/{message}', [ContactMessageController::class, 'show'])->name('messages.show');
    Route::delete('messages/{message}', [ContactMessageController::class, 'destroy'])->name('messages.destroy');

    Route::get('volunteers', [VolunteerApplicationController::class, 'index'])->name('volunteers.index');
    Route::get('volunteers/{volunteerApplication}', [VolunteerApplicationController::class, 'show'])->name('volunteers.show');
    Route::get('volunteers/{volunteerApplication}/cv', [VolunteerApplicationController::class, 'downloadCv'])->name('volunteers.cv');
    Route::delete('volunteers/{volunteerApplication}', [VolunteerApplicationController::class, 'destroy'])->name('volunteers.destroy');

    Route::middleware('admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::resource('leaders', LeaderController::class)->except(['show']);
        Route::resource('programs', ProgramController::class)->except(['show']);
        Route::resource('pillars', PillarController::class)->except(['show']);
        Route::resource('thematic-areas', ThematicAreaController::class)->except(['show'])->parameters([
            'thematic-areas' => 'thematicArea',
        ]);
        Route::resource('strategic-approaches', StrategicApproachController::class)->except(['show'])->parameters([
            'strategic-approaches' => 'strategicApproach',
        ]);
        Route::resource('faqs', FaqController::class)->except(['show']);

        Route::get('pages', [PageContentController::class, 'index'])->name('pages.index');
        Route::get('pages/{page}/edit', [PageContentController::class, 'edit'])->name('pages.edit');
        Route::put('pages/{page}', [PageContentController::class, 'update'])->name('pages.update');

        Route::get('settings', [SettingController::class, 'edit'])->name('settings.edit');
        Route::put('settings', [SettingController::class, 'update'])->name('settings.update');

        Route::get('gallery', [GalleryController::class, 'index'])->name('gallery.index');
        Route::post('gallery', [GalleryController::class, 'store'])->name('gallery.store');
        Route::delete('gallery/{gallery}', [GalleryController::class, 'destroy'])->name('gallery.destroy');

        Route::resource('users', UserController::class)->except(['show']);
    });
});
