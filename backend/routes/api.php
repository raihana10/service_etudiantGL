<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\EtudiantController;

use App\Http\Controllers\Api\Auth\AdminLoginController;
use App\Http\Controllers\Api\Admin\DashboardController;
use App\Http\Controllers\Api\Admin\HistoriqueController;
use App\Http\Controllers\Api\Admin\DemandesController;
use App\Http\Controllers\Api\Admin\ReclamationController;




// Étudiant routes
Route::prefix('etudiant')->group(function () {
    Route::post('/verifier', [EtudiantController::class, 'verifier']);
    Route::post('/demande', [EtudiantController::class, 'creerDemande']);
    Route::post('/reclamation', [EtudiantController::class, 'creerReclamation']);
    Route::post('/verifier-attestation-reussite', [EtudiantController::class, 'verifierAttestationReussite']);
    Route::post('/rechercher-demande', [EtudiantController::class, 'rechercherDemandeParNumero']);
});

// Admin authentication routes
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
    Route::get('/{num_demande}/preview', [DemandesController::class, 'preview']);
    Route::get('/{num_demande}/download', [DemandesController::class, 'download']);
    Route::post('/{num_demande}/valider', [DemandesController::class, 'valider']);
    Route::post('/{num_demande}/refuser', [DemandesController::class, 'refuser']);
});

// Réclamations routes
Route::prefix('admin/reclamations')->group(function () {
    Route::get('/', [ReclamationController::class, 'index']);
    Route::get('/statistics', [ReclamationController::class, 'getStatistics']);
    Route::get('/{id}', [ReclamationController::class, 'show']);
    Route::put('/{id}/status', [ReclamationController::class, 'updateStatus']);
    Route::post('/{id}/respond', [ReclamationController::class, 'respond']);
});


