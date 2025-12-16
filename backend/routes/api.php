<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TestController;
use App\Http\Controllers\Api\Auth\AdminLoginController;
use App\Http\Controllers\Api\Admin\DashboardController;
use App\Http\Controllers\Api\Admin\HistoriqueController;
use App\Http\Controllers\Api\Admin\ReclamationController;
use App\Http\Controllers\Api\Admin\TestEmailController;

Route::get('/test', [TestController::class, 'testConnection']);
// Dashboard routes
Route::post('/admin/login', [AdminLoginController::class, 'login']);
Route::get('/admin/profile', [AdminLoginController::class, 'profile']);
Route::post('/admin/logout', [AdminLoginController::class, 'logout']);

// Dashboard routes
Route::prefix('admin/dashboard')->group(function () {
    Route::get('/statistics', [DashboardController::class, 'getStatistics']);
    Route::get('/demandes', [DashboardController::class, 'getDemandes']);
    Route::get('/detailed-stats', [DashboardController::class, 'getDetailedStats']);
});

// Historique routes
Route::prefix('admin/historique')->group(function () {
    Route::get('/', [HistoriqueController::class, 'getHistorique']);
    Route::get('/stats', [HistoriqueController::class, 'getHistoriqueStats']);
    Route::get('/{idDemande}', [HistoriqueController::class, 'getDemandeDetails']);
});

// Réclamations routes
Route::prefix('admin/reclamations')->group(function () {
    Route::get('/', [ReclamationController::class, 'index']);
    Route::get('/statistics', [ReclamationController::class, 'getStatistics']);
    Route::get('/{id}', [ReclamationController::class, 'show']);
    Route::put('/{id}/status', [ReclamationController::class, 'updateStatus']);
    Route::post('/{id}/respond', [ReclamationController::class, 'respond']);
});

// Test email route
Route::get('/admin/test-email', [TestEmailController::class, 'sendTestEmail']);
