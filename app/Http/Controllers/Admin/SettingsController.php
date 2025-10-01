<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Artisan;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('verified');
    }

    /**
     * Display the settings page
     */
    public function index()
    {
        if (!Gate::allows('manage-system-settings')) {
            abort(403, 'Unauthorized to manage system settings');
        }

        // Get current settings from database or config
        $settings = $this->getCurrentSettings();

        return view('backend.pages.settings.index', compact('settings'));
    }

    /**
     * Update system settings
     */
    public function update(Request $request)
    {
        if (!Gate::allows('manage-system-settings')) {
            abort(403, 'Unauthorized to manage system settings');
        }

        $validated = $request->validate([
            'settings.site_name' => 'required|string|max:255',
            'settings.site_description' => 'nullable|string|max:1000',
            'settings.contact_email' => 'required|email|max:255',
            'settings.contact_phone' => 'nullable|string|max:20',
            'settings.contact_address' => 'nullable|string|max:500',
            'settings.social_facebook' => 'nullable|url|max:255',
            'settings.social_instagram' => 'nullable|url|max:255',
            'settings.theme' => 'required|string|in:default,blue,green',
            'settings.maintenance_mode' => 'nullable|boolean',
            'settings.mail_driver' => 'required|string|in:smtp,sendmail,mailgun',
            'settings.mail_host' => 'required_if:settings.mail_driver,smtp|string|max:255',
            'settings.mail_port' => 'required_if:settings.mail_driver,smtp|integer|min:1|max:65535',
            'settings.mail_username' => 'required_if:settings.mail_driver,smtp|email|max:255',
            'settings.mail_password' => 'nullable|string|max:255',
            'settings.mail_from_address' => 'required|email|max:255',
            'settings.mail_from_name' => 'required|string|max:255',
            'settings.session_lifetime' => 'required|integer|min:1|max:1440',
            'settings.max_login_attempts' => 'required|integer|min:1|max:10',
            'settings.password_require_uppercase' => 'nullable|boolean',
            'settings.password_require_numbers' => 'nullable|boolean',
            'settings.password_require_symbols' => 'nullable|boolean',
            'settings.enable_2fa' => 'nullable|boolean',
            'settings.auto_backup' => 'nullable|boolean',
            'settings.backup_frequency' => 'required|string|in:daily,weekly,monthly',
            'settings.backup_retention' => 'required|integer|min:1|max:365',
            'site_logo' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
            'site_favicon' => 'nullable|image|mimes:ico,png|max:1024',
        ]);

        try {
            $settings = $validated['settings'];

            // Handle logo upload
            if ($request->hasFile('site_logo')) {
                // Delete old logo
                if (isset($this->getCurrentSettings()['site_logo'])) {
                    Storage::disk('public')->delete($this->getCurrentSettings()['site_logo']);
                }
                $settings['site_logo'] = $request->file('site_logo')->store('settings', 'public');
            }

            // Handle favicon upload
            if ($request->hasFile('site_favicon')) {
                // Delete old favicon
                if (isset($this->getCurrentSettings()['site_favicon'])) {
                    Storage::disk('public')->delete($this->getCurrentSettings()['site_favicon']);
                }
                $settings['site_favicon'] = $request->file('site_favicon')->store('settings', 'public');
            }

            // Don't update password if empty
            if (empty($settings['mail_password'])) {
                unset($settings['mail_password']);
            }

            // Save settings to database (you can create a settings table or use cache)
            $this->saveSettings($settings);

            // Update environment variables if needed
            $this->updateEnvironmentVariables($settings);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Settings updated successfully',
                    'settings' => $settings
                ]);
            }

            return redirect()->route('admin.settings.index')
                ->with('success', 'Settings updated successfully');

        } catch (\Exception $e) {
            \Log::error('Settings update error: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update settings: ' . $e->getMessage()
                ]);
            }

            return back()->withInput()
                ->with('error', 'Failed to update settings');
        }
    }

    /**
     * Send test email
     */
    public function testEmail(Request $request)
    {
        if (!Gate::allows('manage-system-settings')) {
            abort(403, 'Unauthorized to test email settings');
        }

        $validated = $request->validate([
            'email' => 'required|email'
        ]);

        try {
            // Send test email
            Mail::raw('This is a test email from your Laravel application.', function ($message) use ($validated) {
                $message->to($validated['email'])
                        ->subject('Test Email from ' . config('app.name', 'Laravel'));
            });

            return response()->json([
                'success' => true,
                'message' => 'Test email sent successfully'
            ]);

        } catch (\Exception $e) {
            \Log::error('Test email error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to send test email: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Display backup management page
     */
    public function backupIndex()
    {
        if (!Gate::allows('manage-system-backup')) {
            abort(403, 'Unauthorized to manage backups');
        }

        // Get list of backup files
        $backups = $this->getBackupFiles();

        return view('backend.pages.backup.index', compact('backups'));
    }

    /**
     * Create new backup
     */
    public function createBackup(Request $request)
    {
        if (!Gate::allows('manage-system-backup')) {
            abort(403, 'Unauthorized to create backups');
        }

        $validated = $request->validate([
            'type' => 'required|string|in:database,files,full'
        ]);

        try {
            $backupPath = storage_path('app/backups');
            
            if (!is_dir($backupPath)) {
                mkdir($backupPath, 0755, true);
            }

            $timestamp = date('Y-m-d_H-i-s');
            $filename = '';

            switch ($validated['type']) {
                case 'database':
                    $filename = "database_backup_{$timestamp}.sql";
                    $this->createDatabaseBackup($backupPath . '/' . $filename);
                    break;
                
                case 'files':
                    $filename = "files_backup_{$timestamp}.zip";
                    $this->createFilesBackup($backupPath . '/' . $filename);
                    break;
                
                case 'full':
                    $filename = "full_backup_{$timestamp}.zip";
                    $this->createFullBackup($backupPath . '/' . $filename);
                    break;
            }

            return response()->json([
                'success' => true,
                'message' => 'Backup created successfully',
                'filename' => $filename
            ]);

        } catch (\Exception $e) {
            \Log::error('Backup creation error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to create backup: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Download backup file
     */
    public function downloadBackup($filename)
    {
        if (!Gate::allows('manage-system-backup')) {
            abort(403, 'Unauthorized to download backups');
        }

        $filePath = storage_path('app/backups/' . $filename);

        if (!file_exists($filePath)) {
            abort(404, 'Backup file not found');
        }

        return response()->download($filePath);
    }

    /**
     * Delete backup file
     */
    public function deleteBackup($filename)
    {
        if (!Gate::allows('manage-system-backup')) {
            abort(403, 'Unauthorized to delete backups');
        }

        try {
            $filePath = storage_path('app/backups/' . $filename);

            if (file_exists($filePath)) {
                unlink($filePath);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Backup deleted successfully'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Backup file not found'
                ]);
            }

        } catch (\Exception $e) {
            \Log::error('Backup deletion error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete backup'
            ]);
        }
    }

    /**
     * Get current settings from database or config
     */
    private function getCurrentSettings()
    {
        // This is a simplified version - in production you'd store these in a settings table
        return [
            'site_name' => config('app.name', 'Website Desa Ciuwlan'),
            'site_description' => 'Website resmi Desa Ciuwlan, Kecamatan Telagsari',
            'contact_email' => 'info@desaciuwlan.com',
            'contact_phone' => '+62 xxx xxxx xxxx',
            'contact_address' => 'Desa Ciuwlan, Kecamatan Telagsari, Kabupaten Cirebon, Jawa Barat',
            'social_facebook' => '',
            'social_instagram' => '',
            'theme' => 'default',
            'maintenance_mode' => false,
            'mail_driver' => config('mail.default', 'smtp'),
            'mail_host' => config('mail.mailers.smtp.host', 'smtp.gmail.com'),
            'mail_port' => config('mail.mailers.smtp.port', '587'),
            'mail_username' => config('mail.mailers.smtp.username', ''),
            'mail_password' => '', // Never show actual password
            'mail_from_address' => config('mail.from.address', 'noreply@desaciuwlan.com'),
            'mail_from_name' => config('mail.from.name', 'Website Desa Ciuwlan'),
            'session_lifetime' => config('session.lifetime', 120),
            'max_login_attempts' => 5,
            'password_require_uppercase' => false,
            'password_require_numbers' => false,
            'password_require_symbols' => false,
            'enable_2fa' => false,
            'auto_backup' => false,
            'backup_frequency' => 'daily',
            'backup_retention' => 30,
        ];
    }

    /**
     * Save settings to database
     */
    private function saveSettings($settings)
    {
        // This is where you'd save settings to database
        // For now, we'll just store in cache as an example
        cache()->put('app_settings', $settings, now()->addYears(1));
    }

    /**
     * Update environment variables
     */
    private function updateEnvironmentVariables($settings)
    {
        $envUpdates = [];

        if (isset($settings['mail_driver'])) {
            $envUpdates['MAIL_MAILER'] = $settings['mail_driver'];
        }
        if (isset($settings['mail_host'])) {
            $envUpdates['MAIL_HOST'] = $settings['mail_host'];
        }
        if (isset($settings['mail_port'])) {
            $envUpdates['MAIL_PORT'] = $settings['mail_port'];
        }
        if (isset($settings['mail_username'])) {
            $envUpdates['MAIL_USERNAME'] = $settings['mail_username'];
        }
        if (isset($settings['mail_password'])) {
            $envUpdates['MAIL_PASSWORD'] = $settings['mail_password'];
        }

        // Update .env file (simplified - in production use a proper env manager)
        foreach ($envUpdates as $key => $value) {
            $this->updateEnvFile($key, $value);
        }
    }

    /**
     * Update single environment variable
     */
    private function updateEnvFile($key, $value)
    {
        $path = base_path('.env');
        
        if (file_exists($path)) {
            $content = file_get_contents($path);
            
            if (str_contains($content, $key . '=')) {
                $content = preg_replace('/^' . $key . '=.*$/m', $key . '=' . $value, $content);
            } else {
                $content .= "\n" . $key . '=' . $value;
            }
            
            file_put_contents($path, $content);
        }
    }

    /**
     * Get list of backup files
     */
    private function getBackupFiles()
    {
        $backupPath = storage_path('app/backups');
        
        if (!is_dir($backupPath)) {
            return [];
        }

        $files = glob($backupPath . '/*');
        $backups = [];

        foreach ($files as $file) {
            if (is_file($file)) {
                $backups[] = [
                    'name' => basename($file),
                    'size' => $this->formatBytes(filesize($file)),
                    'created' => date('Y-m-d H:i:s', filemtime($file)),
                    'type' => $this->getBackupType(basename($file))
                ];
            }
        }

        // Sort by creation date (newest first)
        usort($backups, function($a, $b) {
            return strtotime($b['created']) - strtotime($a['created']);
        });

        return $backups;
    }

    /**
     * Create database backup
     */
    private function createDatabaseBackup($filePath)
    {
        $database = config('database.connections.mysql.database');
        $username = config('database.connections.mysql.username');
        $password = config('database.connections.mysql.password');
        $host = config('database.connections.mysql.host');

        $command = "mysqldump --user={$username} --password={$password} --host={$host} {$database} > {$filePath}";
        
        exec($command, $output, $returnVar);
        
        if ($returnVar !== 0) {
            throw new \Exception('Database backup failed');
        }
    }

    /**
     * Create files backup
     */
    private function createFilesBackup($filePath)
    {
        $zip = new \ZipArchive();
        
        if ($zip->open($filePath, \ZipArchive::CREATE) !== TRUE) {
            throw new \Exception('Could not create zip file');
        }

        $this->addDirectoryToZip($zip, public_path(), 'public');
        $this->addDirectoryToZip($zip, storage_path('app'), 'storage');
        
        $zip->close();
    }

    /**
     * Create full backup
     */
    private function createFullBackup($filePath)
    {
        // Create database backup first
        $dbBackupPath = storage_path('app/temp_db_backup.sql');
        $this->createDatabaseBackup($dbBackupPath);

        $zip = new \ZipArchive();
        
        if ($zip->open($filePath, \ZipArchive::CREATE) !== TRUE) {
            throw new \Exception('Could not create zip file');
        }

        // Add database backup to zip
        $zip->addFile($dbBackupPath, 'database.sql');
        
        // Add directories
        $this->addDirectoryToZip($zip, public_path(), 'public');
        $this->addDirectoryToZip($zip, storage_path('app'), 'storage');
        
        $zip->close();
        
        // Clean up temporary database backup
        if (file_exists($dbBackupPath)) {
            unlink($dbBackupPath);
        }
    }

    /**
     * Add directory to zip recursively
     */
    private function addDirectoryToZip($zip, $dirPath, $zipPath = '')
    {
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($dirPath),
            \RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($iterator as $file) {
            if ($file->isFile()) {
                $filePath = $file->getRealPath();
                $relativePath = $zipPath . '/' . substr($filePath, strlen($dirPath) + 1);
                $zip->addFile($filePath, $relativePath);
            }
        }
    }

    /**
     * Get backup type from filename
     */
    private function getBackupType($filename)
    {
        if (str_contains($filename, 'database_backup')) {
            return 'Database';
        } elseif (str_contains($filename, 'files_backup')) {
            return 'Files';
        } elseif (str_contains($filename, 'full_backup')) {
            return 'Full';
        }
        return 'Unknown';
    }

    /**
     * Format bytes to human readable format
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }
}