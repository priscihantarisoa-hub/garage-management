<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Reparation;
use App\Models\Client;
use App\Models\Intervention;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

/**
 * API Controller pour la gestion des réparations
 * 
 * Endpoints pour l'application mobile et le jeu Godot
 */
class RepairController extends Controller
{
    /**
     * Liste toutes les réparations avec pagination
     * GET /api/repairs
     */
    public function index(Request $request): JsonResponse
    {
        $query = Reparation::with(['client', 'interventions']);

        // Filtrer par statut si demandé
        if ($request->has('statut')) {
            $query->where('statut', $request->statut);
        }

        // Filtrer par client si demandé
        if ($request->has('client_id')) {
            $query->where('client_id', $request->client_id);
        }

        $repairs = $query->orderBy('created_at', 'desc')
                        ->paginate($request->get('per_page', 10));

        return response()->json([
            'success' => true,
            'data' => $repairs
        ]);
    }

    /**
     * Affiche les réparations en cours (max 3 slots)
     * GET /api/repairs/active
     */
    public function active(): JsonResponse
    {
        $activeRepairs = Reparation::whereIn('statut', ['en_attente', 'en_cours'])
            ->with(['client', 'interventions'])
            ->orderBy('created_at', 'asc')
            ->limit(3)
            ->get();

        $availableSlots = 3 - $activeRepairs->where('statut', 'en_cours')->count();

        return response()->json([
            'success' => true,
            'data' => [
                'repairs' => $activeRepairs,
                'available_slots' => $availableSlots,
                'total_slots' => 3
            ]
        ]);
    }

    /**
     * Affiche les réparations terminées en attente de paiement
     * GET /api/repairs/completed
     */
    public function completed(): JsonResponse
    {
        $completedRepairs = Reparation::where('statut', 'termine')
            ->with(['client', 'interventions'])
            ->orderBy('updated_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $completedRepairs
        ]);
    }

    /**
     * Affiche une réparation spécifique
     * GET /api/repairs/{id}
     */
    public function show($id): JsonResponse
    {
        $repair = Reparation::with(['client', 'interventions', 'payments'])
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $repair
        ]);
    }

    /**
     * Crée une nouvelle réparation
     * POST /api/repairs
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'intervention_ids' => 'required|array',
            'intervention_ids.*' => 'exists:interventions,id',
            'slot' => 'nullable|integer|min:1|max:3'
        ]);

        // Vérifier la disponibilité des slots
        $currentInProgress = Reparation::where('statut', 'en_cours')->count();
        if ($currentInProgress >= 3) {
            return response()->json([
                'success' => false,
                'message' => 'Le garage ne peut accueillir que 3 voitures en réparation'
            ], 400);
        }

        // Calculer le montant total
        $interventions = Intervention::whereIn('id', $request->intervention_ids)->get();
        $montantTotal = $interventions->sum('prix');

        // Créer la réparation
        $repair = Reparation::create([
            'client_id' => $request->client_id,
            'statut' => 'en_attente',
            'slot' => $request->slot ?? null,
            'montant_total' => $montantTotal
        ]);

        // Attachement des interventions avec pivot
        foreach ($request->intervention_ids as $interventionId) {
            $repair->interventions()->attach($interventionId, ['status' => 'pending']);
        }

        return response()->json([
            'success' => true,
            'data' => $repair->load(['client', 'interventions']),
            'message' => 'Réparation créée avec succès'
        ], 201);
    }

    /**
     * Met à jour le statut d'une réparation
     * PUT /api/repairs/{id}/status
     */
    public function updateStatus(Request $request, $id): JsonResponse
    {
        $request->validate([
            'statut' => 'required|in:en_attente,en_cours,termine,paye'
        ]);

        $repair = Reparation::findOrFail($id);
        $newStatus = $request->statut;

        // Vérifier la transition de statut
        if (!$this->isValidTransition($repair->statut, $newStatus)) {
            return response()->json([
                'success' => false,
                'message' => 'Transition de statut non valide'
            ], 400);
        }

        // Vérifier la limite de 3 voitures pour "en_cours"
        if ($newStatus === 'en_cours') {
            $currentInProgress = Reparation::where('statut', 'en_cours')->count();
            if ($currentInProgress >= 3 && $repair->statut !== 'en_cours') {
                return response()->json([
                    'success' => false,
                    'message' => 'Le garage ne peut accueillir que 3 voitures en réparation'
                ], 400);
            }
        }

        // Mettre à jour la réparation
        $repair->update(['statut' => $newStatus]);

        // Mettre à jour les dates
        if ($newStatus === 'en_cours' && !$repair->debut_reparation) {
            $repair->update(['debut_reparation' => now()]);
        } elseif ($newStatus === 'termine') {
            $repair->update(['fin_reparation' => now()]);
        }

        return response()->json([
            'success' => true,
            'data' => $repair->load(['client', 'interventions']),
            'message' => 'Statut mis à jour avec succès'
        ]);
    }

    /**
     * Met à jour une intervention spécifique dans une réparation
     * PUT /api/repairs/{repairId}/interventions/{interventionId}
     */
    public function updateInterventionStatus(Request $request, $repairId, $interventionId): JsonResponse
    {
        $request->validate([
            'status' => 'required|in:pending,in_progress,completed'
        ]);

        $repair = Reparation::findOrFail($repairId);
        
        $pivot = $repair->interventions()->where('intervention_id', $interventionId)->first();
        
        if (!$pivot) {
            return response()->json([
                'success' => false,
                'message' => 'Intervention non trouvée dans cette réparation'
            ], 404);
        }

        $repair->interventions()->updateExistingPivot($interventionId, [
            'status' => $request->status
        ]);

        // Vérifier si toutes les interventions sont terminées
        $allCompleted = $repair->interventions()
            ->wherePivot('status', '!=', 'completed')
            ->count() === 0;

        if ($allCompleted && $repair->statut !== 'termine') {
            $repair->update(['statut' => 'termine', 'fin_reparation' => now()]);
        }

        return response()->json([
            'success' => true,
            'data' => $repair->load(['client', 'interventions']),
            'all_completed' => $allCompleted
        ]);
    }

    /**
     * Vérifie si une transition de statut est valide
     */
    private function isValidTransition(string $current, string $new): bool
    {
        $transitions = [
            'en_attente' => ['en_cours', 'termine'],
            'en_cours' => ['termine', 'en_attente'],
            'termine' => ['paye', 'en_attente'],
            'paye' => [] // État final
        ];

        return in_array($new, $transitions[$current] ?? []);
    }

    /**
     * Affiche les statistiques du garage
     * GET /api/repairs/stats
     */
    public function stats(): JsonResponse
    {
        $stats = [
            'total_repairs' => Reparation::count(),
            'repairs_in_progress' => Reparation::where('statut', 'en_cours')->count(),
            'repairs_pending' => Reparation::where('statut', 'en_attente')->count(),
            'repairs_completed' => Reparation::where('statut', 'termine')->count(),
            'repairs_paid' => Reparation::where('statut', 'paye')->count(),
            'total_amount' => Reparation::where('statut', 'paye')->sum('montant_total'),
            'available_slots' => 3 - Reparation::where('statut', 'en_cours')->count(),
            'total_clients' => Client::count()
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }
}
