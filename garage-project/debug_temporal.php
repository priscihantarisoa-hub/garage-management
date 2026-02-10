<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

$kernel->bootstrap();

echo "=== DEBUG STATISTIQUES TEMPORELLES ===\n";

// Année
$revenuesYear = DB::table('reparations')
    ->where('statut', 'paye')
    ->whereYear('updated_at', now()->year)
    ->sum('montant_total');
    
$repairsYear = DB::table('reparations')
    ->whereYear('created_at', now()->year)
    ->count();

echo "Année " . now()->year . ":\n";
echo "  Réparations: {$repairsYear}\n";
echo "  Revenus: " . number_format($revenuesYear, 0, ',', ' ') . " Ar\n";

// Mois
$revenuesMonth = DB::table('reparations')
    ->where('statut', 'paye')
    ->whereMonth('updated_at', now()->month)
    ->whereYear('updated_at', now()->year)
    ->sum('montant_total');
    
$repairsMonth = DB::table('reparations')
    ->whereMonth('created_at', now()->month)
    ->whereYear('created_at', now()->year)
    ->count();

echo "\nMois " . now()->format('F Y') . ":\n";
echo "  Réparations: {$repairsMonth}\n";
echo "  Revenus: " . number_format($revenuesMonth, 0, ',', ' ') . " Ar\n";

// Semaine
$revenuesWeek = DB::table('reparations')
    ->where('statut', 'paye')
    ->whereBetween('updated_at', [now()->startOfWeek(), now()->endOfWeek()])
    ->sum('montant_total');
    
$repairsWeek = DB::table('reparations')
    ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
    ->count();

echo "\nSemaine " . now()->weekOfYear . ":\n";
echo "  Réparations: {$repairsWeek}\n";
echo "  Revenus: " . number_format($revenuesWeek, 0, ',', ' ') . " Ar\n";

echo "\n=== FIN DEBUG ===\n";
