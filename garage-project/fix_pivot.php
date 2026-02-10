<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

$kernel->bootstrap();

try {
    // Create repair_intervention table with correct structure
    DB::statement('CREATE TABLE repair_intervention (
        reparation_id BIGINT UNSIGNED NOT NULL,
        intervention_id BIGINT UNSIGNED NOT NULL,
        status ENUM("pending", "in_progress", "completed") DEFAULT "pending",
        created_at TIMESTAMP NULL,
        updated_at TIMESTAMP NULL,
        PRIMARY KEY (reparation_id, intervention_id),
        FOREIGN KEY (reparation_id) REFERENCES reparations(id) ON DELETE CASCADE,
        FOREIGN KEY (intervention_id) REFERENCES interventions(id) ON DELETE CASCADE
    )');
    
    echo "✅ Table repair_intervention created successfully!\n";
    
    // Add some test data
    DB::table('repair_intervention')->insert([
        [
            'reparation_id' => 1,
            'intervention_id' => 1,
            'status' => 'in_progress',
            'created_at' => now(),
            'updated_at' => now()
        ],
        [
            'reparation_id' => 2,
            'intervention_id' => 2,
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now()
        ],
        [
            'reparation_id' => 3,
            'intervention_id' => 3,
            'status' => 'completed',
            'created_at' => now(),
            'updated_at' => now()
        ]
    ]);
    
    echo "✅ Test data added to repair_intervention!\n";
    echo "🎉 Pivot table setup complete!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
