<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DonationCategoryController;
use App\Http\Controllers\Api\DonateController;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'getUsersDetails']);
    Route::get('/donation-categories', [DonationCategoryController::class, 'index']);
    Route::post('/donate', [DonateController::class, 'store']);
    Route::get('/user/donations', [DonateController::class, 'getUserDonations']);
    Route::put('/user', [AuthController::class, 'updateUserDetails']);
    Route::put('/user/password', [AuthController::class, 'updatePassword']);
    Route::post('/logout', [AuthController::class, 'logout']);
});
