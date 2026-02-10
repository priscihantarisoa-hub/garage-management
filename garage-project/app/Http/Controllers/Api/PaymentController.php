<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Reparation;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

/**
 * API Controller pour la gestion des paiements
 * 
 * Endpoints pour l'application mobile
 */
class PaymentController extends Controller
{
    /**
     * Liste tous les paiements avec pagination
     * GET /api/payments
     */
    public function index(Request $request): JsonResponse
    {
        $query = Payment::with(['reparation.client']);

        // Filtrer par réparation si demandé
        if ($request->has('reparation_id')) {
            $query->where('reparation_id', $request->reparation_id);
        }

        // Filtrer par statut si demandé
        if ($request->has('statut')) {
            $query->where('statut', $request->statut);
        }

        $payments = $query->orderBy('created_at', 'desc')
                         ->paginate($request->get('per_page', 10));

        return response()->json([
            'success' => true,
            'data' => $payments
        ]);
    }

    /**
     * Affiche un paiement spécifique
     * GET /api/payments/{id}
     */
    public function show($id): JsonResponse
    {
        $payment = Payment::with(['reparation.client'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $payment
        ]);
    }

    /**
     * Crée un nouveau paiement
     * POST /api/payments
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'reparation_id' => 'required|exists:reparations,id',
            'montant' => 'required|numeric|min:0',
            'methode' => 'required|in:especes,carte,virement'
        ]);

        // Vérifier que la réparation existe et n'est pas déjà payée
        $reparation = Reparation::findOrFail($request->reparation_id);

        if ($reparation->statut === 'paye') {
            return response()->json([
                'success' => false,
                'message' => 'Cette réparation est déjà payée'
            ], 400);
        }

        // Créer le paiement
        $payment = Payment::create([
            'reparation_id' => $request->reparation_id,
            'montant' => $request->montant,
            'methode' => $request->methode,
            'date_paiement' => now(),
            'statut' => 'valide'
        ]);

        // Mettre à jour le statut de la réparation
        $reparation->update(['statut' => 'paye']);

        return response()->json([
            'success' => true,
            'data' => $payment->load(['reparation']),
            'message' => 'Paiement effectué avec succès'
        ], 201);
    }

    /**
     * Effectue le paiement d'une réparation (raccourci pour mobile)
     * POST /api/payments/pay/{reparationId}
     */
    public function pay(Request $request, $reparationId): JsonResponse
    {
        $request->validate([
            'methode' => 'required|in:especes,carte,virement'
        ]);

        $reparation = Reparation::with(['client', 'interventions'])->findOrFail($reparationId);

        if ($reparation->statut !== 'termine') {
            return response()->json([
                'success' => false,
                'message' => 'La réparation doit être terminée avant le paiement'
            ], 400);
        }

        // Créer le paiement
        $payment = Payment::create([
            'reparation_id' => $reparationId,
            'montant' => $reparation->montant_total,
            'methode' => $request->methode,
            'date_paiement' => now(),
            'statut' => 'valide'
        ]);

        // Mettre à jour le statut de la réparation
        $reparation->update(['statut' => 'paye']);

        return response()->json([
            'success' => true,
            'data' => [
                'payment' => $payment,
                'reparation' => $reparation->fresh()
            ],
            'message' => 'Paiement effectué avec succès. Vous pouvez récupérer votre véhicule.'
        ]);
    }

    /**
     * Annule un paiement
     * PUT /api/payments/{id}/cancel
     */
    public function cancel($id): JsonResponse
    {
        $payment = Payment::findOrFail($id);

        if ($payment->statut === 'annule') {
            return response()->json([
                'success' => false,
                'message' => 'Ce paiement est déjà annulé'
            ], 400);
        }

        $payment->update(['statut' => 'annule']);

        // Remettre la réparation en attente de paiement
        $reparation = $payment->reparation;
        if ($reparation->statut === 'paye') {
            $reparation->update(['statut' => 'termine']);
        }

        return response()->json([
            'success' => true,
            'data' => $payment,
            'message' => 'Paiement annulé avec succès'
        ]);
    }

    /**
     * Affiche l'historique des paiements d'un client
     * GET /api/payments/client/{clientId}
     */
    public function clientPayments($clientId): JsonResponse
    {
        $payments = Payment::whereHas('reparation', function ($query) use ($clientId) {
                $query->where('client_id', $clientId);
            })
            ->with(['reparation.interventions'])
            ->orderBy('created_at', 'desc')
            ->get();

        $totalPaid = $payments->where('statut', 'valide')->sum('montant');

        return response()->json([
            'success' => true,
            'data' => [
                'payments' => $payments,
                'total_paid' => $totalPaid
            ]
        ]);
    }

    /**
     * Statistiques des paiements
     * GET /api/payments/stats
     */
    public function stats(): JsonResponse
    {
        $stats = [
            'total_payments' => Payment::count(),
            'total_amount' => Payment::where('statut', 'valide')->sum('montant'),
            'by_method' => Payment::where('statut', 'valide')
                ->groupBy('methode')
                ->selectRaw('methode, SUM(montant) as total, COUNT(*) as count')
                ->get()
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }
}
