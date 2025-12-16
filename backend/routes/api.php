<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TestController;
use App\Http\Controllers\Api\Auth\AdminLoginController;
use App\Http\Controllers\Api\Admin\DashboardController;
use App\Http\Controllers\Api\Admin\HistoriqueController;
use App\Http\Controllers\Api\Admin\DemandesController;

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

// Demandes management routes
Route::prefix('admin/demandes')->group(function () {
    Route::get('/', [DemandesController::class, 'index']);
    Route::get('/{idDemande}/preview', [DemandesController::class, 'preview']);
    Route::post('/{idDemande}/valider', [DemandesController::class, 'valider']);
    Route::post('/{idDemande}/refuser', [DemandesController::class, 'refuser']);
});
