<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

    // --------------- Register and Login ----------------//
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login'])->middleware('throttle:login');
    
    // ------------------ Get Data ----------------------//
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::post('logout', [AuthController::class, 'logOut']);
        // Route::get('get-user', 'AuthenticationController@userInfo')->name('get-user');
    });