<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TestController;
use App\Http\Controllers\Api\Auth\AdminLoginController;
use App\Http\Controllers\Api\Admin\DashboardController;

Route::get('/test', [TestController::class, 'testConnection']);
// Dashboard routes
Route::post('/admin/login', [AdminLoginController::class, 'login']);
Route::get('/admin/profile', [AdminLoginController::class, 'profile']);
Route::post('/admin/logout', [AdminLoginController::class, 'logout']);
Route::prefix('admin/dashboard')->group(function () {
    Route::get('/statistics', [DashboardController::class, 'getStatistics']);
    Route::get('/demandes', [DashboardController::class, 'getDemandes']);
    Route::get('/detailed-stats', [DashboardController::class, 'getDetailedStats']);
});
