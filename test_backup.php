<?php

require 'vendor/autoload.php';

$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Http\Request;
use App\Http\Controllers\Backend\BackupController;

echo "=== Test Backup Settings Category ===\n";

try {
    $controller = new BackupController();
    
    // Create request for settings backup
    $request = new Request();
    $request->merge([
        'backup_types' => ['settings']
    ]);
    
    echo "Creating backup with settings category...\n";
    $response = $controller->createBackup($request);
    
    if ($response->isSuccessful()) {
        echo "✅ Backup created successfully!\n";
        $content = json_decode($response->getContent(), true);
        echo "Backup file: " . ($content['filename'] ?? 'Unknown') . "\n";
        echo "Success: " . ($content['success'] ? 'Yes' : 'No') . "\n";
        echo "Message: " . ($content['message'] ?? 'No message') . "\n";
    } else {
        echo "❌ Backup failed!\n";
        echo "Response: " . $response->getContent() . "\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}