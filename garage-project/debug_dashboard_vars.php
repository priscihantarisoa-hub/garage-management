<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

$kernel->bootstrap();

echo "=== DEBUG DASHBOARD VARIABLES ===\n";

// Simuler les variables du DashboardController
$totalReparations = DB::table('reparations')->count();
$clientsCount = DB::table('clients')->count();
$totalRevenue = DB::table('reparations')->where('statut', 'paye')->sum('montant_total');
$totalPending = DB::table('reparations')->where('statut', 'termine')->sum('montant_total');
$reparationsAujourdHui = DB::table('reparations')->whereDate('created_at', today())->where('statut', 'termine')->count();

$reparationsEnCours = DB::table('reparations')->whereIn('statut', ['en_attente', 'en_cours'])->limit(2)->get();
$reparationsTerminees = DB::table('reparations')
    ->join('clients', 'reparations.client_id', '=', 'clients.id')
    ->join('interventions', 'reparations.intervention_id', '=', 'interventions.id')
    ->select('reparations.*', 'clients.nom as client_nom', 'clients.prenom as client_prenom', 'interventions.nom as intervention_nom')
    ->where('reparations.statut', 'termine')
    ->orderBy('reparations.created_at', 'desc')
    ->limit(5)
    ->get();

$interventionsPopulaires = DB::table('reparations')
    ->join('interventions', 'reparations.intervention_id', '=', 'interventions.id')
    ->select('interventions.nom as intervention_nom', DB::raw('COUNT(*) as count'))
    ->groupBy('interventions.id', 'interventions.nom')
    ->orderByDesc('count')
    ->limit(5)
    ->get();

echo "Total réparations: {$totalReparations}\n";
echo "Nombre de clients: {$clientsCount}\n";
echo "Revenus totaux (payé): " . number_format($totalRevenue, 0, ',', ' ') . " Ar\n";
echo "En attente (terminé): " . number_format($totalPending, 0, ',', ' ') . " Ar\n";
echo "Réparations aujourd'hui: {$reparationsAujourdHui}\n";

echo "\nRéparations en cours ({$reparationsEnCours->count()}):\n";
foreach ($reparationsEnCours as $repair) {
    echo "- ID: {$repair->id} | Statut: {$repair->statut} | Montant: " . number_format($repair->montant_total, 0, ',', ' ') . " Ar\n";
}

echo "\nRéparations terminées ({$reparationsTerminees->count()}):\n";
foreach ($reparationsTerminees as $repair) {
    echo "- ID: {$repair->id} | Client: {$repair->client_nom} | Montant: " . number_format($repair->montant_total, 0, ',', ' ') . " Ar\n";
}

echo "\nInterventions populaires ({$interventionsPopulaires->count()}):\n";
foreach ($interventionsPopulaires as $item) {
    echo "- {$item->intervention_nom}: {$item->count}\n";
}

echo "\n=== FIN DEBUG ===\n";
