<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Models\News;
use App\Models\ContactMessage;
use App\Models\PopulationData;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * Display the admin dashboard
     */
    public function index(Request $request)
    {
        // Check if user can access admin dashboard
        if (!Gate::allows('access-admin-panel')) {
            abort(403, 'Unauthorized access to admin panel');
        }

        // Get dashboard statistics
        $stats = $this->getDashboardStats();
        
        // Get chart data for monthly statistics
        $chartData = $this->getChartData();
        
        // Get recent activities
        $recentActivities = $this->getRecentActivities();

        // If it's an AJAX request for stats refresh
        if ($request->ajax() && $request->get('ajax') === 'stats') {
            return response()->json($stats);
        }

        return view('backend.pages.dashboard', compact('stats', 'chartData', 'recentActivities'));
    }

    /**
     * Get dashboard statistics
     */
    private function getDashboardStats()
    {
        $stats = [];
        
        try {
            // Users statistics
            if (Gate::allows('manage-users')) {
                $stats['total_users'] = User::count();
                $stats['new_users_this_month'] = User::whereMonth('created_at', Carbon::now()->month)
                    ->whereYear('created_at', Carbon::now()->year)
                    ->count();
            }

            // Population statistics
            if (Gate::allows('manage-population-data')) {
                $populationData = PopulationData::latest()->first();
                $stats['total_population'] = $populationData ? 
                    ($populationData->male_count + $populationData->female_count) : 0;
            }

            // Content statistics
            if (Gate::allows('manage-content')) {
                $stats['total_news'] = News::count();
                $stats['news_this_month'] = News::whereMonth('created_at', Carbon::now()->month)
                    ->whereYear('created_at', Carbon::now()->year)
                    ->count();
            }

            // Contact messages statistics
            if (Gate::allows('manage-contact-messages')) {
                $stats['total_messages'] = ContactMessage::count();
                $stats['unread_messages'] = ContactMessage::where('is_read', false)->count();
            }

            // System statistics
            $stats['storage_used'] = $this->getStorageUsed();
            $stats['storage_total'] = '1 GB'; // Configurable
            $stats['storage_percentage'] = $this->getStoragePercentage();
            $stats['last_backup'] = $this->getLastBackupDate();

        } catch (\Exception $e) {
            \Log::error('Dashboard stats error: ' . $e->getMessage());
            // Return default stats if there's an error
            $stats = [
                'total_users' => 0,
                'new_users_this_month' => 0,
                'total_population' => 0,
                'total_news' => 0,
                'news_this_month' => 0,
                'total_messages' => 0,
                'unread_messages' => 0,
                'storage_used' => '0 MB',
                'storage_total' => '1 GB',
                'storage_percentage' => 0,
                'last_backup' => 'Never'
            ];
        }

        return $stats;
    }

    /**
     * Get chart data for dashboard
     */
    private function getChartData()
    {
        $chartData = [
            'months' => [],
            'users' => [],
            'news' => [],
            'messages' => []
        ];

        try {
            // Get last 6 months data
            for ($i = 5; $i >= 0; $i--) {
                $date = Carbon::now()->subMonths($i);
                $monthName = $date->locale('id')->format('M');
                
                $chartData['months'][] = $monthName;
                
                // Users data
                if (Gate::allows('manage-users')) {
                    $chartData['users'][] = User::whereMonth('created_at', $date->month)
                        ->whereYear('created_at', $date->year)
                        ->count();
                } else {
                    $chartData['users'][] = 0;
                }

                // News data
                if (Gate::allows('manage-content')) {
                    $chartData['news'][] = News::whereMonth('created_at', $date->month)
                        ->whereYear('created_at', $date->year)
                        ->count();
                } else {
                    $chartData['news'][] = 0;
                }

                // Messages data
                if (Gate::allows('manage-contact-messages')) {
                    $chartData['messages'][] = ContactMessage::whereMonth('created_at', $date->month)
                        ->whereYear('created_at', $date->year)
                        ->count();
                } else {
                    $chartData['messages'][] = 0;
                }
            }
        } catch (\Exception $e) {
            \Log::error('Chart data error: ' . $e->getMessage());
            // Return default chart data
            $chartData = [
                'months' => ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
                'users' => [0, 0, 0, 0, 0, 0],
                'news' => [0, 0, 0, 0, 0, 0],
                'messages' => [0, 0, 0, 0, 0, 0]
            ];
        }

        return $chartData;
    }

    /**
     * Get recent activities for dashboard
     */
    private function getRecentActivities()
    {
        $activities = [];

        try {
            // Recent user registrations
            if (Gate::allows('manage-users')) {
                $recentUsers = User::latest()->take(3)->get();
                foreach ($recentUsers as $user) {
                    $activities[] = [
                        'title' => 'Pengguna baru mendaftar',
                        'description' => $user->name,
                        'time' => $user->created_at->diffForHumans(),
                        'icon' => 'fas fa-user-plus',
                        'color' => 'blue'
                    ];
                }
            }

            // Recent news
            if (Gate::allows('manage-content')) {
                $recentNews = News::latest()->take(2)->get();
                foreach ($recentNews as $news) {
                    $activities[] = [
                        'title' => 'Berita baru dipublikasi',
                        'description' => \Str::limit($news->title, 50),
                        'time' => $news->created_at->diffForHumans(),
                        'icon' => 'fas fa-newspaper',
                        'color' => 'green'
                    ];
                }
            }

            // Recent messages
            if (Gate::allows('manage-contact-messages')) {
                $recentMessages = ContactMessage::latest()->take(2)->get();
                foreach ($recentMessages as $message) {
                    $activities[] = [
                        'title' => 'Pesan baru diterima',
                        'description' => 'Dari: ' . $message->name,
                        'time' => $message->created_at->diffForHumans(),
                        'icon' => 'fas fa-envelope',
                        'color' => 'orange'
                    ];
                }
            }

            // Sort by time (most recent first)
            usort($activities, function($a, $b) {
                // This is a simplified sort - in production you'd want to use actual timestamps
                return strcmp($b['time'], $a['time']);
            });

            // Limit to 5 most recent
            $activities = array_slice($activities, 0, 5);

        } catch (\Exception $e) {
            \Log::error('Recent activities error: ' . $e->getMessage());
            $activities = [];
        }

        return $activities;
    }

    /**
     * Get storage usage information
     */
    private function getStorageUsed()
    {
        try {
            $bytes = 0;
            $publicPath = public_path();
            $storagePath = storage_path();
            
            // Calculate public directory size
            $bytes += $this->getDirectorySize($publicPath);
            
            // Calculate storage directory size
            $bytes += $this->getDirectorySize($storagePath);
            
            return $this->formatBytes($bytes);
        } catch (\Exception $e) {
            \Log::error('Storage calculation error: ' . $e->getMessage());
            return '0 MB';
        }
    }

    /**
     * Get storage usage percentage
     */
    private function getStoragePercentage()
    {
        try {
            // This is a simplified calculation
            // In production, you'd want to get actual disk usage
            $usedMB = 50; // Example value
            $totalMB = 1024; // 1GB in MB
            
            return round(($usedMB / $totalMB) * 100, 1);
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Get last backup date
     */
    private function getLastBackupDate()
    {
        try {
            // Check for backup files in storage
            $backupPath = storage_path('app/backups');
            
            if (!is_dir($backupPath)) {
                return 'Never';
            }

            $files = glob($backupPath . '/*');
            if (empty($files)) {
                return 'Never';
            }

            $latestFile = max($files);
            $fileTime = filemtime($latestFile);
            
            return Carbon::createFromTimestamp($fileTime)->diffForHumans();
        } catch (\Exception $e) {
            return 'Never';
        }
    }

    /**
     * Calculate directory size recursively
     */
    private function getDirectorySize($directory)
    {
        $size = 0;
        
        try {
            if (is_dir($directory)) {
                $files = new \RecursiveIteratorIterator(
                    new \RecursiveDirectoryIterator($directory)
                );
                
                foreach ($files as $file) {
                    if ($file->isFile()) {
                        $size += $file->getSize();
                    }
                }
            }
        } catch (\Exception $e) {
            // Handle permission errors or other issues
            return 0;
        }
        
        return $size;
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

    /**
     * Get system information for dashboard
     */
    public function getSystemInfo()
    {
        if (!Gate::allows('view-system-info')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $info = [
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
            'database_version' => $this->getDatabaseVersion(),
            'memory_limit' => ini_get('memory_limit'),
            'max_execution_time' => ini_get('max_execution_time'),
            'upload_max_filesize' => ini_get('upload_max_filesize'),
        ];

        return response()->json($info);
    }

    /**
     * Get database version
     */
    private function getDatabaseVersion()
    {
        try {
            $result = DB::select('SELECT VERSION() as version');
            return $result[0]->version ?? 'Unknown';
        } catch (\Exception $e) {
            return 'Unknown';
        }
    }

    /**
     * Show logs page
     */
    public function logs(Request $request)
    {
        if (!Gate::allows('view-logs')) {
            abort(403, 'You do not have permission to view logs.');
        }

        $logs = $this->getLogEntries($request);
        
        return view('backend.logs.index', [
            'logs' => $logs,
            'levels' => ['emergency', 'alert', 'critical', 'error', 'warning', 'notice', 'info', 'debug'],
            'currentLevel' => $request->get('level', ''),
            'currentDate' => $request->get('date', ''),
            'search' => $request->get('search', ''),
        ]);
    }

    /**
     * Show activity logs page
     */
    public function activityLogs(Request $request)
    {
        if (!Gate::allows('view-activity-logs')) {
            abort(403, 'You do not have permission to view activity logs.');
        }

        // In a real application, you would have an ActivityLog model
        // For now, we'll return a simple view with mock data
        $activities = collect([
            [
                'id' => 1,
                'user' => 'Admin User',
                'action' => 'User Login',
                'description' => 'Admin logged into the system',
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0...',
                'created_at' => now()->subMinutes(10),
            ],
            [
                'id' => 2,
                'user' => 'Super Admin',
                'action' => 'News Created',
                'description' => 'Created new news article: "Village Development Update"',
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0...',
                'created_at' => now()->subHour(),
            ],
        ]);

        return view('backend.logs.activity', [
            'activities' => $activities,
            'search' => $request->get('search', ''),
            'user_filter' => $request->get('user', ''),
            'action_filter' => $request->get('action', ''),
        ]);
    }

    /**
     * Get log entries from Laravel log files
     */
    private function getLogEntries(Request $request)
    {
        $logs = collect();
        
        try {
            $logPath = storage_path('logs/laravel.log');
            
            if (!file_exists($logPath)) {
                return $logs;
            }

            $content = file_get_contents($logPath);
            $lines = explode("\n", $content);
            
            // Parse last 100 log entries (simplified parsing)
            $entries = array_slice(array_reverse($lines), 0, 100);
            
            foreach ($entries as $line) {
                if (empty(trim($line))) continue;
                
                // Simple regex to parse Laravel log format
                if (preg_match('/^\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\] \w+\.(\w+): (.+)/', $line, $matches)) {
                    $entry = [
                        'datetime' => $matches[1],
                        'level' => $matches[2],
                        'message' => $matches[3],
                        'context' => '',
                    ];
                    
                    // Apply filters
                    if ($request->get('level') && $request->get('level') !== $entry['level']) {
                        continue;
                    }
                    
                    if ($request->get('search') && stripos($entry['message'], $request->get('search')) === false) {
                        continue;
                    }
                    
                    if ($request->get('date') && !str_contains($entry['datetime'], $request->get('date'))) {
                        continue;
                    }
                    
                    $logs->push($entry);
                }
            }
            
        } catch (\Exception $e) {
            \Log::error('Error reading log file: ' . $e->getMessage());
        }
        
        return $logs->take(50); // Limit to 50 entries
    }

    /**
     * Clear log files
     */
    public function clearLogs(Request $request)
    {
        if (!Gate::allows('clear-logs')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        try {
            $logPath = storage_path('logs/laravel.log');
            
            if (file_exists($logPath)) {
                // Clear the log file content
                file_put_contents($logPath, '');
            }

            return response()->json(['success' => true, 'message' => 'Logs cleared successfully!']);
        } catch (\Exception $e) {
            \Log::error('Error clearing logs: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to clear logs.']);
        }
    }
}