<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Demande;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;

class DemandesController extends Controller
{
    /**
     * Obtenir toutes les demandes avec filtres et pagination
     */
    public function index(Request $request)
    {
        try {
            $query = Demande::with(['etudiant', 'administrateur']);

            // Filtrage par statut
            if ($request->has('statut') && $request->statut !== 'tous') {
                $statutMapping = [
                    'en_attente' => 'En attente',
                    'acceptee' => 'Validée',
                    'refusee' => 'Refusée'
                ];
                $query->where('statut', $statutMapping[$request->statut] ?? $request->statut);
            }

            // Filtrage par type de document
            if ($request->has('typeDoc') && $request->typeDoc !== 'toutes') {
                $typeMapping = [
                    'attestation_scolarite' => 'AttestationScolarite',
                    'attestation_reussite' => 'AttestationReussite',
                    'releve_notes' => 'ReleveNote',
                    'convention_stage' => 'ConventionStage'
                ];
                $query->where('typeDoc', $typeMapping[$request->typeDoc] ?? $request->typeDoc);
            }

            // Recherche par nom, prénom ou numéro apogée
            if ($request->has('search') && $request->search) {
                $search = $request->search;
                $query->whereHas('etudiant', function ($q) use ($search) {
                    $q->where('nom', 'like', "%{$search}%")
                      ->orWhere('prenom', 'like', "%{$search}%")
                      ->orWhere('numApogee', 'like', "%{$search}%");
                });
            }

            // Pagination
            $page = $request->get('page', 1);
            $limit = $request->get('limit', 20);

            $demandes = $query->orderBy('datesoumission', 'desc')
                ->skip(($page - 1) * $limit)
                ->take($limit)
                ->get()
                ->map(function ($demande) {
                    // Récupérer les détails spécifiques selon le type
                    $details = $this->getDemandeDetails($demande);

                    return [
                        'id' => $demande->num_demande,
                        'idDemande' => $demande->idDemande,
                        'num_demande' => $demande->num_demande,
                        'type' => $this->mapTypeToFrontend($demande->typeDoc),
                        'typeLabel' => $this->getTypeLabel($demande->typeDoc),
                        'statut' => $this->mapStatutToFrontend($demande->statut),
                        'etudiant' => $demande->etudiant ? [
                            'nom' => $demande->etudiant->nom . ' ' . $demande->etudiant->prenom,
                            'apogee' => $demande->etudiant->numApogee,
                            'email' => $demande->etudiant->emailInstitu,
                        ] : null,
                        'date' => $demande->datesoumission->format('d/m/Y'),
                        'details' => $details,
                    ];
                });

            $total = $query->count();

            return response()->json([
                'success' => true,
                'data' => [
                    'demandes' => $demandes,
                    'pagination' => [
                        'page' => $page,
                        'limit' => $limit,
                        'total' => $total,
                        'pages' => ceil($total / $limit)
                    ]
                ]
            ])
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
            ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With');

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des demandes: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Valider une demande
     */
    public function valider(Request $request, $num_demande)
    {
        DB::beginTransaction();
        try {
            Log::info("Début validation demande #{$num_demande}");
            
            $demande = Demande::with(['etudiant.filiere', 'attestationscolarite', 'attestationreussite', 'relevenote', 'conventionstage'])
                ->where('num_demande', $num_demande)
                ->firstOrFail();

            if ($demande->statut !== 'En attente') {
                return response()->json([
                    'success' => false,
                    'message' => 'Cette demande ne peut pas être validée. Statut actuel: ' . $demande->statut
                ], 400);
            }

            // Préparer données PDF (notes pour Relevé ou Attestation Réussite)
            $notes = [];
            if (($demande->typeDoc === 'ReleveNote' && $demande->relevenote) || 
                ($demande->typeDoc === 'AttestationReussite' && $demande->attestationreussite)) {
                
                $annee = ($demande->typeDoc === 'ReleveNote') 
                    ? $demande->relevenote->annee 
                    : $demande->attestationreussite->anneeObtention;
                    
                $idEtudiant = $demande->idEtudiant;
                
                // Extraire l'année (4 chiffres) pour la recherche
                $searchYear = $annee;
                if (preg_match('/(\d{4})/', $annee, $matches)) {
                    $searchYear = $matches[1];
                }

                $notes = DB::table('concerne as c')
                    ->join('contient as ct', 'ct.idContient', '=', 'c.idContient')
                    ->join('module as m', 'm.idM', '=', 'ct.idM')
                    ->where('c.idEtudiant', $idEtudiant)
                    ->where('c.annee', 'like', '%' . $searchYear . '%')
                    ->select('m.code', 'm.nomM as module', 'c.note')
                    ->orderBy('m.code')
                    ->get()
                    ->map(function ($row) {
                        $note = is_null($row->note) ? null : floatval($row->note);
                        return [
                            'code' => $row->code ?? 'N/A',
                            'module' => $row->module ?? 'N/A',
                            'note' => $note,
                            'resultat' => is_null($note) ? 'N/A' : ($note >= 10 ? 'Validé' : 'Non validé'),
                        ];
                    })->toArray();
            }

            $data = [
                'demande'    => $demande,
                'etudiant'   => $demande->etudiant,
                'scolarite'  => $demande->attestationscolarite,
                'reussite'   => $demande->attestationreussite,
                'releve'     => $demande->relevenote,
                'convention' => $demande->conventionstage,
                'notes'      => $notes,
                'now'        => now(),
            ];

            // Générer le PDF
            $pdfContent = null;
            try {
                Log::info("Génération du PDF pour demande #{$num_demande}");
                $pdf = Pdf::loadView('pdf.demande', $data)->setPaper('a4');
                $pdfContent = $pdf->output();
            } catch (\Exception $pdfError) {
                Log::error("Erreur génération PDF: " . $pdfError->getMessage());
                // On continue sans PDF, ou on lève une exception selon le besoin. 
                // Pour l'instant on log juste, mais l'envoi d'email échouera s'il n'y a pas de contenu.
                // Mieux vaut peut-être échouer ici si le PDF est requis.
                throw new \Exception("Erreur lors de la génération du PDF: " . $pdfError->getMessage());
            }
            
            $typeLabel = $this->getTypeLabel($demande->typeDoc);

            // Mettre à jour la demande
            Log::info("Mise à jour du statut pour demande #{$num_demande}");
            $demande->update([
                'statut' => 'Validée',
                'date_traitement' => now(),
                'idAdmin' => null, // Utiliser null au lieu de 1 pour éviter l'erreur de contrainte FK
                'motif_refus' => null,
            ]);

            DB::commit();
            Log::info("Demande #{$num_demande} validée avec succès");

            // Envoyer l'email avec la pièce jointe PDF
            $email = optional($demande->etudiant)->emailInstitu;
            if ($email) {
                try {
                    // Créer un nom de fichier significatif
                    $etudiantNom = $demande->etudiant ? ($demande->etudiant->nom . '_' . $demande->etudiant->prenom) : 'etudiant';
                    $numDemande = $demande->num_demande;

                    // Nettoyer les parties du nom de fichier
                    $safeType = preg_replace('/[^A-Za-z0-9_\-]/u', '', str_replace(' ', '_', $typeLabel));
                    $safeNom = preg_replace('/[^A-Za-z0-9\-_]/', '', $etudiantNom);
                    
                    $fileName = $safeType . '_' . $safeNom . '_' . $numDemande . '_' . now()->format('Y-m-d') . '.pdf';

                    Log::info("Envoi email à {$email} pour demande #{$num_demande} avec pièce jointe {$fileName}");
                    $emailHtml = $this->renderEmailValidee($demande, $typeLabel, $fileName);
                    Mail::send([], [], function ($message) use ($email, $typeLabel, $emailHtml, $pdfContent, $fileName) {
                        $message->to($email)
                            ->subject('Votre ' . $typeLabel . ' a été validée')
                            ->html($emailHtml)
                            ->attachData($pdfContent, $fileName, ['mime' => 'application/pdf']);
                    });
                    Log::info("Email envoyé avec succès à {$email}");
                } catch (\Exception $mailError) {
                    Log::error("Erreur envoi email: " . $mailError->getMessage());
                    // Continue même si l'email échoue
                }
            } else {
                Log::warning("Pas d'email pour l'étudiant de la demande #{$num_demande}");
            }

            return response()->json([
                'success' => true,
                'message' => 'Demande validée avec succès' . ($email ? ' et email envoyé' : '')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Erreur validation demande #{$num_demande}: " . $e->getMessage());
            Log::error($e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la validation: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Refuser une demande
     */
    public function refuser(Request $request, $num_demande)
    {
        DB::beginTransaction();
        try {
            Log::info("Début refus demande #{$num_demande}");
            
            $request->validate([
                'motif_refus' => 'required|string|max:1000'
            ]);

            $demande = Demande::with('etudiant')
                ->where('num_demande', $num_demande)
                ->firstOrFail();

            if ($demande->statut !== 'En attente') {
                return response()->json([
                    'success' => false,
                    'message' => 'Cette demande ne peut pas être refusée. Statut actuel: ' . $demande->statut
                ], 400);
            }

            Log::info("Mise à jour du statut pour demande #{$num_demande}");
            $demande->update([
                'statut' => 'Refusée',
                'motif_refus' => $request->motif_refus,
                'date_traitement' => now(),
                'idAdmin' => null // Utiliser null au lieu de 1
            ]);

            DB::commit();
            Log::info("Demande #{$num_demande} refusée avec succès");

            $typeLabel = $this->getTypeLabel($demande->typeDoc);
            $email = optional($demande->etudiant)->emailInstitu;
            if ($email) {
                try {
                    Log::info("Envoi email de refus à {$email} pour demande #{$num_demande}");
                    $emailHtml = $this->renderEmailRefusee($demande, $typeLabel);
                    Mail::send([], [], function ($message) use ($email, $typeLabel, $emailHtml) {
                        $message->to($email)
                            ->subject('Votre ' . $typeLabel . ' a été refusée')
                            ->html($emailHtml);
                    });
                    Log::info("Email de refus envoyé avec succès à {$email}");
                } catch (\Exception $mailError) {
                    Log::error("Erreur envoi email de refus: " . $mailError->getMessage());
                    // Continue même si l'email échoue
                }
            } else {
                Log::warning("Pas d'email pour l'étudiant de la demande #{$num_demande}");
            }

            return response()->json([
                'success' => true,
                'message' => 'Demande refusée avec succès' . ($email ? ' et email envoyé' : '')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Erreur refus demande #{$num_demande}: " . $e->getMessage());
            Log::error($e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du refus: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtenir les détails spécifiques d'une demande
     */
   private function getDemandeDetails($demande)
{
    
    switch ($demande->typeDoc) {
        case 'AttestationScolarite':
            // Charger les détails avec la relation
            $detail = $demande->attestationscolarite()->first();
            return $detail ? [
                'filiere' => $demande->etudiant->filiere->nomF ?? 'N/A',
                
            ] : [];

        case 'AttestationReussite':
            $detail = $demande->attestationreussite()->first();
            return $detail ? [
                'filiere' => $demande->etudiant->filiere->nomF ?? 'N/A',
                'anneeUniversitaire' => $detail->anneeObtention ?? 'N/A',
                'diplome' => $detail->diplomeConcernee ?? 'N/A',
            ] : [];

        case 'ReleveNote':
            $detail = $demande->relevenote()->first();
            return $detail ? [
    
                'anneeUniversitaire' => $detail->annee ?? 'N/A',
            ] : [];

        case 'ConventionStage':
            $detail = $demande->conventionstage()->first();
            return $detail ? [
                'entreprise' => $detail->raisonSocialeEntreprise ?? 'N/A',
                'ville' => $detail->villeEntreprise ?? 'N/A',
                'periode' => ($detail->dateDebut && $detail->dateFin) ?
                    $detail->dateDebut->format('d/m/Y') . ' → ' . $detail->dateFin->format('d/m/Y') : 'N/A',
                'sujet' => $detail->sujetStage ?? 'N/A',
                'encadrant_entreprise' => $detail->encadrantEntreprise ?? 'N/A',
            ] : [];

        default:
            return [];
    }
}

    /**
     * Mapper le type backend vers frontend
     */
    private function mapTypeToFrontend($type)
    {
        $mapping = [
            'AttestationScolarite' => 'attestation_scolarite',
            'AttestationReussite' => 'attestation_reussite',
            'ReleveNote' => 'releve_notes',
            'ConventionStage' => 'convention_stage'
        ];
        return $mapping[$type] ?? $type;
    }

    /**
     * Obtenir le label du type
     */
    private function getTypeLabel($type)
    {
        $labels = [
            'AttestationScolarite' => 'Attestation de scolarité',
            'AttestationReussite' => 'Attestation de réussite',
            'ReleveNote' => 'Relevé de notes',
            'ConventionStage' => 'Convention de stage'
        ];
        return $labels[$type] ?? $type;
    }

    /**
     * Mapper le statut backend vers frontend
     */
    private function mapStatutToFrontend($statut)
    {
        $mapping = [
            'En attente' => 'en_attente',
            'Validée' => 'acceptee',
            'Refusée' => 'refusee'
        ];
        return $mapping[$statut] ?? $statut;
    }

    /**
     * Rendu HTML stylisé pour l'email de validation
     */
   private function renderEmailValidee($demande, $typeLabel, $fileName)
{
    $type = e($typeLabel);
    $num = e($demande->num_demande);
    $etudiantNom = $demande->etudiant ? e($demande->etudiant->prenom . ' ' . $demande->etudiant->nom) : 'Étudiant';

    return <<<HTML
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Validation de la demande</title>
</head>
<body style="margin:0;padding:40px 20px;background:#f5f5f5;font-family:Arial,sans-serif;">
  <div style="max-width:600px;margin:0 auto;background:#ffffff;border:1px solid #e0e0e0;border-radius:8px;">
    
    <!-- Header -->
    <div style="padding:30px;background:#4caf50;text-align:center;border-radius:8px 8px 0 0;">
      <h1 style="margin:0;color:#ffffff;font-size:24px;font-weight:normal;">
        ✓ Demande Validée
      </h1>
    </div>
    
    <!-- Corps -->
    <div style="padding:30px;color:#333333;line-height:1.6;">
      <p style="margin:0 0 20px;font-size:16px;">
        Bonjour <strong>{$etudiantNom}</strong>,
      </p>
      
      <p style="margin:0 0 20px;font-size:15px;">
        Votre demande de <strong>{$type}</strong> a été validée avec succès.
      </p>
      
      <!-- Informations -->
      <div style="margin:25px 0;padding:20px;background:#f9f9f9;border-left:4px solid #4caf50;border-radius:4px;">
        <p style="margin:0 0 10px;font-size:14px;">
          <strong>Type de document :</strong> {$type}
        </p>
        <p style="margin:0 0 10px;font-size:14px;">
          <strong>Numéro de demande :</strong> {$num}
        </p>
        <p style="margin:0;font-size:14px;">
          <strong>Fichier joint :</strong> {$fileName}
        </p>
      </div>
      
      <div style="margin:25px 0;padding:15px;background:#e8f5e9;border-radius:4px;">
        <p style="margin:0;font-size:14px;color:#2e7d32;">
          📎 Le document PDF est joint à cet email.
        </p>
      </div>
      
      <p style="margin:20px 0 0;font-size:14px;color:#666666;">
        Conservez ce numéro pour tout suivi auprès du service de scolarité.
      </p>
    </div>
    
    <!-- Footer -->
    <div style="padding:20px 30px;background:#f9f9f9;border-top:1px solid #e0e0e0;border-radius:0 0 8px 8px;">
      <p style="margin:0;font-size:13px;color:#666666;text-align:center;">
        École Nationale des Sciences Appliquées de Tétouan<br>
        <span style="font-size:12px;color:#999999;">Cet email est envoyé automatiquement, merci de ne pas y répondre.</span>
      </p>
    </div>
  </div>
</body>
</html>
HTML;
}

/**
 * Rendu HTML simple pour l'email de refus
 */
private function renderEmailRefusee($demande, $typeLabel)
{
    $type = e($typeLabel);
    $num = e($demande->num_demande);
    $etudiantNom = $demande->etudiant ? e($demande->etudiant->prenom . ' ' . $demande->etudiant->nom) : 'Étudiant';
    $motif = e($demande->motif_refus ?? 'Non spécifié');

    return <<<HTML
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Refus de la demande</title>
</head>
<body style="margin:0;padding:40px 20px;background:#f5f5f5;font-family:Arial,sans-serif;">
  <div style="max-width:600px;margin:0 auto;background:#ffffff;border:1px solid #e0e0e0;border-radius:8px;">
    
    <!-- Header -->
    <div style="padding:30px;background:#f44336;text-align:center;border-radius:8px 8px 0 0;">
      <h1 style="margin:0;color:#ffffff;font-size:24px;font-weight:normal;">
        ✕ Demande Refusée
      </h1>
    </div>
    
    <!-- Corps -->
    <div style="padding:30px;color:#333333;line-height:1.6;">
      <p style="margin:0 0 20px;font-size:16px;">
        Bonjour <strong>{$etudiantNom}</strong>,
      </p>
      
      <p style="margin:0 0 20px;font-size:15px;">
        Votre demande de <strong>{$type}</strong> a été refusée.
      </p>
      
      <!-- Informations -->
      <div style="margin:25px 0;padding:20px;background:#f9f9f9;border-left:4px solid #f44336;border-radius:4px;">
        <p style="margin:0 0 10px;font-size:14px;">
          <strong>Type de document :</strong> {$type}
        </p>
        <p style="margin:0;font-size:14px;">
          <strong>Numéro de demande :</strong> {$num}
        </p>
      </div>
      
      <!-- Motif -->
      <div style="margin:25px 0;padding:15px;background:#ffebee;border-radius:4px;">
        <p style="margin:0 0 8px;font-size:14px;color:#c62828;font-weight:bold;">
          Motif du refus :
        </p>
        <p style="margin:0;font-size:14px;color:#d32f2f;">
          {$motif}
        </p>
      </div>
      
      <p style="margin:20px 0 0;font-size:14px;color:#666666;">
        Vous pouvez corriger les informations et soumettre une nouvelle demande. Pour toute question, contactez le service de scolarité.
      </p>
    </div>
    
    <!-- Footer -->
    <div style="padding:20px 30px;background:#f9f9f9;border-top:1px solid #e0e0e0;border-radius:0 0 8px 8px;">
      <p style="margin:0;font-size:13px;color:#666666;text-align:center;">
        École Nationale des Sciences Appliquées de Tétouan<br>
        <span style="font-size:12px;color:#999999;">Cet email est envoyé automatiquement, merci de ne pas y répondre.</span>
      </p>
    </div>
  </div>
</body>
</html>
HTML;
}

    /**
     * Prévisualiser une demande (générer HTML du document)
     */
    public function preview($num_demande)
    {
        try {
            $demande = Demande::with(['etudiant.filiere', 'attestationscolarite', 'attestationreussite', 'relevenote', 'conventionstage'])
                ->where('num_demande', $num_demande)
                ->firstOrFail();

            $map = [
                'AttestationScolarite' => 'pdf.demande',
                'AttestationReussite'  => 'pdf.demande',
                'ReleveNote'           => 'pdf.demande',
                'ConventionStage'      => 'pdf.demande',
            ];
            $view = $map[$demande->typeDoc] ?? 'pdf.demande';

            // Préparer les notes/modules pour Relevé ou Attestation Réussite si applicables
            $notes = [];
            
            if (($demande->typeDoc === 'ReleveNote' && $demande->relevenote) || 
                ($demande->typeDoc === 'AttestationReussite' && $demande->attestationreussite)) {
                
                $annee = ($demande->typeDoc === 'ReleveNote') 
                    ? $demande->relevenote->annee 
                    : $demande->attestationreussite->anneeObtention;
                    
                $idEtudiant = $demande->idEtudiant;
                
                // Extraire l'année (4 chiffres) pour la recherche
                $searchYear = $annee;
                if (preg_match('/(\d{4})/', $annee, $matches)) {
                    $searchYear = $matches[1];
                }
                
                $notes = DB::table('concerne as c')
                    ->join('contient as ct', 'ct.idContient', '=', 'c.idContient')
                    ->join('module as m', 'm.idM', '=', 'ct.idM')
                    ->where('c.idEtudiant', $idEtudiant)
                    ->where('c.annee', 'like', '%' . $searchYear . '%')
                    ->select('m.code', 'm.nomM as module', 'c.note')
                    ->orderBy('m.code')
                    ->get()
                    ->map(function ($row) {
                        $note = is_null($row->note) ? null : floatval($row->note);
                        return [
                            'code' => $row->code ?? 'N/A',
                            'module' => $row->module ?? 'N/A',
                            'note' => $note,
                            'resultat' => is_null($note) ? 'N/A' : ($note >= 10 ? 'Validé' : 'Non validé'),
                        ];
                    })->toArray();
            }

            $data = [
                'demande'    => $demande,
                'etudiant'   => $demande->etudiant,
                'scolarite'  => $demande->attestationscolarite,
                'reussite'   => $demande->attestationreussite,
                'releve'     => $demande->relevenote,
                'convention' => $demande->conventionstage,
                'notes'      => $notes,
                'now'        => now(),
            ];

            if (class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
                $pdf = Pdf::loadView($view, $data)->setPaper('a4');

                // Créer un nom de fichier significatif
                $typeLabel = $this->getTypeLabel($demande->typeDoc);
                $etudiantNom = $demande->etudiant ? ($demande->etudiant->nom . '_' . $demande->etudiant->prenom) : 'etudiant';
                $numDemande = $demande->num_demande;
                
                // Nettoyer les parties du nom de fichier
                $safeType = preg_replace('/[^A-Za-z0-9_\-]/u', '', str_replace(' ', '_', $typeLabel));
                $safeNom = preg_replace('/[^A-Za-z0-9\-_]/', '', $etudiantNom);

                $fileName = $numDemande . '.pdf';

                $content = $pdf->output();
                return response($content, 200)
                    ->header('Content-Type', 'application/pdf')
                    ->header('Content-Disposition', 'inline; filename="' . $fileName . '"')
                    ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
                    ->header('Pragma', 'no-cache')
                    ->header('Access-Control-Allow-Origin', '*')
                    ->header('Access-Control-Expose-Headers', 'Content-Disposition');
            }

            // Fallback HTML if dompdf is not available
            $html = view($view, $data)->render();
            return response($html, 200)
                ->header('Content-Type', 'text/html')
                ->header('Access-Control-Allow-Origin', '*');

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la génération du document: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Télécharger le PDF avec un nom {num_demande}.pdf (Content-Disposition: attachment)
     */
    public function download($num_demande)
    {
        try {
            $demande = Demande::with(['etudiant.filiere', 'attestationscolarite', 'attestationreussite', 'relevenote', 'conventionstage'])
                ->where('num_demande', $num_demande)
                ->firstOrFail();

            $map = [
                'AttestationScolarite' => 'pdf.demande',
                'AttestationReussite'  => 'pdf.demande',
                'ReleveNote'           => 'pdf.demande',
                'ConventionStage'      => 'pdf.demande',
            ];
            $view = $map[$demande->typeDoc] ?? 'pdf.demande';

            // Préparer les notes/modules si applicables
            $notes = [];
            if (($demande->typeDoc === 'ReleveNote' && $demande->relevenote) || 
                ($demande->typeDoc === 'AttestationReussite' && $demande->attestationreussite)) {
                $annee = ($demande->typeDoc === 'ReleveNote') 
                    ? $demande->relevenote->annee 
                    : $demande->attestationreussite->anneeObtention;
                $idEtudiant = $demande->idEtudiant;
                $searchYear = $annee;
                if (preg_match('/(\d{4})/', $annee, $matches)) {
                    $searchYear = $matches[1];
                }
                $notes = DB::table('concerne as c')
                    ->join('contient as ct', 'ct.idContient', '=', 'c.idContient')
                    ->join('module as m', 'm.idM', '=', 'ct.idM')
                    ->where('c.idEtudiant', $idEtudiant)
                    ->where('c.annee', 'like', '%' . $searchYear . '%')
                    ->select('m.code', 'm.nomM as module', 'c.note')
                    ->orderBy('m.code')
                    ->get()
                    ->map(function ($row) {
                        $note = is_null($row->note) ? null : floatval($row->note);
                        return [
                            'code' => $row->code ?? 'N/A',
                            'module' => $row->module ?? 'N/A',
                            'note' => $note,
                            'resultat' => is_null($note) ? 'N/A' : ($note >= 10 ? 'Validé' : 'Non validé'),
                        ];
                    })->toArray();
            }

            $data = [
                'demande'    => $demande,
                'etudiant'   => $demande->etudiant,
                'scolarite'  => $demande->attestationscolarite,
                'reussite'   => $demande->attestationreussite,
                'releve'     => $demande->relevenote,
                'convention' => $demande->conventionstage,
                'notes'      => $notes,
                'now'        => now(),
            ];

            $pdf = Pdf::loadView($view, $data)->setPaper('a4');
            $content = $pdf->output();
            $fileName = $demande->num_demande . '.pdf';

            return response()->streamDownload(function () use ($content) {
                echo $content;
            }, $fileName, [
                'Content-Type' => 'application/pdf',
                'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
                'Pragma' => 'no-cache',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du téléchargement du document: ' . $e->getMessage()
            ], 500);
        }
    }

}