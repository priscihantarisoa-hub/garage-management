<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

$kernel->bootstrap();

try {
    // Test database connection
    $tables = DB::select('SHOW TABLES');
    echo "✅ Database connected successfully!\n";
    echo "📋 Tables found:\n";
    foreach ($tables as $table) {
        foreach ($table as $key => $value) {
            echo "  - " . $value . "\n";
        }
    }
    
    // Test sessions table
    $sessions = DB::table('sessions')->count();
    echo "✅ Sessions table exists with $sessions records\n";
    
    // Test interventions
    $interventions = DB::table('interventions')->count();
    echo "✅ Interventions table exists with $interventions records\n";
    
    echo "\n🎉 Database setup is complete!\n";
    echo "🌐 You can now access: http://localhost:8000\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
