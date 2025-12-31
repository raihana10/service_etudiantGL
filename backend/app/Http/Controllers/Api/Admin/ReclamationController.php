<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reclamation;
use App\Models\Etudiant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReclamationResponseMail;

class ReclamationController extends Controller
{
    public function index(Request $request)
    {
        $query = Reclamation::with(['etudiant', 'administrateur', 'demande']);

        // Filter by status
        if ($request->has('statut') && $request->statut !== 'Toutes') {
            $query->where('statut', $request->statut);
        }


        // Search functionality
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('idReclamation', 'LIKE', "%{$search}%")
                    ->orWhereHas('etudiant', function ($subQuery) use ($search) {
                        $subQuery->where('nom', 'LIKE', "%{$search}%")
                            ->orWhere('prenom', 'LIKE', "%{$search}%")
                            ->orWhere('emailInstitu', 'LIKE', "%{$search}%");
                    })
                    ->orWhere('sujet', 'LIKE', "%{$search}%")
                    ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        // Sort by date (newest first)
        $query->orderBy('datesoumission', 'desc');

        $reclamations = $query->paginate($request->get('per_page', 10));

        return response()->json([
            'success' => true,
            'data' => $reclamations
        ]);
    }

    public function show($id)
    {
        $reclamation = Reclamation::with(['etudiant', 'administrateur', 'demande'])
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $reclamation
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        // Require status, but make idAdmin optional in validation if we can get it from auth
        $validator = Validator::make($request->all(), [
            'statut' => 'required|in:En cours,Résolue',
            'idAdmin' => 'nullable|exists:administrateur,idAdmin'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Données invalides',
                'errors' => $validator->errors()
            ], 422);
        }

        $idAdmin = $request->idAdmin;

        // If idAdmin not provided, try to get from authenticated user
        if (!$idAdmin && auth()->check()) {
            $user = auth()->user();
            // Assuming the authenticated user model has idAdmin (if using Sanctum/Passport with custom provider)
            // Or if the user IS the administrator
            if (isset($user->idAdmin)) {
                $idAdmin = $user->idAdmin;
            }
        }

        if (!$idAdmin) {
            return response()->json([
                'success' => false,
                'message' => 'Identifiant administrateur manquant',
            ], 422);
        }

        $reclamation = Reclamation::findOrFail($id);
        $reclamation->statut = $request->statut;
        $reclamation->idAdmin = $idAdmin;

        if ($request->statut === 'Résolue') {
            $reclamation->dateReponse = now();
        }

        $reclamation->save();

        return response()->json([
            'success' => true,
            'message' => 'Statut mis à jour avec succès',
            'data' => $reclamation->load(['etudiant', 'administrateur'])
        ]);
    }

    public function respond(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'reponse' => 'required|string|min:3',
            'idAdmin' => 'nullable|exists:administrateur,idAdmin'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Données invalides',
                'errors' => $validator->errors()
            ], 422);
        }

        $idAdmin = $request->idAdmin;

        if (!$idAdmin && auth()->check()) {
            $user = auth()->user();
            if (isset($user->idAdmin)) {
                $idAdmin = $user->idAdmin;
            }
        }

        if (!$idAdmin) {
            return response()->json([
                'success' => false,
                'message' => 'Identifiant administrateur manquant',
            ], 422);
        }

        $reclamation = Reclamation::with('etudiant')->findOrFail($id);
        $reclamation->reponse = $request->reponse;
        $reclamation->idAdmin = $idAdmin;
        $reclamation->statut = 'Résolue';
        $reclamation->dateReponse = now();
        $reclamation->save();

        // Send email to student
        try {
            Mail::to($reclamation->etudiant->emailInstitu)->send(new ReclamationResponseMail($reclamation));
        } catch (\Exception $e) {
            \Log::error('Failed to send reclamation response email: ' . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Réponse envoyée avec succès',
            'data' => $reclamation->load(['etudiant', 'administrateur'])
        ]);
    }

    public function getStatistics()
    {
        $stats = [
            'total' => Reclamation::count(),
            'en_cours' => Reclamation::where('statut', 'En cours')->count(),
            'resolues' => Reclamation::where('statut', 'Résolue')->count(),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }
}
