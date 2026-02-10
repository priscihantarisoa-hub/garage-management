<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Intervention;

class InterventionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $interventions = [
            [
                'nom' => 'Changement des plaquettes de frein',
                'type' => 'frein',
                'description' => 'Remplacement des plaquettes de frein avant et/ou arrière',
                'prix' => 89.99 * 4950, // 89.99 EUR en Ariary
                'duree' => 1800, // 30 minutes
                'actif' => true,
            ],
            [
                'nom' => 'Vidange moteur',
                'type' => 'vidange',
                'description' => 'Vidange complète avec filtre à huile',
                'prix' => 59.99 * 4950, // 59.99 EUR en Ariary
                'duree' => 1200, // 20 minutes
                'actif' => true,
            ],
            [
                'nom' => 'Remplacement filtre à air',
                'type' => 'filtre',
                'description' => 'Changement du filtre à air du moteur',
                'prix' => 29.99 * 4950, // 29.99 EUR en Ariary
                'duree' => 600, // 10 minutes
                'actif' => true,
            ],
            [
                'nom' => 'Changement batterie',
                'type' => 'batterie',
                'description' => 'Remplacement de la batterie et recalibrage système',
                'prix' => 129.99 * 4950, // 129.99 EUR en Ariary
                'duree' => 900, // 15 minutes
                'actif' => true,
            ],
            [
                'nom' => 'Remplacement amortisseurs',
                'type' => 'amortisseurs',
                'description' => 'Changement des amortisseurs avant ou arrière',
                'prix' => 249.99 * 4950, // 249.99 EUR en Ariary
                'duree' => 3600, // 1 heure
                'actif' => true,
            ],
            [
                'nom' => 'Remplacement embrayage',
                'type' => 'embrayage',
                'description' => 'Changement complet du kit d\'embrayage',
                'prix' => 449.99 * 4950, // 449.99 EUR en Ariary
                'duree' => 5400, // 1h30
                'actif' => true,
            ],
            [
                'nom' => 'Montage pneus',
                'type' => 'pneus',
                'description' => 'Montage et équilibrage de 4 pneus',
                'prix' => 79.99 * 4950, // 79.99 EUR en Ariary
                'duree' => 2400, // 40 minutes
                'actif' => true,
            ],
            [
                'nom' => 'Vidange système refroidissement',
                'type' => 'refroidissement',
                'description' => 'Vidange du liquide de refroidissement et contrôle du circuit',
                'prix' => 99.99 * 4950, // 99.99 EUR en Ariary
                'duree' => 1500, // 25 minutes
                'actif' => true,
            ],
        ];

        foreach ($interventions as $intervention) {
            Intervention::create($intervention);
        }
    }
}
