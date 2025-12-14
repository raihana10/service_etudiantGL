<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Etudiant;
use App\Models\Demande;
use Illuminate\Support\Facades\Validator;

class EtudiantController extends Controller
{
    public function verifier(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'numApogee' => 'required|string',
            'cin' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Données invalides',
                'errors' => $validator->errors()
            ], 422);
        }

        $etudiant = Etudiant::where('numApogee', $request->numApogee)
            ->where('emailInstitu', $request->email)
            ->where('CIN', $request->cin)
            ->first();

        if (!$etudiant) {
            return response()->json([
                'success' => false,
                'message' => 'Informations incorrectes. Vérifiez vos données.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Étudiant vérifié avec succès',
            'etudiant' => [
                'idEtudiant' => $etudiant->idEtudiant
            ]
        ]);
    }

    public function creerDemande(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'idEtudiant' => 'required|integer|exists:etudiant,idEtudiant',
            'typeDoc' => 'required|in:AttestationScolarite,AttestationReussite,ReleveNote,ConventionStage,Reclamation',
            'informations' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Données invalides',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $demande = Demande::create([
                'idEtudiant' => $request->idEtudiant,
                'typeDoc' => $request->typeDoc,
                'statut' => 'En attente',
                'datesoumission' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Demande créée avec succès',
                'demande' => [
                    'idDemande' => $demande->idDemande,
                    'typeDoc' => $demande->typeDoc,
                    'statut' => $demande->statut,
                    'datesoumission' => $demande->datesoumission
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création de la demande',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
