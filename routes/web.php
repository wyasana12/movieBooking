<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\SchedulesController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('admin/dashboard', function() {
    return view('admin.dashboard');
});
Route::get('admin/movies', [MovieController::class, 'index'])->name('admin.dashboard.film');
Route::get('admin/movies/create', [MovieController::class, 'create'])->name('admin.dashboard.createfilm');
Route::post('admin/movies/store', [MovieController::class, 'store'])->name('admin.movies.store');
Route::get('admin/movies/edit', [MovieController::class, 'edit'])->name('admin.dashboard.editfilm');
Route::post('admin/movies/update', [MovieController::class, 'update']);
Route::delete('admin/movies/{id}',[MovieController::class, 'destroy']);

Route::get('admin/schedules', [SchedulesController::class, 'index'])->name('admin.dashboard.schedules');
Route::get('admin/schedules/create', [SchedulesController::class, 'create'])->name('admin.dashboard.createschedules');
Route::post('admin/schedules/store', [SchedulesController::class, 'store']);
Route::get('admin/schedules/edit', [SchedulesController::class, 'edit'])->name('admin.dashboard.editschedules');
Route::post('admin/schedules/update', [SchedulesController::class, 'update']);
Route::delete('admin/schedules/{id}', [SchedulesController::class, 'destroy']);

Route::get('booking/create', [BookingController::class, 'create'])->name('booking.create');
Route::post('booking/store', [BookingController::class, 'store']);
Route::delete('booking/destroy/{id}', [BookingController::class, 'destroy']);
require __DIR__.'/auth.php';
