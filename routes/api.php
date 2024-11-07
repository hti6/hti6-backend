<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\DamageRequest\IndexController as DamageRequestIndex;
use App\Http\Controllers\DamageRequest\StoreController as DamageRequestStore;
use App\Http\Controllers\DamageRequest\GetController as DamageRequestGet;
use App\Http\Controllers\Camera\IndexController as CameraIndex;
use App\Http\Controllers\Camera\StoreController as CameraStore;
use App\Http\Controllers\Camera\GetController as CameraGet;
use App\Http\Controllers\Category\IndexController as CategoryIndex;
use App\Http\Controllers\User\IndexController as UserIndex;
use App\Http\Controllers\User\StoreController as UserStore;
use App\Http\Controllers\User\UpdateController as UserUpdate;
use App\Http\Controllers\User\DeleteController as UserDelete;
use App\Http\Controllers\Map\MapController;
use App\Http\Controllers\User\NotificationsController;
use App\Http\Controllers\User\SelfController;
use Illuminate\Support\Facades\Route;

Route::prefix('/v1')->group(function () {
    Route::prefix('/auth')->group(function () {
       Route::post('/login',    LoginController::class);
       Route::post('/logout',   LogoutController::class);
    });
    Route::prefix('/user')->middleware(['auth:sanctum','type.client'])->group(function () {
        Route::get('/',         SelfController::class);
        Route::get('/notifications', NotificationsController::class);
        Route::put('/');
        Route::get('/categories',CategoryIndex::class);
        Route::prefix('/damage_requests')->group(function () {
            Route::post('/',    DamageRequestStore::class);
            Route::get('/',    DamageRequestIndex::class);
            Route::get('/{id}', DamageRequestGet::class);
        });
        Route::prefix('/cameras')->group(function () {
            Route::post('/', CameraStore::class);
            Route::get('/', CameraIndex::class);
            Route::get('/{id}', CameraGet::class);
        });
        Route::get('/map', MapController::class);
    });
    Route::prefix('/admin')->middleware(['auth:sanctum','type.admin'])->group(function () {
        Route::get('/', UserIndex::class);
        Route::put('/{id}', UserUpdate::class);
        Route::delete('/{id}',UserDelete::class);
        Route::post('/', UserStore::class);
    });
});
