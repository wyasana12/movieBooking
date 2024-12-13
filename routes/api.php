<?php

use App\Http\Controllers\Api\BookingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MovieController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::resource('/movies', MovieController::class);
Route::get('/booking/list', [BookingController::class, 'list']);
Route::resource('/booking', BookingController::class);
