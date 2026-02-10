<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Vérification des calculs
$totalRevenue = App\Models\Reparation::whereIn('statut', ['termine', 'paye'])->sum('montant_total');
$totalPaid = App\Models\Reparation::where('statut', 'paye')->sum('montant_total');
$totalPending = App\Models\Reparation::whereIn('statut', ['en_attente', 'en_cours', 'termine'])->sum('montant_total');

echo "=== VÉRIFICATION DES CALCULS ===\n";
echo "Chiffre d'affaires (terminé + payé): " . number_format($totalRevenue, 0, ',', ' ') . " Ar\n";
echo "Paiements réglés (uniquement payé): " . number_format($totalPaid, 0, ',', ' ') . " Ar\n";
echo "Paiements en attente: " . number_format($totalPending, 0, ',', ' ') . " Ar\n";

$allReparations = App\Models\Reparation::all();
echo "\n=== DÉTAIL PAR STATUT ===\n";
foreach (['en_attente', 'en_cours', 'termine', 'paye'] as $status) {
    $count = $allReparations->where('statut', $status)->count();
    $sum = $allReparations->where('statut', $status)->sum('montant_total');
    echo $status . ': ' . $count . ' réparations, ' . number_format($sum, 0, ',', ' ') . " Ar\n";
}

echo "\n=== VÉRIFICATION LOGIQUE ===\n";
echo "Chiffre d'affaires (terminé + payé) = " . number_format($totalRevenue, 0, ',', ' ') . " Ar\n";
echo "Paiements réglés (payé) = " . number_format($totalPaid, 0, ',', ' ') . " Ar\n";
echo "Différence = " . number_format($totalRevenue - $totalPaid, 0, ',', ' ') . " Ar\n";
echo "Cette différence représente les réparations terminées mais non payées\n";
?>
