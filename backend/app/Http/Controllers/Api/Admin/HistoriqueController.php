<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Demande;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class HistoriqueController extends Controller
{
    /**
     * Obtenir l'historique des demandes traitées (validées/refusées)
     */
    public function getHistorique(Request $request)
    {
        try {
            $query = Demande::with(['etudiant', 'administrateur'])
                ->whereIn('statut', ['Validée', 'Refusée']);

            // Filtre par nom d'étudiant
            if ($request->has('nomEtudiant') && $request->nomEtudiant) {
                $query->whereHas('etudiant', function ($q) use ($request) {
                    $q->where('nom', 'like', '%' . $request->nomEtudiant . '%')
                      ->orWhere('prenom', 'like', '%' . $request->nomEtudiant . '%');
                });
            }

            // Filtre par N° APOGEE
            if ($request->has('numApogee') && $request->numApogee) {
                $query->whereHas('etudiant', function ($q) use ($request) {
                    $q->where('numApogee', 'like', '%' . $request->numApogee . '%');
                });
            }

            // Filtre par date de traitement
            if ($request->has('dateTraitement') && $request->dateTraitement) {
                $query->whereDate('date_traitement', $request->dateTraitement);
            }

            // Filtre par statut
            if ($request->has('statut') && $request->statut) {
                $query->where('statut', $request->statut);
            }

            // Filtre par type de document
            if ($request->has('typeDoc') && $request->typeDoc) {
                $query->where('typeDoc', $request->typeDoc);
            }

            // Pagination
            $page = $request->get('page', 1);
            $limit = $request->get('limit', 10);
            
            $demandes = $query->orderBy('date_traitement', 'desc')
                ->skip(($page - 1) * $limit)
                ->take($limit)
                ->get()
                ->map(function ($demande) {
                    return [
                        'idDemande' => $demande->idDemande,
                        'typeDoc' => $demande->typeDoc,
                        'statut' => $demande->statut,
                        'datesoumission' => $demande->datesoumission->toISOString(),
                        'date_traitement' => $demande->date_traitement ? $demande->date_traitement->toISOString() : null,
                        'motif_refus' => $demande->motif_refus,
                        'etudiant' => $demande->etudiant ? [
                            'nom' => $demande->etudiant->nom,
                            'prenom' => $demande->etudiant->prenom,
                            'niveau' => $demande->etudiant->niveau,
                            'CIN' => $demande->etudiant->CIN,
                            'numApogee' => $demande->etudiant->numApogee,
                            'emailInstitu' => $demande->etudiant->emailInstitu,
                            'dateNaissance' => $demande->etudiant->dateNaissance ? $demande->etudiant->dateNaissance->toISOString() : null,
                            'lieuNaissance' => $demande->etudiant->lieuNaissance
                        ] : null,
                        'administrateur' => $demande->administrateur ? [
                            'email' => $demande->administrateur->email
                        ] : null
                    ];
                });

            // Pagination
            $total = $query->count();

            return response()->json([
                'success' => true,
                'data' => [
                    'demandes' => $demandes,
                    'pagination' => [
                        'current_page' => $page,
                        'per_page' => $limit,
                        'total' => $total,
                        'from' => (($page - 1) * $limit) + 1,
                        'to' => min(($page * $limit), $total),
                        'last_page' => ceil($total / $limit)
                    ]
                ]
            ], 200)
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
            ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With');

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération de l\'historique',
                'error' => $e->getMessage()
            ], 500)
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
            ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With');
        }
    }

    /**
     * Obtenir les détails d'une demande spécifique
     */
    public function getDemandeDetails($idDemande)
    {
        try {
            $demande = Demande::with(['etudiant', 'administrateur'])
                ->where('idDemande', $idDemande)
                ->whereIn('statut', ['Validée', 'Refusée'])
                ->first();

            if (!$demande) {
                return response()->json([
                    'success' => false,
                    'message' => 'Demande non trouvée ou non traitée'
                ], 404)
                ->header('Access-Control-Allow-Origin', '*')
                ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
                ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With');
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'idDemande' => $demande->idDemande,
                    'typeDoc' => $demande->typeDoc,
                    'statut' => $demande->statut,
                    'datesoumission' => $demande->datesoumission->toISOString(),
                    'date_traitement' => $demande->date_traitement ? $demande->date_traitement->toISOString() : null,
                    'motif_refus' => $demande->motif_refus,
                    'etudiant' => $demande->etudiant ? [
                        'idEtudiant' => $demande->etudiant->idEtudiant,
                        'nom' => $demande->etudiant->nom,
                        'prenom' => $demande->etudiant->prenom,
                        'niveau' => $demande->etudiant->niveau,
                        'CIN' => $demande->etudiant->CIN,
                        'numApogee' => $demande->etudiant->numApogee,
                        'emailInstitu' => $demande->etudiant->emailInstitu,
                        'dateNaissance' => $demande->etudiant->dateNaissance ? $demande->etudiant->dateNaissance->toISOString() : null,
                        'lieuNaissance' => $demande->etudiant->lieuNaissance
                    ] : null,
                    'administrateur' => $demande->administrateur ? [
                        'idAdmin' => $demande->administrateur->idAdmin,
                        'email' => $demande->administrateur->email
                    ] : null
                ]
            ], 200)
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
            ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With');

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des détails de la demande',
                'error' => $e->getMessage()
            ], 500)
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
            ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With');
        }
    }

    /**
     * Obtenir les statistiques détaillées de l'historique
     */
    public function getHistoriqueStats(Request $request)
    {
        try {
            $query = Demande::whereIn('statut', ['Validée', 'Refusée']);

            // Appliquer les mêmes filtres que pour l'historique
            if ($request->has('statut') && $request->statut) {
                $query->where('statut', $request->statut);
            }
            if ($request->has('typeDoc') && $request->typeDoc) {
                $query->where('typeDoc', $request->typeDoc);
            }
            if ($request->has('periode') && $request->periode) {
                $days = (int) $request->periode;
                $query->where('date_traitement', '>=', Carbon::now()->subDays($days));
            }

            // Statistiques par statut
            $statsByStatus = $query->select('statut', DB::raw('count(*) as count'))
                ->groupBy('statut')
                ->orderBy('count', 'desc')
                ->get();

            // Statistiques par type de document
            $statsByType = $query->select('typeDoc', DB::raw('count(*) as count'))
                ->groupBy('typeDoc')
                ->orderBy('count', 'desc')
                ->get();

            // Statistiques par mois (derniers 6 mois)
            $monthlyStats = Demande::select(
                    DB::raw('DATE_FORMAT(date_traitement, "%Y-%m") as month'),
                    DB::raw('count(*) as total'),
                    DB::raw("SUM(CASE WHEN statut = 'Validée' THEN 1 ELSE 0 END) as valides"),
                    DB::raw("SUM(CASE WHEN statut = 'Refusée' THEN 1 ELSE 0 END) as refusees")
                )
                ->whereIn('statut', ['Validée', 'Refusée'])
                ->where('date_traitement', '>=', Carbon::now()->subMonths(6))
                ->groupBy('month')
                ->orderBy('month', 'asc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'by_status' => $statsByStatus,
                    'by_type' => $statsByType,
                    'monthly' => $monthlyStats
                ]
            ], 200)
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
            ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With');

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des statistiques',
                'error' => $e->getMessage()
            ], 500)
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
            ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With');
        }
    }
}
