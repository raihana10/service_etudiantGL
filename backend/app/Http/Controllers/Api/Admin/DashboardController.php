<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Demande;
use App\Models\Reclamation;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Obtenir les statistiques générales du dashboard
     */
    public function getStatistics(Request $request)
    {
        try {
            // Statistiques par statut
            $stats = [
                'total' => Demande::count(),
                'acceptees' => Demande::where('statut', 'Validée')->count(),
                'refusees' => Demande::where('statut', 'Refusée')->count(),
                'enAttente' => Demande::where('statut', 'En attente')->count(),
            ];

            // Statistiques par type de document
            $statsByType = Demande::select('typeDoc', DB::raw('count(*) as count'))
                ->groupBy('typeDoc')
                ->orderBy('count', 'desc')
                ->get();

            // Données pour le graphique donut (répartition par statut)
            $donutData = [
                'labels' => ['Validées', 'Refusées', 'En attente'],
                'data' => [
                    $stats['acceptees'],
                    $stats['refusees'],
                    $stats['enAttente']
                ],
                'backgroundColor' => ['#4E7D96', '#FF844B', '#0A0D25']
            ];

            // Données pour le graphique en barres (demandes par type de document)
            $barData = [
                'labels' => $statsByType->pluck('typeDoc')->toArray(),
                'data' => $statsByType->pluck('count')->toArray(),
                'backgroundColor' => '#4E7D96'
            ];

            // Statistiques des 7 derniers jours (Demandes)
            $last7Days = [];
            $last7DaysLabels = [];

            // Statistiques des 7 derniers jours (Réclamations)
            $last7DaysReclamations = [];

            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i);

                // Demandes
                $countDemandes = Demande::whereDate('datesoumission', $date)->count();
                $last7Days[] = $countDemandes;

                // Réclamations
                $countReclamations = Reclamation::whereDate('datesoumission', $date)->count();
                $last7DaysReclamations[] = $countReclamations;

                $last7DaysLabels[] = $date->format('d/m');
            }

            // Statistiques globales des réclamations
            $reclamationStats = [
                'total' => Reclamation::count(),
                'enCours' => Reclamation::where('statut', 'En cours')->count(),
                'resolues' => Reclamation::where('statut', 'Résolue')->count(),
            ];

            // Taux de résolution
            $resolutionRate = $reclamationStats['total'] > 0
                ? round(($reclamationStats['resolues'] / $reclamationStats['total']) * 100, 1)
                : 0;

            // Temps moyen de traitement (en heures)
            $avgProcessingTime = Demande::whereNotNull('date_traitement')
                ->select(DB::raw('AVG(TIMESTAMPDIFF(HOUR, datesoumission, date_traitement)) as avg_time'))
                ->value('avg_time');

            $avgProcessingTime = $avgProcessingTime ? round($avgProcessingTime, 1) : 0;

            // Top 5 Étudiants actifs
            $topStudents = Demande::select('idEtudiant', DB::raw('count(*) as total_demandes'))
                ->with('etudiant')
                ->when(true, function ($query) {
                    // Check if column exists or just group by commonly used FK
                    return $query->groupBy('idEtudiant');
                })
                ->orderByDesc('total_demandes')
                ->limit(5)
                ->get()
                ->map(function ($item) {
                    return [
                        'nom' => $item->etudiant ? $item->etudiant->nom . ' ' . $item->etudiant->prenom : 'Inconnu',
                        'numApogee' => $item->etudiant ? $item->etudiant->numApogee : '-',
                        'count' => $item->total_demandes
                    ];
                });

            // Données pour le graphique donut (répartition des réclamations)
            $reclamationDonutData = [
                'labels' => ['En cours', 'Résolues'],
                'data' => [
                    $reclamationStats['enCours'],
                    $reclamationStats['resolues']
                ],
                'backgroundColor' => ['#FF844B', '#4E7D96'] // Orange for En cours, Blue for Résolues
            ];

            // Données pour le graphique en ligne (réclamations)
            $lineChartData = [
                'labels' => $last7DaysLabels,
                'data' => $last7DaysReclamations,
                'borderColor' => '#FF844B',
                'backgroundColor' => 'rgba(255, 132, 75, 0.1)',
                'fill' => true
            ];

            // Demandes récentes (5 dernières)
            $recentDemandes = Demande::with(['etudiant'])
                ->orderBy('datesoumission', 'desc')
                ->limit(5)
                ->get()
                ->map(function ($demande) {
                    return [
                        'idDemande' => $demande->idDemande,
                        'typeDoc' => $demande->typeDoc,
                        'statut' => $demande->statut,
                        'datesoumission' => $demande->datesoumission->format('d/m/Y H:i'),
                        'etudiant' => $demande->etudiant ? $demande->etudiant->nom . ' ' . $demande->etudiant->prenom : 'Non spécifié'
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => [
                    'stats' => $stats,
                    'reclamationStats' => $reclamationStats, // Add global reclamation stats
                    'kpis' => [
                        'resolutionRate' => $resolutionRate,
                        'avgProcessingTime' => $avgProcessingTime
                    ],
                    'topStudents' => $topStudents,
                    'donutChart' => $donutData,
                    'barChart' => $barData,
                    'reclamationDonut' => $reclamationDonutData, // Add reclamation donut data
                    'lineChart' => $lineChartData,
                    'last7Days' => [
                        'labels' => $last7DaysLabels,
                        'data' => $last7Days
                    ],
                    'recentDemandes' => $recentDemandes
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

    /**
     * Obtenir les demandes filtrées par statut ou type
     */
    public function getDemandes(Request $request)
    {
        try {
            $query = Demande::with(['etudiant', 'administrateur']);

            // Filtrage par statut
            if ($request->has('statut') && $request->statut) {
                $query->where('statut', $request->statut);
            }

            // Filtrage par type de document
            if ($request->has('typeDoc') && $request->typeDoc) {
                $query->where('typeDoc', $request->typeDoc);
            }

            // Pagination
            $page = $request->get('page', 1);
            $limit = $request->get('limit', 10);

            $demandes = $query->orderBy('datesoumission', 'desc')
                ->skip(($page - 1) * $limit)
                ->take($limit)
                ->get()
                ->map(function ($demande) {
                    return [
                        'idDemande' => $demande->idDemande,
                        'typeDoc' => $demande->typeDoc,
                        'statut' => $demande->statut,
                        'datesoumission' => $demande->datesoumission->format('d/m/Y H:i'),
                        'date_traitement' => $demande->date_traitement ? $demande->date_traitement->format('d/m/Y H:i') : null,
                        'motif_refus' => $demande->motif_refus,
                        'etudiant' => $demande->etudiant ? [
                            'nom' => $demande->etudiant->nom,
                            'prenom' => $demande->etudiant->prenom,
                            'email' => $demande->etudiant->email
                        ] : null,
                        'administrateur' => $demande->administrateur ? [
                            'email' => $demande->administrateur->email
                        ] : null
                    ];
                });

            $total = $query->count();

            return response()->json([
                'success' => true,
                'data' => [
                    'demandes' => $demandes,
                    'pagination' => [
                        'current_page' => $page,
                        'per_page' => $limit,
                        'total' => $total,
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
                'message' => 'Erreur lors de la récupération des demandes',
                'error' => $e->getMessage()
            ], 500)
                ->header('Access-Control-Allow-Origin', '*')
                ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
                ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With');
        }
    }

    /**
     * Obtenir les statistiques détaillées par période
     */
    public function getDetailedStats(Request $request)
    {
        try {
            $period = $request->get('period', 'month'); // week, month, year

            $dateFormat = match ($period) {
                'week' => '%Y-%u',
                'month' => '%Y-%m',
                'year' => '%Y',
                default => '%Y-%m'
            };

            $stats = Demande::select(
                DB::raw("DATE_FORMAT(datesoumission, '$dateFormat') as period"),
                DB::raw('count(*) as total'),
                DB::raw("SUM(CASE WHEN statut = 'Validée' THEN 1 ELSE 0 END) as acceptees"),
                DB::raw("SUM(CASE WHEN statut = 'Refusée' THEN 1 ELSE 0 END) as refusees"),
                DB::raw("SUM(CASE WHEN statut = 'En attente' THEN 1 ELSE 0 END) as enAttente")
            )
                ->groupBy('period')
                ->orderBy('period', 'asc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $stats
            ], 200)
                ->header('Access-Control-Allow-Origin', '*')
                ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
                ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With');

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des statistiques détaillées',
                'error' => $e->getMessage()
            ], 500)
                ->header('Access-Control-Allow-Origin', '*')
                ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
                ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With');
        }
    }
}
