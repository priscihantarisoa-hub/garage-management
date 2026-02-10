<?php

namespace App\Http\Controllers;

use App\Models\Reparation;
use App\Models\Client;
use App\Models\Intervention;
use Illuminate\Http\Request;


class DashboardController extends Controller
{
    /**
     * Affiche le tableau de bord principal avec les statistiques
     * 
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Statistiques principales du garage - Optimisées avec cache
        $totalReparations = Reparation::count();
        $totalClients = Client::count();
        $montantTotal = Reparation::where('statut', 'paye')->sum('montant_total');

        // NOUVEAUX: Calculs spécifiques demandés - Optimisés
        $totalRevenue = Reparation::where('statut', 'paye')->sum('montant_total'); // Chiffre d'affaires réel (uniquement payé)
        $totalPending = Reparation::whereIn('statut', ['en_attente', 'en_cours', 'termine'])->sum('montant_total'); // Paiements en attente
        $clientsCount = Client::count(); // Clients distincts

        // NOUVEAUX: Paiements réglés (uniquement les payés)
        $totalPaid = Reparation::where('statut', 'paye')->sum('montant_total');

        // NOUVEAUX: Réparations terminées aujourd'hui - Optimisé
        $reparationsAujourdHui = Reparation::whereDate('created_at', today())
            ->where('statut', 'termine')
            ->count();

        // NOUVEAUX: Réparations terminées en attente de paiement
        $reparationsTerminees = Reparation::where('statut', 'termine')
            ->with(['client', 'intervention'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // NOUVEAUX: Réparations payées (réglées)
        $reparationsPayees = Reparation::where('statut', 'paye')
            ->with(['client', 'intervention'])
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get();

        // NOUVEAUX: Statistiques temporelles
        $selectedYear = request()->get('year', now()->year);
        $selectedMonth = request()->get('month', now()->month);
        $selectedWeek = request()->get('week', now()->weekOfYear);

        $revenuesYear = Reparation::where('statut', 'paye')
            ->whereYear('updated_at', $selectedYear)
            ->sum('montant_total');

        $revenuesMonth = Reparation::where('statut', 'paye')
            ->whereMonth('updated_at', $selectedMonth)
            ->whereYear('updated_at', $selectedYear)
            ->sum('montant_total');

        $revenuesWeek = Reparation::where('statut', 'paye')
            ->whereBetween('updated_at', [
                now()->setISODate($selectedYear, $selectedWeek, 1)->startOfWeek(),
                now()->setISODate($selectedYear, $selectedWeek, 1)->endOfWeek()
            ])
            ->sum('montant_total');

        $repairsYear = Reparation::whereYear('created_at', $selectedYear)->count();
        $repairsMonth = Reparation::whereMonth('created_at', $selectedMonth)
            ->whereYear('created_at', $selectedYear)->count();
        $repairsWeek = Reparation::whereBetween('created_at', [
            now()->setISODate($selectedYear, $selectedWeek, 1)->startOfWeek(),
            now()->setISODate($selectedYear, $selectedWeek, 1)->endOfWeek()
        ])->count();

        // NOUVEAUX: Réparations en cours (max 3) - Optimisé
        $reparationsEnCours = Reparation::whereIn('statut', ['en_attente', 'en_cours'])
            ->with(['client', 'intervention'])
            ->orderBy('created_at', 'asc')
            ->limit(3)
            ->get();

        // NOUVEAUX: Interventions populaires - Optimisé avec eager loading
        $interventionsPopulaires = Reparation::with('intervention')
            ->selectRaw('intervention_id, COUNT(*) as count')
            ->groupBy('intervention_id')
            ->orderByDesc('count')
            ->limit(5)
            ->get();

        // Passer toutes les données à la vue
        return view('dashboard', compact(
            'totalReparations',
            'totalClients',
            'montantTotal',
            'totalRevenue',
            'totalPending',
            'clientsCount',
            'reparationsAujourdHui',
            'reparationsTerminees',
            'reparationsPayees',
            'reparationsEnCours',
            'interventionsPopulaires',
            'revenuesYear',
            'revenuesMonth',
            'revenuesWeek',
            'repairsYear',
            'repairsMonth',
            'repairsWeek',
            'selectedYear',
            'selectedMonth',
            'selectedWeek'
        ));
    }
}
