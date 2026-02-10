<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Reparation;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

/**
 * API Controller pour la gestion des clients
 * 
 * Endpoints pour l'application mobile et le jeu Godot
 */
class ClientController extends Controller
{
    /**
     * Liste tous les clients avec pagination
     * GET /api/clients
     */
    public function index(Request $request): JsonResponse
    {
        $query = Client::query();

        // Recherche par nom ou email
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('voiture_immatriculation', 'like', "%{$search}%");
            });
        }

        $clients = $query->orderBy('created_at', 'desc')
                         ->paginate($request->get('per_page', 10));

        return response()->json([
            'success' => true,
            'data' => $clients
        ]);
    }

    /**
     * Affiche un client spécifique avec son historique
     * GET /api/clients/{id}
     */
    public function show($id): JsonResponse
    {
        $client = Client::with(['user'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $client
        ]);
    }

    /**
     * Affiche l'historique des réparations d'un client
     * GET /api/clients/{id}/repairs
     */
    public function repairs($id): JsonResponse
    {
        $client = Client::findOrFail($id);

        $repairs = Reparation::where('client_id', $id)
            ->with(['interventions'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Calculer les statistiques
        $stats = [
            'total_repairs' => $repairs->count(),
            'total_spent' => $repairs->where('statut', 'paye')->sum('montant_total'),
            'pending_amount' => $repairs->whereIn('statut', ['en_attente', 'en_cours', 'termine'])->sum('montant_total'),
            'last_repair_date' => $repairs->max('created_at')
        ];

        return response()->json([
            'success' => true,
            'data' => [
                'client' => $client,
                'repairs' => $repairs,
                'stats' => $stats
            ]
        ]);
    }

    /**
     * Crée un nouveau client
     * POST /api/clients
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:clients',
            'telephone' => 'nullable|string|max:20',
            'voiture_marque' => 'nullable|string|max:100',
            'voiture_modele' => 'nullable|string|max:100',
            'voiture_annee' => 'nullable|integer',
            'voiture_immatriculation' => 'nullable|string|max:20'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $client = Client::create($request->all());

        return response()->json([
            'success' => true,
            'data' => $client,
            'message' => 'Client créé avec succès'
        ], 201);
    }

    /**
     * Met à jour un client
     * PUT /api/clients/{id}
     */
    public function update(Request $request, $id): JsonResponse
    {
        $client = Client::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nom' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:clients,email,' . $id,
            'telephone' => 'nullable|string|max:20',
            'voiture_marque' => 'nullable|string|max:100',
            'voiture_modele' => 'nullable|string|max:100',
            'voiture_annee' => 'nullable|integer',
            'voiture_immatriculation' => 'nullable|string|max:20'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $client->update($request->all());

        return response()->json([
            'success' => true,
            'data' => $client,
            'message' => 'Client mis à jour avec succès'
        ]);
    }

    /**
     * Supprime un client
     * DELETE /api/clients/{id}
     */
    public function destroy($id): JsonResponse
    {
        $client = Client::findOrFail($id);

        // Vérifier s'il y a des réparations associées
        if ($client->reparations()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Ce client ne peut pas être supprimé car il a des réparations associées'
            ], 400);
        }

        $client->delete();

        return response()->json([
            'success' => true,
            'message' => 'Client supprimé avec succès'
        ]);
    }

    /**
     * Affiche les réparations en cours d'un client
     * GET /api/clients/{id}/active-repairs
     */
    public function activeRepairs($id): JsonResponse
    {
        $client = Client::findOrFail($id);

        $activeRepairs = Reparation::where('client_id', $id)
            ->whereIn('statut', ['en_attente', 'en_cours', 'termine'])
            ->with(['interventions'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'client' => $client,
                'repairs' => $activeRepairs
            ]
        ]);
    }
}
