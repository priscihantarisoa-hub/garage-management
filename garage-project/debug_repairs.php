<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

$kernel->bootstrap();

echo "=== DEBUG RÉPARATIONS ===\n";

$repairs = DB::table('reparations')->get();

foreach ($repairs as $repair) {
    echo "ID: {$repair->id} | Statut: {$repair->statut} | Client: {$repair->client_id} | Intervention: {$repair->intervention_id}\n";
}

echo "\n=== TRANSITIONS VALIDES ===\n";

$transitions = [
    'en_attente' => ['en_cours'],
    'en_cours' => ['termine'],
    'termine' => ['paye'],
    'paye' => []
];

foreach ($transitions as $from => $to) {
    echo "De '{$from}' vers: " . implode(', ', $to) . "\n";
}

echo "\n=== FIN DEBUG ===\n";
