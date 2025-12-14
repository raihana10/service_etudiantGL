<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Administrateur;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AdminLoginController extends Controller
{
    public function login(Request $request)
    {
        try {
            // Validation des données
            $request->validate([
                'email' => 'required|email',
                'password' => 'required|string',
            ]);

            // Recherche de l'administrateur
            $admin = Administrateur::where('email', $request->email)->first();
            
            // Vérification de l'existence et du mot de passe
            if (!$admin) {
                \Log::warning('Admin not found', ['email' => $request->email]);
                return response()->json([
                    'success' => false,
                    'message' => 'Email ou mot de passe incorrect',
                ], 401);
            }

            // Vérification du mot de passe avec Hash::check directement
            if (!Hash::check($request->password, $admin->motDePasse)) {
                \Log::warning('Invalid password', ['email' => $request->email]);
                return response()->json([
                    'success' => false,
                    'message' => 'Email ou mot de passe incorrect',
                ], 401);
            }

            // Créer un token simple
            $token = bin2hex(random_bytes(32));

            \Log::info('Admin login successful', ['email' => $admin->email]);

            return response()->json([
                'success' => true,
                'message' => 'Connexion réussie',
                'data' => [
                    'admin' => [
                        'id' => $admin->idAdmin,
                        'email' => $admin->email,
                    ],
                    'token' => $token,
                ]
            ], 200);

        } catch (ValidationException $e) {
            \Log::error('Validation error', ['errors' => $e->errors()]);
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            \Log::error('Login error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la connexion',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function profile(Request $request)
    {
        try {
            // Pour l'instant, retourner l'admin avec id 1 (à améliorer avec authentification)
            $admin = Administrateur::find(1);
            
            if (!$admin) {
                return response()->json([
                    'success' => false,
                    'message' => 'Administrateur non trouvé'
                ], 404)
                ->header('Access-Control-Allow-Origin', '*')
                ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
                ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With');
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $admin->idAdmin,
                    'email' => $admin->email,
                ]
            ], 200)
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
            ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With');

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération du profil',
                'error' => $e->getMessage()
            ], 500)
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
            ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With');
        }
    }

    public function logout(Request $request)
    {
        try {
            return response()->json([
                'success' => true,
                'message' => 'Déconnexion réussie'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la déconnexion',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}