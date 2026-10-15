<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InquiryController;

Route::view('/', 'welcome')->name('home');
Route::post('/inquiries', [InquiryController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('inquiries.store');
