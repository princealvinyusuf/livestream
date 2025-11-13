<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StreamerProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

Route::get('/jobs', [JobController::class, 'index'])->name('jobs.index');
Route::get('/jobs/{job:slug}', [JobController::class, 'show'])->name('jobs.show');

Route::get('/streamers/{streamerProfile:user_id}', [StreamerProfileController::class, 'show'])
    ->name('streamers.show');

Route::middleware(['auth', 'verified', 'role:employer'])->group(function () {
    Route::get('/employer/jobs/create', [JobController::class, 'create'])->name('jobs.create');
    Route::post('/employer/jobs', [JobController::class, 'store'])->name('jobs.store');
});

Route::post('/jobs/{job:slug}/apply', [JobController::class, 'apply'])
    ->middleware(['auth', 'verified', 'role:streamer'])
    ->name('jobs.apply');

Route::get('/dashboard', DashboardController::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
