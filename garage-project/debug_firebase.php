<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

$kernel->bootstrap();

echo "=== DEBUG FIREBASE ===\n";

// Check environment variables
echo "FIREBASE_PROJECT_ID: " . env('FIREBASE_PROJECT_ID', 'NOT SET') . "\n";
echo "FIREBASE_CLIENT_EMAIL: " . env('FIREBASE_CLIENT_EMAIL', 'NOT SET') . "\n";
echo "FIREBASE_PRIVATE_KEY_ID: " . env('FIREBASE_PRIVATE_KEY_ID', 'NOT SET') . "\n";
echo "FIREBASE_DATABASE_URL: " . env('FIREBASE_DATABASE_URL', 'NOT SET') . "\n";

// Check private key format
$privateKey = env('FIREBASE_PRIVATE_KEY', '');
if (empty($privateKey)) {
    echo "❌ FIREBASE_PRIVATE_KEY is empty\n";
} else {
    echo "✅ FIREBASE_PRIVATE_KEY length: " . strlen($privateKey) . " chars\n";
    if (strpos($privateKey, 'BEGIN PRIVATE KEY') === false) {
        echo "❌ FIREBASE_PRIVATE_KEY format invalid (missing BEGIN PRIVATE KEY)\n";
    } else {
        echo "✅ FIREBASE_PRIVATE_KEY format looks valid\n";
    }
}

try {
    // Try simple Firebase connection
    $serviceAccount = [
        'type' => 'service_account',
        'project_id' => env('FIREBASE_PROJECT_ID'),
        'private_key_id' => env('FIREBASE_PRIVATE_KEY_ID'),
        'private_key' => env('FIREBASE_PRIVATE_KEY'),
        'client_email' => env('FIREBASE_CLIENT_EMAIL'),
        'client_id' => env('FIREBASE_CLIENT_ID'),
        'auth_uri' => env('FIREBASE_AUTH_URI'),
        'token_uri' => env('FIREBASE_TOKEN_URI'),
        'auth_provider_x509_cert_url' => env('FIREBASE_AUTH_PROVIDER_X509_CERT_URL'),
        'client_x509_cert_url' => env('FIREBASE_CLIENT_X509_CERT_URL'),
    ];

    $firebase = (new Kreait\Firebase\Factory())
        ->withServiceAccount($serviceAccount)
        ->withDatabaseUri(env('FIREBASE_DATABASE_URL'));

    $database = $firebase->createDatabase();
    
    // Test simple write/read
    $testRef = $database->getReference('test_connection');
    $testRef->set(['timestamp' => now()->toISOString()]);
    $result = $testRef->getValue();
    
    echo "✅ Firebase connection successful!\n";
    echo "📊 Test result: " . json_encode($result) . "\n";
    
} catch (Exception $e) {
    echo "❌ Firebase connection failed: " . $e->getMessage() . "\n";
    echo "📍 File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}

echo "\n=== END DEBUG ===\n";
