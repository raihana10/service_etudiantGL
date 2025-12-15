<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\EtudiantController;

Route::prefix('etudiant')->group(function () {
    Route::post('/verifier', [EtudiantController::class, 'verifier']);
    Route::post('/demande', [EtudiantController::class, 'creerDemande']);
    Route::post('/reclamation', [EtudiantController::class, 'creerReclamation']);
    Route::post('/verifier-attestation-reussite', [EtudiantController::class, 'verifierAttestationReussite']);
    Route::post('/rechercher-demande', [EtudiantController::class, 'rechercherDemandeParNumero']);
});
