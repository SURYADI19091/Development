<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use ZipArchive;
use Exception;

class BackupController extends Controller
{
    /**
     * Display backup dashboard
     */
    public function index()
    {
        try {
            $backups = $this->getBackupFiles();
            $statistics = $this->getBackupStatistics($backups);
            
            return view('backend.pages.backup.index', compact('backups', 'statistics'));
        } catch (Exception $e) {
            Log::error('Backup index error: ' . $e->getMessage());
            return view('backend.pages.backup.index', [
                'backups' => [],
                'statistics' => [
                    'total_backups' => 0,
                    'last_backup' => null,
                    'total_size' => 0
                ]
            ]);
        }
    }

    /**
     * Create database backup
     */
    public function createBackup(Request $request)
    {
        try {
            $request->validate([
                'backup_types' => 'required|array',
                'backup_types.*' => 'in:users,locations,news,content,village,services,business,tourism,settings,system,all'
            ]);

            $backupTypes = $request->input('backup_types', []);
            $timestamp = Carbon::now()->format('Y-m-d_H-i-s');
            $filename = "backup_{$timestamp}.sql";
            
            // Create backup directory if not exists
            $backupPath = storage_path('app/backups');
            if (!file_exists($backupPath)) {
                mkdir($backupPath, 0755, true);
            }

            $fullPath = $backupPath . '/' . $filename;
            
            // Generate SQL backup based on selected types
            $sqlContent = $this->generateBackupSQL($backupTypes);
            
            // Save backup file
            file_put_contents($fullPath, $sqlContent);
            
            // Log backup creation
            Log::info("Backup created: {$filename}", [
                'types' => $backupTypes,
                'size' => filesize($fullPath),
                'user_id' => auth()->id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Backup berhasil dibuat!',
                'filename' => $filename,
                'size' => $this->formatFileSize(filesize($fullPath))
            ]);

        } catch (Exception $e) {
            Log::error('Backup creation error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat backup: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Download backup file
     */
    public function downloadBackup($filename)
    {
        try {
            $filePath = storage_path('app/backups/' . $filename);
            
            if (!file_exists($filePath)) {
                abort(404, 'File backup tidak ditemukan');
            }

            return response()->download($filePath);

        } catch (Exception $e) {
            Log::error('Backup download error: ' . $e->getMessage());
            abort(500, 'Gagal mengunduh backup');
        }
    }

    /**
     * Delete backup file
     */
    public function deleteBackup($filename)
    {
        try {
            $filePath = storage_path('app/backups/' . $filename);
            
            if (file_exists($filePath)) {
                unlink($filePath);
                
                Log::info("Backup deleted: {$filename}", [
                    'user_id' => auth()->id()
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Backup berhasil dihapus!'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'File backup tidak ditemukan'
            ], 404);

        } catch (Exception $e) {
            Log::error('Backup deletion error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus backup: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Restore from backup file
     */
    public function restoreBackup(Request $request)
    {
        try {
            $request->validate([
                'backup_file' => 'required|file|mimes:sql,zip|max:102400' // 100MB max
            ]);

            $file = $request->file('backup_file');
            $extension = $file->getClientOriginalExtension();
            
            // Save uploaded file temporarily
            $tempPath = storage_path('app/temp/restore_' . time() . '.' . $extension);
            $tempDir = dirname($tempPath);
            
            if (!file_exists($tempDir)) {
                mkdir($tempDir, 0755, true);
            }
            
            $file->move($tempDir, basename($tempPath));

            // Process restore based on file type
            if ($extension === 'sql') {
                $this->restoreFromSQL($tempPath);
            } elseif ($extension === 'zip') {
                $this->restoreFromZip($tempPath);
            }

            // Clean up temp file
            if (file_exists($tempPath)) {
                unlink($tempPath);
            }

            Log::info("Database restored from backup", [
                'filename' => $file->getClientOriginalName(),
                'user_id' => auth()->id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil dipulihkan dari backup!'
            ]);

        } catch (Exception $e) {
            Log::error('Backup restore error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal memulihkan data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get backup statistics
     */
    public function getStatistics()
    {
        try {
            $backups = $this->getBackupFiles();
            $statistics = $this->getBackupStatistics($backups);
            
            return response()->json([
                'success' => true,
                'data' => $statistics
            ]);

        } catch (Exception $e) {
            Log::error('Backup statistics error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat statistik backup'
            ], 500);
        }
    }

    /**
     * Get list of backup files
     */
    private function getBackupFiles()
    {
        $backupPath = storage_path('app/backups');
        $files = [];
        
        if (is_dir($backupPath)) {
            $fileList = scandir($backupPath);
            
            foreach ($fileList as $file) {
                if (in_array(pathinfo($file, PATHINFO_EXTENSION), ['sql', 'zip', 'gz'])) {
                    $fullPath = $backupPath . '/' . $file;
                    $files[] = [
                        'name' => $file,
                        'path' => $fullPath,
                        'size' => filesize($fullPath),
                        'created_at' => Carbon::createFromTimestamp(filemtime($fullPath)),
                        'type' => pathinfo($file, PATHINFO_EXTENSION)
                    ];
                }
            }
            
            // Sort by creation date, newest first
            usort($files, function($a, $b) {
                return $b['created_at']->timestamp - $a['created_at']->timestamp;
            });
        }
        
        return $files;
    }

    /**
     * Get backup statistics
     */
    private function getBackupStatistics($backups)
    {
        $totalSize = array_sum(array_column($backups, 'size'));
        $lastBackup = count($backups) > 0 ? $backups[0]['created_at'] : null;
        
        return [
            'total_backups' => count($backups),
            'last_backup' => $lastBackup,
            'total_size' => $totalSize,
            'total_size_formatted' => $this->formatFileSize($totalSize)
        ];
    }

    /**
     * Generate SQL backup content
     */
    private function generateBackupSQL($backupTypes)
    {
        $sql = "-- Database Backup Generated on " . Carbon::now()->format('Y-m-d H:i:s') . "\n";
        $sql .= "-- Backup Types: " . implode(', ', $backupTypes) . "\n\n";
        
        $sql .= "SET FOREIGN_KEY_CHECKS=0;\n\n";

        // Get all tables in database
        $allTables = $this->getAllTables();
        
        if (in_array('all', $backupTypes)) {
            // Backup all tables
            foreach ($allTables as $table) {
                $sql .= $this->getTableBackup($table);
            }
        } else {
            // Backup selected categories
            $tablesToBackup = $this->getTablesForCategories($backupTypes);
            
            foreach ($tablesToBackup as $table) {
                $sql .= $this->getTableBackup($table);
            }
        }

        $sql .= "\nSET FOREIGN_KEY_CHECKS=1;\n";
        
        return $sql;
    }

    /**
     * Get all tables in current database
     */
    public function getAllTables()
    {
        $database = config('database.connections.mysql.database');
        $tables = DB::select("SELECT TABLE_NAME as table_name FROM information_schema.tables WHERE table_schema = ?", [$database]);
        
        $tableNames = array_map(function($table) {
            // Convert object to array to handle different property access methods
            $tableArray = (array) $table;
            return isset($table->table_name) ? $table->table_name : $tableArray['table_name'];
        }, $tables);
        
        return collect($tableNames);
    }

    /**
     * Get tables for specific categories
     */
    private function getTablesForCategories($categories)
    {
        $tableCategories = [
            'users' => ['users', 'password_resets', 'password_reset_tokens', 'personal_access_tokens', 'sessions'],
            'locations' => ['locations', 'location_categories'],
            'news' => ['news', 'news_categories'],
            'content' => ['pages', 'announcements', 'banners', 'galleries', 'agendas'],
            'village' => ['village_profiles', 'village_officials', 'population_data', 'village_statistics', 'village_budgets', 'budget_transactions'],
            'services' => ['services', 'service_categories', 'letter_requests', 'letter_templates'],
            'business' => ['umkms', 'umkm_categories'],
            'tourism' => ['tourism_objects', 'tourism_categories'],
            'settings' => ['roles', 'permissions', 'role_permissions', 'user_permissions'],
            'system' => ['migrations', 'failed_jobs', 'jobs', 'cache', 'cache_locks']
        ];

        $tables = [];
        foreach ($categories as $category) {
            if (isset($tableCategories[$category])) {
                $tables = array_merge($tables, $tableCategories[$category]);
            }
        }

        // Remove duplicates and ensure tables exist
        $tables = array_unique($tables);
        $existingTables = $this->getAllTables()->toArray();
        
        return array_intersect($tables, $existingTables);
    }

    /**
     * Get table backup SQL
     */
    public function getTableBackup($tableName)
    {
        try {
            // Check if table exists using INFORMATION_SCHEMA
            $database = config('database.connections.mysql.database');
            $tableExists = DB::select("SELECT table_name FROM information_schema.tables WHERE table_schema = ? AND table_name = ?", [$database, $tableName]);
            
            if (empty($tableExists)) {
                Log::info("Table {$tableName} does not exist, skipping backup");
                return "-- Table {$tableName} does not exist\n\n";
            }

            $sql = "-- Table: {$tableName}\n";
            $sql .= "DROP TABLE IF EXISTS `{$tableName}`;\n";
            
            // Get table structure
            $createTable = DB::select("SHOW CREATE TABLE `{$tableName}`");
            if (!empty($createTable)) {
                $sql .= $createTable[0]->{'Create Table'} . ";\n\n";
            }
            
            // Get row count
            $rowCount = DB::table($tableName)->count();
            
            if ($rowCount > 0) {
                $sql .= "-- Dumping data for table `{$tableName}` ({$rowCount} rows)\n";
                $sql .= "LOCK TABLES `{$tableName}` WRITE;\n";
                $sql .= "/*!40000 ALTER TABLE `{$tableName}` DISABLE KEYS */;\n";
                
                // Process data in chunks to avoid memory issues
                $chunkSize = 1000;
                $chunks = ceil($rowCount / $chunkSize);
                
                for ($i = 0; $i < $chunks; $i++) {
                    $offset = $i * $chunkSize;
                    $data = DB::table($tableName)->offset($offset)->limit($chunkSize)->get();
                    
                    if ($data->count() > 0) {
                        $sql .= "INSERT INTO `{$tableName}` VALUES\n";
                        $values = [];
                        
                        foreach ($data as $row) {
                            $rowData = array_map(function($value) {
                                if (is_null($value)) {
                                    return 'NULL';
                                } elseif (is_numeric($value)) {
                                    return $value;
                                } else {
                                    return "'" . addslashes($value) . "'";
                                }
                            }, (array) $row);
                            
                            $values[] = '(' . implode(',', $rowData) . ')';
                        }
                        
                        $sql .= implode(",\n", $values) . ";\n";
                    }
                }
                
                $sql .= "/*!40000 ALTER TABLE `{$tableName}` ENABLE KEYS */;\n";
                $sql .= "UNLOCK TABLES;\n\n";
            } else {
                $sql .= "-- Table `{$tableName}` is empty\n\n";
            }
            
            return $sql;
            
        } catch (Exception $e) {
            Log::warning("Failed to backup table {$tableName}: " . $e->getMessage());
            return "-- Failed to backup table: {$tableName} - Error: " . $e->getMessage() . "\n\n";
        }
    }

    /**
     * Get backup preview information
     */
    public function getBackupPreview(Request $request)
    {
        try {
            $request->validate([
                'backup_types' => 'required|array',
                'backup_types.*' => 'in:users,locations,news,content,village,services,business,tourism,settings,system,all'
            ]);

            $backupTypes = $request->input('backup_types', []);
            
            if (in_array('all', $backupTypes)) {
                $tables = $this->getAllTables();
            } else {
                $tables = $this->getTablesForCategories($backupTypes);
            }

            $preview = [];
            $totalRows = 0;

            foreach ($tables as $table) {
                try {
                    $count = DB::table($table)->count();
                    $preview[] = [
                        'table' => $table,
                        'rows' => $count,
                        'size_estimate' => $this->estimateTableSize($table)
                    ];
                    $totalRows += $count;
                } catch (Exception $e) {
                    $preview[] = [
                        'table' => $table,
                        'rows' => 0,
                        'error' => $e->getMessage()
                    ];
                }
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'tables' => $preview,
                    'total_tables' => count($tables),
                    'total_rows' => $totalRows,
                    'estimated_time' => $this->estimateBackupTime($totalRows)
                ]
            ]);

        } catch (Exception $e) {
            Log::error('Backup preview error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat preview backup: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Estimate table size in KB
     */
    private function estimateTableSize($tableName)
    {
        try {
            $database = config('database.connections.mysql.database');
            $result = DB::select("
                SELECT 
                    ROUND(((data_length + index_length) / 1024), 2) AS size_kb
                FROM information_schema.TABLES 
                WHERE table_schema = ? AND table_name = ?
            ", [$database, $tableName]);
            
            return $result[0]->size_kb ?? 0;
        } catch (Exception $e) {
            return 0;
        }
    }

    /**
     * Estimate backup time based on row count
     */
    private function estimateBackupTime($totalRows)
    {
        // Rough estimate: 1000 rows per second
        $seconds = max(1, ceil($totalRows / 1000));
        
        if ($seconds < 60) {
            return $seconds . ' detik';
        } elseif ($seconds < 3600) {
            return ceil($seconds / 60) . ' menit';
        } else {
            return ceil($seconds / 3600) . ' jam';
        }
    }

    /**
     * Restore from SQL file
     */
    private function restoreFromSQL($filePath)
    {
        $sql = file_get_contents($filePath);
        
        // Split SQL into individual statements
        $statements = array_filter(
            array_map('trim', explode(';', $sql)),
            function($statement) {
                return !empty($statement) && !str_starts_with($statement, '--');
            }
        );
        
        DB::transaction(function() use ($statements) {
            foreach ($statements as $statement) {
                if (!empty(trim($statement))) {
                    DB::unprepared($statement);
                }
            }
        });
    }

    /**
     * Restore from ZIP file
     */
    private function restoreFromZip($filePath)
    {
        $zip = new ZipArchive;
        
        if ($zip->open($filePath) === TRUE) {
            $tempDir = storage_path('app/temp/extracted_' . time());
            mkdir($tempDir, 0755, true);
            
            $zip->extractTo($tempDir);
            $zip->close();
            
            // Look for SQL files in extracted content
            $sqlFiles = glob($tempDir . '/*.sql');
            
            foreach ($sqlFiles as $sqlFile) {
                $this->restoreFromSQL($sqlFile);
            }
            
            // Clean up extracted files
            $this->deleteDirectory($tempDir);
        } else {
            throw new Exception('Gagal membuka file ZIP');
        }
    }

    /**
     * Format file size
     */
    private function formatFileSize($size)
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $unitIndex = 0;
        
        while ($size >= 1024 && $unitIndex < count($units) - 1) {
            $size /= 1024;
            $unitIndex++;
        }
        
        return round($size, 2) . ' ' . $units[$unitIndex];
    }

    /**
     * Delete directory recursively
     */
    private function deleteDirectory($dir)
    {
        if (is_dir($dir)) {
            $files = array_diff(scandir($dir), ['.', '..']);
            
            foreach ($files as $file) {
                $filePath = $dir . '/' . $file;
                is_dir($filePath) ? $this->deleteDirectory($filePath) : unlink($filePath);
            }
            
            rmdir($dir);
        }
    }

}