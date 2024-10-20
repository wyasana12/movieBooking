<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\SchedulesController;
use App\Http\Controllers\UserMovieController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [UserMovieController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
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
Route::get('admin/movies', [MovieController::class, 'index'])->name('admin.dashboard.film');
Route::get('admin/movies/create', [MovieController::class, 'create'])->name('admin.dashboard.createfilm');
Route::post('admin/movies/store', [MovieController::class, 'store'])->name('admin.movies.store');
Route::get('admin/movies/{film}/edit', [MovieController::class, 'edit'])->name('admin.dashboard.editfilm');
Route::put('admin/movies/edit/{film}', [MovieController::class, 'update'])->name('admin.dashboard.film.update');
Route::delete('admin/movies/{film}', [MovieController::class, 'destroy'])->name('admin.dashboard.film.destroy');

Route::get('admin/schedules', [SchedulesController::class, 'index'])->name('admin.dashboard.schedules');
Route::get('admin/schedules/create', [SchedulesController::class, 'create'])->name('admin.dashboard.createschedules');
Route::post('admin/schedules/store', [SchedulesController::class, 'store']);
Route::delete('admin/schedules/{id}', [SchedulesController::class, 'destroy']);

Route::get('film', [UserMovieController::class, 'index'])->name('user.film.index');
require __DIR__ . '/auth.php';
