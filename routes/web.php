<?php

use App\Http\Controllers\AgencyController;
use App\Http\Controllers\AlertController;
use App\Http\Controllers\CalamityController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ResourceController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Agencies
    Route::get('/agencies', [AgencyController::class, 'index'])->name('agencies.index');
    Route::get('/agencies/{user}', [AgencyController::class, 'show'])->name('agencies.show');

    // Resources
    Route::get('/resources', [ResourceController::class, 'index'])->name('resources.index');
    Route::get('/resources/create', [ResourceController::class, 'create'])->name('resources.create');
    Route::post('/resources', [ResourceController::class, 'store'])->name('resources.store');
    Route::get('/resources/{resource}/edit', [ResourceController::class, 'edit'])->name('resources.edit');
    Route::put('/resources/{resource}', [ResourceController::class, 'update'])->name('resources.update');
    Route::delete('/resources/{resource}', [ResourceController::class, 'destroy'])->name('resources.destroy');

    // Calamities
    Route::get('/calamities', [CalamityController::class, 'index'])->name('calamities.index');
    Route::get('/calamities/create', [CalamityController::class, 'create'])->name('calamities.create');
    Route::post('/calamities', [CalamityController::class, 'store'])->name('calamities.store');
    Route::get('/calamities/{calamity}', [CalamityController::class, 'show'])->name('calamities.show');
    Route::put('/calamities/{calamity}', [CalamityController::class, 'update'])->name('calamities.update');

    // Alerts
    Route::get('/alerts', [AlertController::class, 'index'])->name('alerts.index');
    Route::get('/alerts/create', [AlertController::class, 'create'])->name('alerts.create');
    Route::post('/alerts', [AlertController::class, 'store'])->name('alerts.store');
    Route::post('/alerts/{alert}/acknowledge', [AlertController::class, 'acknowledge'])->name('alerts.acknowledge');

    // Messages
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{user}', [MessageController::class, 'show'])->name('messages.show');
    Route::post('/messages', [MessageController::class, 'store'])->name('messages.store');

    // Map
    Route::get('/map', [MapController::class, 'index'])->name('map.index');
    Route::get('/api/map-data', [MapController::class, 'data'])->name('map.data');
    Route::post('/api/location', [MapController::class, 'updateLocation'])->name('location.update');
});

require __DIR__.'/auth.php';
