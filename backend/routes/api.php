<?php

use App\Http\Controllers\Api\InquiryController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AdminInquiryController;
use App\Http\Controllers\Api\SiteSettingController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::post('/contact', [InquiryController::class, 'store']);
Route::post('/admin/login', [AuthController::class, 'login']);
Route::get('/site-settings', [SiteSettingController::class, 'index']);

// Protected admin routes
Route::middleware('auth.admin')->group(function () {
    Route::post('/admin/logout', [AuthController::class, 'logout']);
    Route::get('/admin/stats', [AdminInquiryController::class, 'stats']);
    Route::get('/admin/inquiries', [AdminInquiryController::class, 'index']);
    Route::put('/admin/inquiries/{id}', [AdminInquiryController::class, 'updateStatus']);
    Route::delete('/admin/inquiries/{id}', [AdminInquiryController::class, 'destroy']);
    
    // Site Settings Administration
    Route::get('/admin/site-settings/{key}', [SiteSettingController::class, 'show']);
    Route::put('/admin/site-settings/{key}', [SiteSettingController::class, 'update']);
});
