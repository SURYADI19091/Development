<?php

require 'vendor/autoload.php';

$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== Debug Settings Table ===\n";

try {
    // Check if settings table exists
    $tableExists = DB::getSchemaBuilder()->hasTable('settings');
    echo "Settings table exists: " . ($tableExists ? 'YES' : 'NO') . "\n";
    
    if ($tableExists) {
        // Get table structure
        $columns = DB::select('DESCRIBE settings');
        echo "Table structure:\n";
        foreach ($columns as $column) {
            echo "- {$column->Field} ({$column->Type})\n";
        }
        
        // Get record count
        $count = DB::table('settings')->count();
        echo "Record count: {$count}\n";
        
        // Try to get first few records
        $records = DB::table('settings')->limit(5)->get();
        echo "First 5 records:\n";
        foreach ($records as $record) {
            echo "  ID: {$record->id}\n";
        }
        
    } else {
        echo "Settings table does not exist!\n";
        
        // Show all tables
        echo "\nAll tables in database:\n";
        $tables = DB::select('SHOW TABLES');
        foreach ($tables as $table) {
            $tableName = array_values((array)$table)[0];
            echo "- {$tableName}\n";
        }
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}

// Test backup controller method directly
echo "\n=== Testing Backup Controller ===\n";

try {
    $backupController = new App\Http\Controllers\Backend\BackupController();
    
    // Test getAllTables method
    $tables = $backupController->getAllTables();
    echo "Tables from getAllTables(): " . $tables->count() . "\n";
    
    if ($tables->contains('permissions')) {
        echo "Permissions table found in getAllTables()\n";
        
        // Test getTableBackup method specifically for permissions (since settings doesn't exist)
        $permissionsBackup = $backupController->getTableBackup('permissions');
        echo "Permissions backup result length: " . strlen($permissionsBackup) . "\n";
        echo "First 200 chars of backup:\n" . substr($permissionsBackup, 0, 200) . "\n";
        
    } else {
        echo "Permissions table NOT found in getAllTables()\n";
        echo "Available tables:\n";
        foreach ($tables as $table) {
            echo "- {$table}\n";
        }
    }
    
    // Test backup for non-existent settings table
    echo "\nTesting backup for non-existent 'settings' table:\n";
    $settingsBackup = $backupController->getTableBackup('settings');
    echo "Settings backup result:\n" . $settingsBackup . "\n";
    
} catch (Exception $e) {
    echo "Backup Controller Error: " . $e->getMessage() . "\n";
}