<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\AdminCarController;
use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InquiryController;
use App\Http\Controllers\StoreController;

Route::get('/', HomeController::class)->name('home');
Route::get('/store', [StoreController::class, 'index'])->name('store');
Route::get('/store/{car}', [StoreController::class, 'show'])->name('store.show');
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
    Route::put('/account/profile', [AuthController::class, 'updateProfile'])->name('account.profile.update');
    Route::put('/account/password', [AuthController::class, 'updatePassword'])->name('account.password.update');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function (): void {
    Route::get('/', AdminDashboardController::class)->name('dashboard');
    Route::resource('cars', AdminCarController::class)->except('show');
    Route::resource('categories', AdminCategoryController::class)->except('show');
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
});
