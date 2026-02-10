<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

/**
 * API Controller pour l'authentification des utilisateurs mobiles
 * 
 * Endpoints pour login, register et gestion du profile
 */
class AuthController extends Controller
{
    /**
     * Connexion d'un utilisateur
     * POST /api/auth/login
     */
    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Identifiants incorrects'
            ], 401);
        }

        // Générer le token
        $token = $user->createToken('mobile-app')->plainTextToken;

        return response()->json([
            'success' => true,
            'data' => [
                'user' => $user,
                'token' => $token,
                'token_type' => 'Bearer'
            ],
            'message' => 'Connexion réussie'
        ]);
    }

    /**
     * Inscription d'un nouvel utilisateur
     * POST /api/auth/register
     */
    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'telephone' => 'nullable|string|max:20',
            'voiture_marque' => 'nullable|string|max:100',
            'voiture_modele' => 'nullable|string|max:100',
            'voiture_immatriculation' => 'nullable|string|max:20'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Créer l'utilisateur
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        // Créer le client associé
        $client = Client::create([
            'nom' => $request->name,
            'email' => $request->email,
            'telephone' => $request->telephone ?? null,
            'voiture_marque' => $request->voiture_marque ?? null,
            'voiture_modele' => $request->voiture_modele ?? null,
            'voiture_immatriculation' => $request->voiture_immatriculation ?? null,
            'user_id' => $user->id
        ]);

        // Générer le token
        $token = $user->createToken('mobile-app')->plainTextToken;

        return response()->json([
            'success' => true,
            'data' => [
                'user' => $user,
                'client' => $client,
                'token' => $token,
                'token_type' => 'Bearer'
            ],
            'message' => 'Inscription réussie'
        ], 201);
    }

    /**
     * Affiche le profil de l'utilisateur connecté
     * GET /api/auth/profile
     */
    public function profile(Request $request): JsonResponse
    {
        $user = $request->user();
        $client = $user->client ?? Client::where('user_id', $user->id)->first();

        return response()->json([
            'success' => true,
            'data' => [
                'user' => $user,
                'client' => $client
            ]
        ]);
    }

    /**
     * Met à jour le profil de l'utilisateur
     * PUT /api/auth/profile
     */
    public function updateProfile(Request $request): JsonResponse
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'sometimes|string|min:6|confirmed',
            'push_token' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Mettre à jour l'utilisateur
        if ($request->has('name')) {
            $user->name = $request->name;
        }
        if ($request->has('email')) {
            $user->email = $request->email;
        }
        if ($request->has('password')) {
            $user->password = Hash::make($request->password);
        }
        if ($request->has('push_token')) {
            $user->push_token = $request->push_token;
        }
        $user->save();

        // Mettre à jour le client si existant
        $client = $user->client ?? Client::where('user_id', $user->id)->first();
        if ($client) {
            $client->update([
                'nom' => $request->get('name', $client->nom),
                'email' => $request->get('email', $client->email),
                'telephone' => $request->get('telephone', $client->telephone),
                'voiture_marque' => $request->get('voiture_marque', $client->voiture_marque),
                'voiture_modele' => $request->get('voiture_modele', $client->voiture_modele),
                'voiture_immatriculation' => $request->get('voiture_immatriculation', $client->voiture_immatriculation)
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'user' => $user,
                'client' => $client->fresh()
            ],
            'message' => 'Profil mis à jour avec succès'
        ]);
    }

    /**
     * Met à jour le push token pour les notifications
     * PUT /api/auth/push-token
     */
    public function updatePushToken(Request $request): JsonResponse
    {
        $request->validate([
            'push_token' => 'required|string'
        ]);

        $user = $request->user();
        $user->update(['push_token' => $request->push_token]);

        return response()->json([
            'success' => true,
            'message' => 'Push token mis à jour'
        ]);
    }

    /**
     * Déconnexion de l'utilisateur
     * POST /api/auth/logout
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Déconnexion réussie'
        ]);
    }

    /**
     * Vérifie si l'email existe déjà
     * GET /api/auth/check-email
     */
    public function checkEmail(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $exists = User::where('email', $request->email)->exists();

        return response()->json([
            'success' => true,
            'exists' => $exists
        ]);
    }
}
