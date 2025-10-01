<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class MaintenanceController extends Controller
{
    public function backup()
    {
        $backupPath = storage_path('app/backups');
        $backups = [];
        
        if (File::exists($backupPath)) {
            $files = File::files($backupPath);
            foreach ($files as $file) {
                $backups[] = [
                    'name' => $file->getFilename(),
                    'size' => $file->getSize(),
                    'size_human' => $this->formatBytes($file->getSize()),
                    'created_at' => date('Y-m-d H:i:s', $file->getMTime()),
                    'path' => $file->getPathname()
                ];
            }
            
            // Sort by creation time, newest first
            usort($backups, function($a, $b) {
                return strcmp($b['created_at'], $a['created_at']);
            });
        }
        
        return view('backend.maintenance.backup', compact('backups'));
    }
    
    public function createBackup(Request $request)
    {
        $request->validate([
            'type' => 'required|in:database,files,full',
            'description' => 'nullable|string|max:255'
        ]);
        
        try {
            $backupPath = storage_path('app/backups');
            if (!File::exists($backupPath)) {
                File::makeDirectory($backupPath, 0755, true);
            }
            
            $timestamp = date('Y-m-d_H-i-s');
            $type = $request->type;
            $description = $request->description ?? 'Backup otomatis';
            
            switch ($type) {
                case 'database':
                    $this->createDatabaseBackup($timestamp, $description);
                    break;
                    
                case 'files':
                    $this->createFilesBackup($timestamp, $description);
                    break;
                    
                case 'full':
                    $this->createFullBackup($timestamp, $description);
                    break;
            }
            
            return redirect()->route('admin.backup.index')
                           ->with('success', "Backup {$type} berhasil dibuat.");
                           
        } catch (\Exception $e) {
            return redirect()->route('admin.backup.index')
                           ->with('error', 'Gagal membuat backup: ' . $e->getMessage());
        }
    }
    
    private function createDatabaseBackup($timestamp, $description)
    {
        $filename = "database_backup_{$timestamp}.sql";
        $filepath = storage_path("app/backups/{$filename}");
        
        $command = sprintf(
            'mysqldump --user=%s --password=%s --host=%s %s > %s',
            config('database.connections.mysql.username'),
            config('database.connections.mysql.password'),
            config('database.connections.mysql.host'),
            config('database.connections.mysql.database'),
            $filepath
        );
        
        exec($command);
        
        // Create info file
        $info = [
            'type' => 'database',
            'description' => $description,
            'created_at' => date('Y-m-d H:i:s'),
            'database' => config('database.connections.mysql.database'),
            'tables' => $this->getDatabaseTables()
        ];
        
        File::put(storage_path("app/backups/database_backup_{$timestamp}.json"), json_encode($info, JSON_PRETTY_PRINT));
    }
    
    private function createFilesBackup($timestamp, $description)
    {
        $filename = "files_backup_{$timestamp}.zip";
        $filepath = storage_path("app/backups/{$filename}");
        
        $zip = new ZipArchive();
        if ($zip->open($filepath, ZipArchive::CREATE) === TRUE) {
            // Backup storage/app/public
            $this->addDirectoryToZip($zip, storage_path('app/public'), 'storage/');
            
            // Backup uploads if exists
            $uploadsPath = public_path('uploads');
            if (File::exists($uploadsPath)) {
                $this->addDirectoryToZip($zip, $uploadsPath, 'uploads/');
            }
            
            $zip->close();
        }
        
        // Create info file
        $info = [
            'type' => 'files',
            'description' => $description,
            'created_at' => date('Y-m-d H:i:s'),
            'includes' => ['storage/app/public', 'public/uploads']
        ];
        
        File::put(storage_path("app/backups/files_backup_{$timestamp}.json"), json_encode($info, JSON_PRETTY_PRINT));
    }
    
    private function createFullBackup($timestamp, $description)
    {
        // Create database backup
        $this->createDatabaseBackup($timestamp, $description);
        
        // Create files backup
        $this->createFilesBackup($timestamp, $description);
        
        // Create combined info
        $info = [
            'type' => 'full',
            'description' => $description,
            'created_at' => date('Y-m-d H:i:s'),
            'includes' => ['database', 'storage/app/public', 'public/uploads']
        ];
        
        File::put(storage_path("app/backups/full_backup_{$timestamp}.json"), json_encode($info, JSON_PRETTY_PRINT));
    }
    
    private function addDirectoryToZip($zip, $dir, $zipDir = '')
    {
        if (File::exists($dir)) {
            $files = File::allFiles($dir);
            foreach ($files as $file) {
                $relativePath = $zipDir . $file->getRelativePathname();
                $zip->addFile($file->getPathname(), $relativePath);
            }
        }
    }
    
    private function getDatabaseTables()
    {
        try {
            $tables = \DB::select('SHOW TABLES');
            return array_map(function($table) {
                return array_values((array)$table)[0];
            }, $tables);
        } catch (\Exception $e) {
            return [];
        }
    }
    
    public function downloadBackup($file)
    {
        $filepath = storage_path("app/backups/{$file}");
        
        if (!File::exists($filepath)) {
            return redirect()->route('admin.backup.index')
                           ->with('error', 'File backup tidak ditemukan.');
        }
        
        return response()->download($filepath);
    }
    
    public function deleteBackup($file)
    {
        $filepath = storage_path("app/backups/{$file}");
        
        if (File::exists($filepath)) {
            File::delete($filepath);
            
            // Delete associated info file
            $infoFile = str_replace(['.sql', '.zip'], '.json', $filepath);
            if (File::exists($infoFile)) {
                File::delete($infoFile);
            }
            
            return redirect()->route('admin.backup.index')
                           ->with('success', 'Backup berhasil dihapus.');
        }
        
        return redirect()->route('admin.backup.index')
                       ->with('error', 'File backup tidak ditemukan.');
    }
    
    public function clearCache()
    {
        try {
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('view:clear');
            Artisan::call('route:clear');
            
            return response()->json([
                'success' => true,
                'message' => 'Cache berhasil dibersihkan.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membersihkan cache: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function optimizeApp()
    {
        try {
            Artisan::call('optimize');
            
            return response()->json([
                'success' => true,
                'message' => 'Aplikasi berhasil dioptimasi.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengoptimasi aplikasi: ' . $e->getMessage()
            ], 500);
        }
    }
    
    private function formatBytes($size, $precision = 2)
    {
        $base = log($size, 1024);
        $suffixes = array('', 'KB', 'MB', 'GB', 'TB');
        
        return round(pow(1024, $base - floor($base)), $precision) .' '. $suffixes[floor($base)];
    }
}