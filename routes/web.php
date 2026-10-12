<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\ExploreController;
use App\Http\Controllers\FeedController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ConfirmationController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');

// Authentication routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/api/me', [AuthController::class, 'me'])->middleware('auth');

// Protected routes (require authentication)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/map', [MapController::class, 'index'])->name('map');
    Route::get('/explore', [ExploreController::class, 'index'])->name('explore');
    Route::get('/explore/{place}/detail', [ExploreController::class, 'detail'])->name('explore.detail');
    Route::get('/feed', [FeedController::class, 'index'])->name('feed');
    Route::get('/feed/{report}/detail', [FeedController::class, 'detail'])->name('feed.detail');
    Route::get('/report', [ReportController::class, 'index'])->name('report');
    Route::post('/report', [ReportController::class, 'store']);
    Route::post('/confirm', [ConfirmationController::class, 'store'])->name('confirm.store');

    // Admin routes
    Route::middleware('admin')->group(function () {
        Route::get('/admin', [AdminController::class, 'index'])->name('admin');
        Route::post('/admin/places', [AdminController::class, 'placesStore'])->name('admin.places.store');
        Route::put('/admin/places/{place}', [AdminController::class, 'placesUpdate'])->name('admin.places.update');
        Route::delete('/admin/places/{place}', [AdminController::class, 'placesDestroy'])->name('admin.places.destroy');
        Route::post('/admin/reports/{report}/moderate', [AdminController::class, 'moderate'])->name('admin.reports.moderate');
    });
});