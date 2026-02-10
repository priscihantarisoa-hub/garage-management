<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

$kernel->bootstrap();

echo "=== DEBUG DASHBOARD DATA ===\n";

// Statistiques par statut
echo "\n--- Réparations par statut ---\n";
$stats = DB::table('reparations')
    ->select('statut', DB::raw('COUNT(*) as count'), DB::raw('SUM(montant_total) as total'))
    ->groupBy('statut')
    ->get();

foreach ($stats as $stat) {
    echo "Statut: {$stat->statut} | Count: {$stat->count} | Total: " . number_format($stat->total, 0, ',', ' ') . " Ar\n";
}

// Interventions populaires
echo "\n--- Interventions populaires ---\n";
$popular = DB::table('reparations')
    ->join('interventions', 'reparations.intervention_id', '=', 'interventions.id')
    ->select('interventions.nom', DB::raw('COUNT(*) as count'))
    ->groupBy('interventions.id', 'interventions.nom')
    ->orderByDesc('count')
    ->limit(5)
    ->get();

foreach ($popular as $item) {
    echo "Intervention: {$item->nom} | Count: {$item->count}\n";
}

// Total revenue
echo "\n--- Revenus totaux ---\n";
$totalRevenue = DB::table('reparations')->where('statut', 'paye')->sum('montant_total');
$totalPending = DB::table('reparations')->where('statut', 'termine')->sum('montant_total');

echo "Revenus (payé): " . number_format($totalRevenue, 0, ',', ' ') . " Ar\n";
echo "En attente (terminé): " . number_format($totalPending, 0, ',', ' ') . " Ar\n";

echo "\n=== FIN DEBUG ===\n";
