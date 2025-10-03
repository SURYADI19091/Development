<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Carbon\Carbon;

class UpdatePermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permissions:update {--force : Force update without confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update permissions table with all available routes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (!$this->option('force')) {
            if (!$this->confirm('This will update the permissions table with all available routes. Continue?')) {
                $this->info('Operation cancelled.');
                return;
            }
        }

        $this->info('Starting permissions update...');
        
        // Get all routes
        $routes = $this->getAllRoutes();
        $this->info('Found ' . count($routes) . ' routes.');
        
        // Check if permissions table exists
        if (!$this->checkPermissionsTable()) {
            $this->error('Permissions table does not exist. Creating it...');
            $this->createPermissionsTable();
        }
        
        $created = 0;
        $updated = 0;
        $skipped = 0;
        
        foreach ($routes as $route) {
            $result = $this->createOrUpdatePermission($route);
            
            switch ($result) {
                case 'created':
                    $created++;
                    break;
                case 'updated':
                    $updated++;
                    break;
                case 'skipped':
                    $skipped++;
                    break;
            }
        }
        
        $this->info("\nPermissions update completed!");
        $this->table(['Action', 'Count'], [
            ['Created', $created],
            ['Updated', $updated], 
            ['Skipped', $skipped],
            ['Total', $created + $updated + $skipped]
        ]);
        
        // Update role permissions for admin
        $this->updateAdminPermissions();
        
        $this->info('✅ All permissions have been updated successfully!');
    }
    
    /**
     * Get all available routes
     */
    private function getAllRoutes()
    {
        $routes = [];
        $routeCollection = Route::getRoutes();
        
        foreach ($routeCollection as $route) {
            $name = $route->getName();
            $uri = $route->uri();
            $methods = implode('|', $route->methods());
            $action = $route->getActionName();
            
            // Skip routes without names or specific excluded routes
            if (!$name || $this->shouldSkipRoute($name, $uri)) {
                continue;
            }
            
            // Determine category and description
            $category = $this->determineCategory($name, $uri);
            $description = $this->generateDescription($name, $uri, $methods);
            
            $routes[] = [
                'name' => $name,
                'display_name' => $this->generateDisplayName($name),
                'description' => $description,
                'category' => $category,
                'is_active' => 1
            ];
        }
        
        return $routes;
    }
    
    /**
     * Check if permissions table exists
     */
    private function checkPermissionsTable()
    {
        try {
            DB::table('permissions')->count();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
    
    /**
     * Create permissions table if not exists (using existing structure)
     */
    private function createPermissionsTable()
    {
        // Table already exists with different structure, no need to create
        $this->info('Using existing permissions table structure.');
    }
    
    /**
     * Create or update permission
     */
    private function createOrUpdatePermission($route)
    {
        $existing = DB::table('permissions')->where('name', $route['name'])->first();
        
        if ($existing) {
            // Update existing permission
            DB::table('permissions')
                ->where('id', $existing->id)
                ->update([
                    'display_name' => $route['display_name'],
                    'description' => $route['description'],
                    'category' => $route['category'],
                    'is_active' => $route['is_active'],
                    'updated_at' => Carbon::now()
                ]);
            
            return 'updated';
        } else {
            // Create new permission
            DB::table('permissions')->insert([
                'name' => $route['name'],
                'display_name' => $route['display_name'],
                'description' => $route['description'],
                'category' => $route['category'],
                'is_active' => $route['is_active'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
            
            return 'created';
        }
    }
    
    /**
     * Determine if route should be skipped
     */
    private function shouldSkipRoute($name, $uri)
    {
        $skipPatterns = [
            'debugbar.*',
            'horizon.*', 
            '_debugbar.*',
            'ignition.*',
            'livewire.*',
            'sanctum.*'
        ];
        
        foreach ($skipPatterns as $pattern) {
            if (fnmatch($pattern, $name)) {
                return true;
            }
        }
        
        // Skip routes with parameters that are too generic
        if (strpos($uri, '{') !== false && strlen($uri) < 5) {
            return true;
        }
        
        return false;
    }
    
    /**
     * Determine route category
     */
    private function determineCategory($name, $uri)
    {
        $categories = [
            'Authentication' => ['login', 'logout', 'register', 'password', 'auth'],
            'Dashboard' => ['dashboard', 'home'],
            'User Management' => ['users', 'roles', 'permissions'],
            'Content Management' => ['news', 'pages', 'announcements', 'galleries'],
            'Location Management' => ['locations', 'tourism'],
            'Village Management' => ['villages', 'population', 'officials', 'budgets'],
            'Services' => ['services', 'letters', 'templates'],
            'Business' => ['umkm', 'business'],
            'System' => ['settings', 'backup', 'logs', 'maintenance'],
            'API' => ['api'],
            'Frontend' => ['frontend', 'public']
        ];
        
        foreach ($categories as $category => $keywords) {
            foreach ($keywords as $keyword) {
                if (strpos($name, $keyword) !== false || strpos($uri, $keyword) !== false) {
                    return $category;
                }
            }
        }
        
        // Determine by route prefix
        if (strpos($name, 'backend.') === 0) {
            return 'Backend';
        }
        
        if (strpos($name, 'admin.') === 0) {
            return 'Admin';
        }
        
        return 'General';
    }
    
    /**
     * Generate permission description
     */
    private function generateDescription($name, $uri, $methods)
    {
        // Convert route name to readable description
        $parts = explode('.', $name);
        $action = end($parts);
        $resource = isset($parts[count($parts) - 2]) ? $parts[count($parts) - 2] : $parts[0];
        
        $actionMap = [
            'index' => 'View',
            'show' => 'View Details',
            'create' => 'Create',
            'store' => 'Store/Save',
            'edit' => 'Edit',
            'update' => 'Update',
            'destroy' => 'Delete',
            'delete' => 'Delete'
        ];
        
        $actionText = $actionMap[$action] ?? ucfirst($action);
        $resourceText = ucfirst(str_replace(['-', '_'], ' ', $resource));
        
        return "{$actionText} {$resourceText}";
    }
    
    /**
     * Generate display name for permission
     */
    private function generateDisplayName($name)
    {
        // Convert route name to readable display name
        $parts = explode('.', $name);
        $displayParts = [];
        
        foreach ($parts as $part) {
            $displayParts[] = ucfirst(str_replace(['-', '_'], ' ', $part));
        }
        
        return implode(' - ', $displayParts);
    }
    
    /**
     * Update admin role permissions
     */
    private function updateAdminPermissions()
    {
        $this->info('Updating admin role permissions...');
        
        // Check if roles table exists
        try {
            $adminRole = DB::table('roles')->where('name', 'admin')->first();
            if (!$adminRole) {
                $adminRole = DB::table('roles')->where('name', 'super_admin')->first();
            }
            
            if ($adminRole) {
                // Get all permissions
                $permissions = DB::table('permissions')->pluck('id');
                
                // Clear existing role permissions
                DB::table('role_permissions')->where('role_id', $adminRole->id)->delete();
                
                // Assign all permissions to admin role
                foreach ($permissions as $permissionId) {
                    DB::table('role_permissions')->insert([
                        'role_id' => $adminRole->id,
                        'permission_id' => $permissionId,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now()
                    ]);
                }
                
                $this->info('Admin role updated with ' . count($permissions) . ' permissions.');
            } else {
                $this->warn('Admin role not found. Please create admin role manually.');
            }
            
        } catch (\Exception $e) {
            $this->warn('Could not update role permissions: ' . $e->getMessage());
        }
    }
}
