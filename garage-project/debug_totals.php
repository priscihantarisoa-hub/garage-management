<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

$kernel->bootstrap();

echo "=== DEBUG MONTANTS TOTAUX ===\n";

$repairs = DB::table('reparations')->get();

foreach ($repairs as $repair) {
    echo "ID: {$repair->id} | Statut: {$repair->statut} | Montant: " . number_format($repair->montant_total, 0, ',', ' ') . " Ar\n";
}

echo "\n=== INTERVENTIONS CORRESPONDANTES ===\n";

foreach ($repairs as $repair) {
    $intervention = DB::table('interventions')->where('id', $repair->intervention_id)->first();
    if ($intervention) {
        echo "Réparation {$repair->id} -> Intervention {$intervention->id} ({$intervention->nom}): " . number_format($intervention->prix, 0, ',', ' ') . " Ar\n";
    }
}

echo "\n=== FIN DEBUG ===\n";
