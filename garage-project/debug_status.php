<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

$kernel->bootstrap();

echo "=== DEBUG STATUTS RÉPARATIONS ===\n";

$stats = DB::table('reparations')
    ->select('statut', DB::raw('COUNT(*) as count'))
    ->groupBy('statut')
    ->get();

foreach ($stats as $stat) {
    echo "Statut: {$stat->statut} | Count: {$stat->count}\n";
}

echo "\n=== DÉTAIL RÉPARATIONS PAYÉES ===\n";
$payees = DB::table('reparations')
    ->join('clients', 'reparations.client_id', '=', 'clients.id')
    ->join('interventions', 'reparations.intervention_id', '=', 'interventions.id')
    ->select('reparations.id', 'clients.nom', 'interventions.nom as intervention_nom', 'reparations.montant_total')
    ->where('reparations.statut', 'paye')
    ->limit(5)
    ->get();

foreach ($payees as $repair) {
    echo "ID: {$repair->id} | Client: {$repair->nom} | Intervention: {$repair->intervention_nom} | Montant: " . number_format($repair->montant_total, 0, ',', ' ') . " Ar\n";
}

echo "\n=== FIN DEBUG ===\n";
