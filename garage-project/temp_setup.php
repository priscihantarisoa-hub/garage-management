<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

$kernel->bootstrap();

// Create reparations table
try {
    DB::statement('CREATE TABLE reparations (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
        client_id BIGINT UNSIGNED NOT NULL, 
        intervention_id BIGINT UNSIGNED NOT NULL, 
        statut ENUM("en_attente", "en_cours", "termine", "paye") DEFAULT "en_attente", 
        slot INT NULL, 
        debut_reparation TIMESTAMP NULL, 
        fin_reparation TIMESTAMP NULL, 
        montant_total DECIMAL(8,2) NOT NULL, 
        created_at TIMESTAMP NULL, 
        updated_at TIMESTAMP NULL, 
        FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE CASCADE, 
        FOREIGN KEY (intervention_id) REFERENCES interventions(id) ON DELETE CASCADE
    )');
    
    echo "Table reparations created successfully!\n";
} catch (Exception $e) {
    echo "Error creating reparations table: " . $e->getMessage() . "\n";
}

// Create pivot table
try {
    DB::statement('CREATE TABLE repair_intervention (
        repair_id BIGINT UNSIGNED NOT NULL,
        intervention_id BIGINT UNSIGNED NOT NULL,
        status ENUM("pending", "in_progress", "completed") DEFAULT "pending",
        created_at TIMESTAMP NULL,
        updated_at TIMESTAMP NULL,
        PRIMARY KEY (repair_id, intervention_id),
        FOREIGN KEY (repair_id) REFERENCES reparations(id) ON DELETE CASCADE,
        FOREIGN KEY (intervention_id) REFERENCES interventions(id) ON DELETE CASCADE
    )');
    
    echo "Table repair_intervention created successfully!\n";
} catch (Exception $e) {
    echo "Error creating repair_intervention table: " . $e->getMessage() . "\n";
}

echo "Database setup completed!\n";
