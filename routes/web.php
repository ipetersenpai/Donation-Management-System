<?php

use App\Http\Controllers\SMTP\EmailVerificationController;
use App\Http\Controllers\SMTP\ForgotPasswordController;
use App\Http\Controllers\SMTP\ResetPasswordController;
use App\Http\Controllers\DonationCategoryController;
use App\Http\Controllers\FundAllocationController;
use App\Http\Controllers\DonationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;



Route::get('/payment-success', function (Request $request) {

    if ($request->has('payment_intent_id')) {
        return view('success_payment');
    }

    abort(404);

})->name('success_payment');

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

// Donation History Routes
Route::middleware(['auth', 'verified'])
    ->prefix('history')
    ->group(function () {
        Route::get('/', [DonationController::class, 'getDonationHistory'])->name('donation.history');
        Route::get('/search', [DonationController::class, 'searchDonations'])->name('donations.search');
        Route::post('/donation/store', [DonationController::class, 'storeDonation'])->name('donation.store');
        Route::get('/total-users', [DonationController::class, 'countUsersWhoDonated'])->name('donations.total_users');
        Route::get('/total-amount', [DonationController::class, 'totalAmountDonated'])->name('donations.total_amount');
        Route::get('/donations/export', [DonationController::class, 'exportDonations'])->name('donations.export');

    });

// Fund Allocation Routes
Route::middleware(['auth', 'verified'])
    ->prefix('fund-allocations')
    ->group(function () {
        Route::get('/', [FundAllocationController::class, 'index'])->name('fund_allocations.index');
        Route::get('/create', [FundAllocationController::class, 'create'])->name('fund_allocations.create');
        Route::post('/', [FundAllocationController::class, 'store'])->name('fund_allocations.store');
        Route::get('/{fund_allocation}/edit', [FundAllocationController::class, 'edit'])->name('fund_allocations.edit');
        Route::put('/{fund_allocation}', [FundAllocationController::class, 'update'])->name('fund_allocations.update');
        Route::delete('/{fund_allocation}', [FundAllocationController::class, 'destroy'])->name('fund_allocations.destroy');
        Route::get('/search', [FundAllocationController::class, 'search'])->name('fund_allocations.search');
        Route::get('/fund-allocations/total', [FundAllocationController::class, 'totalAllocatedAmount'])->name('fund_allocations.total');
        Route::get('/fund-allocations/balance', [FundAllocationController::class, 'remainingBalance'])->name('remaining_balance.balance');
        Route::get('/fund-allocations/export', [FundAllocationController::class, 'export'])->name('fund_allocations.export');

    });
// profile dashboard route
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [UserController::class, 'profile'])->name('profile');
    Route::post('/profile', [UserController::class, 'updateProfile'])->name('profile.update');
    Route::post('/change-password', [UserController::class, 'changePassword'])->name('change.password');
    Route::get('/total-users', [UserController::class, 'countUsers'])->name('users.total_users');
});

Route::get('/password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
