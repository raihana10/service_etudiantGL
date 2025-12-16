<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Etudiant;
use App\Models\Demande;
use App\Models\ConventionStage;
use App\Models\AttestationScolarite;
use App\Models\AttestationReussite;
use App\Models\ReleveNote;
use App\Models\Reclamation;
use App\Mail\DemandeConfirmationMail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;

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

    public function verifierAttestationReussite(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'idEtudiant' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Données invalides'
            ], 422);
        }

        try {
            $etudiant = Etudiant::find($request->idEtudiant);
            
            if (!$etudiant) {
                return response()->json([
                    'success' => false,
                    'message' => 'Étudiant non trouvé'
                ], 404);
            }

            $hasDiploma = !empty($etudiant->anneeObtentionDiplome);
            
            return response()->json([
                'success' => true,
                'has_diploma' => $hasDiploma,
                'message' => $hasDiploma ? 'L\'étudiant a un diplôme' : 'L\'étudiant n\'a pas encore de diplôme'
            ]);
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la vérification de l\'attestation de réussite: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur serveur'
            ], 500);
        }
    }

    public function rechercherDemandeParNumero(Request $request)
    {
        \Log::info('Recherche de demande par numéro: ' . $request->num_demande);
        
        $validator = Validator::make($request->all(), [
            'num_demande' => 'required|string',
        ]);

        if ($validator->fails()) {
            \Log::error('Validation échouée pour recherche de demande');
            return response()->json([
                'success' => false,
                'message' => 'Numéro de demande requis'
            ], 422);
        }

        try {
            $demande = Demande::where('num_demande', $request->num_demande)->first();
            
            if (!$demande) {
                \Log::info('Demande non trouvée: ' . $request->num_demande);
                return response()->json([
                    'success' => false,
                    'message' => 'Demande non trouvée'
                ], 404);
            }

            \Log::info('Demande trouvée: ' . json_encode($demande));

            return response()->json([
                'success' => true,
                'demande' => [
                    'idDemande' => $demande->idDemande,
                    'num_demande' => $demande->num_demande,
                    'typeDoc' => $demande->typeDoc,
                    'statut' => $demande->statut,
                    'datesoumission' => $demande->datesoumission->format('Y-m-d H:i:s'),
                    'motif_refus' => $demande->motif_refus,
                    'date_traitement' => $demande->date_traitement ? $demande->date_traitement->format('Y-m-d H:i:s') : null
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la recherche de demande: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur serveur'
            ], 500);
        }
    }

    public function creerDemande(Request $request)
    {
        \Log::info('Création de demande - Données reçues: ' . json_encode($request->all()));
        
        $validator = Validator::make($request->all(), [
            'idEtudiant' => 'required|integer|exists:etudiant,idEtudiant',
            'typeDoc' => 'required|in:AttestationScolarite,AttestationReussite,ReleveNote,ConventionStage',
            'informations' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            \Log::error('Validation échouée: ' . json_encode($validator->errors()));
            return response()->json([
                'success' => false,
                'message' => 'Données invalides',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            \Log::info('Tentative de création de demande avec: ' . json_encode([
                'idEtudiant' => $request->idEtudiant,
                'typeDoc' => $request->typeDoc,
                'informations' => $request->informations
            ]));
            
            // Si c'est une attestation de réussite, vérifier l'année d'obtention AVANT de créer la demande
            if ($request->typeDoc === 'AttestationReussite') {
                \Log::info('Vérification pour attestation de réussite pour l\'étudiant ID: ' . $request->idEtudiant);
                
                $etudiant = Etudiant::find($request->idEtudiant);
                
                if (!$etudiant || empty($etudiant->anneeObtentionDiplome)) {
                    \Log::info('L\'étudiant n\'a pas encore d\'année d\'obtention de diplôme');
                    return response()->json([
                        'success' => false,
                        'message' => 'Vous n\'avez pas encore obtenu votre diplôme',
                        'has_diploma' => false
                    ], 422);
                }
                
                \Log::info('L\'étudiant a une année d\'obtention: ' . $etudiant->anneeObtentionDiplome);
            }

            $demande = Demande::create([
                'idEtudiant' => $request->idEtudiant,
                'typeDoc' => $request->typeDoc,
                'statut' => 'En attente',
                'datesoumission' => now(),
                'num_demande' => 'DEM-' . date('Y') . '-' . str_pad(Demande::max('idDemande') + 1, 6, '0', STR_PAD_LEFT)
            ]);

            \Log::info('Demande créée avec succès: ' . json_encode($demande));
            
            // Générer un numéro de demande automatique
            $numDemande = $demande->num_demande;
            \Log::info('Numéro de demande généré: ' . $numDemande);

            // Si c'est une convention de stage, créer l'enregistrement correspondant
            if ($request->typeDoc === 'ConventionStage' && $request->informations) {
                \Log::info('Création de convention de stage pour la demande ID: ' . $demande->idDemande);
                
                $conventionStage = ConventionStage::create([
                    'idDemande' => $demande->idDemande,
                    'raisonSocialeEntreprise' => $request->informations['cosocialreason'] ?? null,
                    'secteurEntreprise' => $request->informations['sector'] ?? null,
                    'TLEntreprise' => $request->informations['cophone'] ?? null,
                    'emailEntreprise' => $request->informations['comail'] ?? null,
                    'adresseEntreprise' => $request->informations['coaddress'] ?? null,
                    'villeEntreprise' => $request->informations['city'] ?? null,
                    'representantEntreprise' => $request->informations['corepresentative'] ?? null,
                    'fctRepresentant' => $request->informations['corepresentativefunction'] ?? null,
                    'encadrantEntreprise' => $request->informations['cosupervisor'] ?? null,
                    'fctEncadrant' => $request->informations['cosupervisorfunction'] ?? null,
                    'TLEncadrant' => $request->informations['cosupervisorphone'] ?? null,
                    'emailEncadrant' => $request->informations['cosupervisormail'] ?? null,
                    'encadrantAcademique' => $request->informations['ensasupervisor'] ?? null,
                    'typeStage' => 'Convention de stage', // Valeur par défaut
                    'dateDebut' => $request->informations['internship_start_date'] ?? null,
                    'dateFin' => $request->informations['internship_end_date'] ?? null,
                    'sujetStage' => $request->informations['internship_subject'] ?? null
                ]);

                \Log::info('Convention de stage créée avec succès: ' . json_encode($conventionStage));
            }

            // Si c'est une attestation de scolarité, créer l'enregistrement correspondant automatiquement
            if ($request->typeDoc === 'AttestationScolarite') {
                \Log::info('Création automatique d\'attestation de scolarité pour la demande ID: ' . $demande->idDemande);
                
                $attestationScolarite = AttestationScolarite::create([
                    'idDemande' => $demande->idDemande
                ]);

                \Log::info('Attestation de scolarité créée avec succès: ' . json_encode($attestationScolarite));
            }

            // Si c'est une attestation de réussite, créer l'enregistrement correspondant
            if ($request->typeDoc === 'AttestationReussite') {
                \Log::info('Création d\'attestation de réussite pour la demande ID: ' . $demande->idDemande);
                
                $etudiant = Etudiant::find($request->idEtudiant);
                
                $attestationReussite = AttestationReussite::create([
                    'idDemande' => $demande->idDemande,
                    'anneeObtention' => $etudiant->anneeObtentionDiplome,
                    'diplomeConcernee' => 'Diplôme d\'Ingénieur ENSA Tétouan'
                ]);

                \Log::info('Attestation de réussite créée avec succès: ' . json_encode($attestationReussite));
            }

            // Si c'est un relevé de notes, créer l'enregistrement correspondant
            if ($request->typeDoc === 'ReleveNote' && $request->informations) {
                \Log::info('Création de relevé de notes pour la demande ID: ' . $demande->idDemande);
                
                $releveNote = ReleveNote::create([
                    'idDemande' => $demande->idDemande,
                    'annee' => $request->informations['annee_universitaire'] ?? null,
                    'semestre' => $request->informations['semestre'] ?? null
                ]);

                \Log::info('Relevé de notes créé avec succès: ' . json_encode($releveNote));
            }

            // Récupérer l'étudiant pour l'email
            $etudiant = Etudiant::find($request->idEtudiant);
            
            // Envoyer l'email de confirmation simple avec numéro de demande
            try {
                Mail::to($etudiant->emailInstitu)->send(new DemandeConfirmationMail($demande, $etudiant));
                \Log::info('Email de confirmation envoyé à l\'étudiant: ' . $etudiant->emailInstitu);
            } catch (\Exception $e) {
                \Log::error('Erreur lors de l\'envoi de l\'email: ' . $e->getMessage());
                // Ne pas bloquer la création de demande si l'email échoue
            }

            return response()->json([
                'success' => true,
                'message' => 'Demande créée avec succès',
                'demande' => [
                    'idDemande' => $demande->idDemande,
                    'numDemande' => $numDemande,
                    'typeDoc' => $demande->typeDoc,
                    'statut' => $demande->statut,
                    'datesoumission' => $demande->datesoumission->format('Y-m-d H:i:s')
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la création de la demande: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création de la demande',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function creerReclamation(Request $request)
    {
        \Log::info('Création de réclamation - Données reçues: ' . json_encode($request->all()));
        
        $validator = Validator::make($request->all(), [
            'idEtudiant' => 'required|integer|exists:etudiant,idEtudiant',
            'sujet' => 'required|string|max:200',
            'description' => 'required|string|max:2000'
        ]);

        if ($validator->fails()) {
            \Log::error('Validation échouée: ' . json_encode($validator->errors()));
            return response()->json([
                'success' => false,
                'message' => 'Données invalides',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            \Log::info('Tentative de création de réclamation avec: ' . json_encode([
                'idEtudiant' => $request->idEtudiant,
                'sujet' => $request->sujet,
                'description' => $request->description
            ]));
            
            $reclamation = Reclamation::create([
                'idEtudiant' => $request->idEtudiant,
                'sujet' => $request->sujet,
                'description' => $request->description,
                'statut' => 'Nouvelle',
                'datesoumission' => now()
            ]);

            \Log::info('Réclamation créée avec succès: ' . json_encode($reclamation));

            return response()->json([
                'success' => true,
                'message' => 'Réclamation créée avec succès',
                'reclamation' => [
                    'idReclamation' => $reclamation->idReclamation,
                    'sujet' => $reclamation->sujet,
                    'statut' => $reclamation->statut,
                    'datesoumission' => $reclamation->datesoumission->format('Y-m-d H:i:s')
                ]
            ], 201);
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la création de réclamation: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création de la réclamation',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
