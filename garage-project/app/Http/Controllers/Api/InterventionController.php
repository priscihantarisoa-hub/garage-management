<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Intervention;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

/**
 * API Controller pour la gestion des interventions
 * 
 * Endpoints pour l'application mobile et le jeu Godot
 */
class InterventionController extends Controller
{
    /**
     * Liste toutes les interventions
     * GET /api/interventions
     */
    public function index(Request $request): JsonResponse
    {
        $query = Intervention::query();

        // Filtrer par type si demandé
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        // Filtrer par actives uniquement
        if ($request->has('actif') && $request->actif) {
            $query->where('actif', true);
        }

        $interventions = $query->orderBy('nom')->get();

        return response()->json([
            'success' => true,
            'data' => $interventions
        ]);
    }

    /**
     * Affiche une intervention spécifique
     * GET /api/interventions/{id}
     */
    public function show($id): JsonResponse
    {
        $intervention = Intervention::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $intervention
        ]);
    }

    /**
     * Liste les interventions par type
     * GET /api/interventions/by-type/{type}
     */
    public function byType($type): JsonResponse
    {
        $interventions = Intervention::where('type', $type)
            ->where('actif', true)
            ->orderBy('nom')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $interventions
        ]);
    }

    /**
     * Liste tous les types d'interventions disponibles
     * GET /api/interventions/types
     */
    public function types(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => Intervention::typesInterventions()
        ]);
    }
}
