<?php

namespace App\Http\Controllers;

use App\Models\Reparation;
use App\Models\Client;
use App\Models\Intervention;
use Illuminate\Http\Request;

/**
 * Controller pour la gestion des réparations en cours
 * 
 * Permet de gérer le flux des réparations avec une limite de 3 voitures simultanées
 */
class AdminRepairController extends Controller
{
    /**
     * Affiche les réparations en cours (max 3)
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Récupérer les réparations actives (en attente ou en cours) - max 3 slots
        $activeRepairs = Reparation::whereIn('statut', ['en_attente', 'en_cours'])
            ->with(['client', 'intervention'])
            ->orderBy('created_at', 'asc')
            ->limit(3)
            ->get();

        // Récupérer les réparations terminées (liste séparée)
        $completedRepairs = Reparation::where('statut', 'termine')
            ->with(['client', 'intervention'])
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('admin.repairs.index', compact('activeRepairs', 'completedRepairs'));
    }

    /**
     * Met à jour le statut d'une réparation
     * 
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateStatus(Request $request, $id)
    {
        $repair = Reparation::findOrFail($id);

        // Valider le nouveau statut
        $request->validate([
            'status' => 'required|in:en_attente,en_cours,termine,paye'
        ]);

        // Logique de transition des statuts
        $currentStatus = $repair->statut;
        $newStatus = $request->status;

        // Debug: afficher les valeurs
        error_log("=== UPDATE STATUS DEBUG ===");
        error_log("Réparation ID: {$id}");
        error_log("Statut actuel: '{$currentStatus}'");
        error_log("Nouveau statut: '{$newStatus}'");

        // Vérifier que la transition est valide
        if (!$this->isValidTransition($currentStatus, $newStatus)) {
            error_log("Transition INVALIDE!");
            return back()->with('error', 'Transition de statut non valide');
        }

        error_log("Transition VALIDE!");

        // Si on passe à "en_cours", vérifier la limite de 3 voitures
        if ($newStatus === 'en_cours') {
            $currentInProgress = Reparation::where('statut', 'en_cours')->count();
            if ($currentInProgress >= 3) {
                return back()->with('error', 'Le garage ne peut accueillir que 3 voitures en réparation en même temps');
            }
        }

        $repair->update(['statut' => $newStatus]);

        // Mettre à jour les dates si nécessaire
        if ($newStatus === 'en_cours' && !$repair->debut_reparation) {
            $repair->update(['debut_reparation' => now()]);
        } elseif ($newStatus === 'termine') {
            $repair->update(['fin_reparation' => now()]);
        }

        return back()->with('success', 'Statut mis à jour avec succès');
    }

    /**
     * Vérifie si une transition de statut est valide
     * 
     * @param string $current
     * @param string $new
     * @return bool
     */
    private function isValidTransition($current, $new)
    {
        $transitions = [
            'en_attente' => ['en_cours'],
            'en_cours' => ['termine'],
            'termine' => [], // Final state - admin ne gère pas les paiements
            'paye' => [] // Final state
        ];

        // Debug: afficher les valeurs
        error_log("Transition demandée: de '{$current}' vers '{$new}'");
        error_log("Transitions valides pour '{$current}': " . json_encode($transitions[$current] ?? []));

        $isValid = in_array($new, $transitions[$current] ?? []);
        error_log("Transition valide: " . ($isValid ? 'OUI' : 'NON'));

        return $isValid;
    }

    /**
     * Affiche le formulaire de création de réparation
     * 
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Vérifier la limite de 3 voitures
        $currentInProgress = Reparation::where('statut', 'en_cours')->count();
        if ($currentInProgress >= 3) {
            return back()->with('error', 'Le garage ne peut accueillir que 3 voitures en réparation en même temps');
        }

        $clients = Client::all();
        $interventions = Intervention::where('actif', true)->get();

        return view('admin.repairs.create', compact('clients', 'interventions'));
    }

    /**
     * Enregistre une nouvelle réparation
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Vérifier la limite de 3 voitures
        $currentInProgress = Reparation::where('statut', 'en_cours')->count();
        if ($currentInProgress >= 3) {
            return back()->with('error', 'Le garage ne peut accueillir que 3 voitures en réparation en même temps');
        }

        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'intervention_ids' => 'required|array',
            'intervention_ids.*' => 'exists:interventions,id',
        ]);

        // Récupérer l'intervention sélectionnée
        $intervention = Intervention::find($request->intervention_ids[0]);

        $repair = Reparation::create([
            'client_id' => $request->client_id,
            'intervention_id' => $request->intervention_ids[0], // Prendre la première intervention
            'statut' => 'en_attente',
            'slot' => $currentInProgress + 1,
            'montant_total' => $intervention->prix, // Utiliser le prix de l'intervention
        ]);

        return redirect()->route('admin.repairs.index')
            ->with('success', 'Réparation créée avec succès');
    }
}
