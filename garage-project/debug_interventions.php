<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

$kernel->bootstrap();

echo "=== DEBUG INTERVENTIONS ===\n";

$interventions = DB::table('interventions')->get();

foreach ($interventions as $intervention) {
    echo "ID: {$intervention->id} | Nom: {$intervention->nom} | Prix: " . number_format($intervention->prix, 0, ',', ' ') . " Ar | Actif: " . ($intervention->actif ? 'OUI' : 'NON') . "\n";
}

echo "\n=== FIN DEBUG ===\n";
