<?php

use App\Http\Controllers\SMTP\EmailVerificationController;
use App\Http\Controllers\SMTP\ForgotPasswordController;
use App\Http\Controllers\SMTP\ResetPasswordController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

Route::prefix('auth')
    ->middleware(['web'])
    ->group(function () {
        Route::get('/', function () {
            return redirect()->route('login');
        });

        Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AuthController::class, 'login']);
        Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

        // Email verification routes
        Route::get('/email/verify', function () {
            return view('smtp_templates.email_verified');
        })
            ->middleware(['auth'])
            ->name('verification.notice');

        Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])
            ->middleware(['signed'])
            ->name('verification.verify');

        Route::post('/email/verification-notification', function (\Illuminate\Http\Request $request) {
            $request->user()->sendEmailVerificationNotification();
            return back()->with('message', 'Verification link sent!');
        })
            ->middleware(['auth', 'throttle:6,1'])
            ->name('verification.send');

        // Password reset routes
        Route::get('/password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
        Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
        Route::post('/password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');
    });

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('pages/dashboard');
    })->name('dashboard');

    Route::get('/', function () {
        return redirect()->route('dashboard');
    });
});

// User management routes
Route::middleware(['auth', 'verified'])
    ->prefix('users')
    ->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('users.index');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/search', [UserController::class, 'search'])->name('users.search');
        Route::put('/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    });

use App\Http\Controllers\DonationCategoryController;

// Donation Category Routes
Route::middleware(['auth', 'verified'])
    ->prefix('categories')
    ->group(function () {
        Route::get('/', [DonationCategoryController::class, 'index'])->name('categories.index');
        Route::post('/', [DonationCategoryController::class, 'store'])->name('categories.store');
        Route::get('/search', [DonationCategoryController::class, 'search'])->name('categories.search');
        Route::put('/{category}', [DonationCategoryController::class, 'update'])->name('categories.update');
        Route::delete('/{category}', [DonationCategoryController::class, 'destroy'])->name('categories.destroy');
    });

Route::get('/password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
