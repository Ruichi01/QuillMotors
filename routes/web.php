<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\InquiryController;

Route::view('/', 'welcome')->name('home');
Route::post('/inquiries', [InquiryController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('inquiries.store');

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])
        ->middleware('throttle:5,1')
        ->name('login.store');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])
        ->middleware('throttle:5,1')
        ->name('register.store');
});

Route::middleware('auth')->group(function (): void {
    Route::get('/account', [AuthController::class, 'account'])->name('account');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
