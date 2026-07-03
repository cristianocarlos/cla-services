<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\DumbController;
use App\Http\Controllers\GeoController;
use App\Models\ApiToken;
use Illuminate\Support\Facades\Route;

Route::middleware(['api.token'])->group(function () {
    Route::get('/authentication/token', [AuthenticationController::class, 'token']);
    //
    Route::get('/geo/address', [GeoController::class, 'address']);
    Route::get('/geo/city', [GeoController::class, 'city']);
    Route::get('/geo/country', [GeoController::class, 'country']);
});

Route::middleware('auth:' . ApiToken::GUARD)->group(function () {
    Route::get('/dumb/stuff', [DumbController::class, 'stuff']);
});
