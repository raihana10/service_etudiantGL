<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\EtudiantController;

Route::prefix('etudiant')->group(function () {
    Route::post('/verifier', [EtudiantController::class, 'verifier']);
    Route::post('/demande', [EtudiantController::class, 'creerDemande']);
});
