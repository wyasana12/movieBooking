<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\UserMovieController;

Route::get('/', [UserMovieController::class, 'index'])->name('dashboard');

Route::middleware(['auth:web', 'role:user'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('film/show/{id}', [UserMovieController::class, 'show'])->name('user.film.show');
    Route::get('film/booking/{scheduleId}', [BookingController::class, 'index'])->name('user.booking.index');
    Route::get('/booking/konfirmasi/{scheduleId}', [BookingController::class, 'konfirmasi'])->name('user.booking.konfirmasi');
    Route::post('film/booking/store', [BookingController::class, 'store'])->name('user.booking.store');
});

Route::get('admin/dashboard', function () {
    return view('admin.dashboard');
});
Route::get('film', [UserMovieController::class, 'index'])->name('user.film.index');
require __DIR__ . '/auth.php';
require __DIR__.'/admin.php';
