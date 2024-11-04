<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\DamageRequest\StoreController;
use App\Http\Controllers\User\SelfController;
use Illuminate\Support\Facades\Route;

Route::prefix('/v1')->group(function () {
    Route::prefix('/auth')->group(function () {
       Route::post('/login',    LoginController::class);
       Route::post('/logout',   LogoutController::class);
    });
    Route::prefix('/user')->middleware(['auth:sanctum','type.client'])->group(function () {
        Route::get('/',         SelfController::class);
        Route::prefix('damage_requests')->group(function () {
            Route::post('/',    StoreController::class);
        });
    });
});
