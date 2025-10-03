<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Permission;

class AssignAllPermissions extends Command
{
    protected $signature = 'user:grant-all {email}';
    protected $description = 'Grant all permissions to a user (for super admin)';

    public function handle()
    {
        $email = $this->argument('email');
        
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            $this->error("User with email '{$email}' not found!");
            return 1;
        }
        
        // Get all permissions
        $allPermissions = Permission::where('is_active', true)->get();
        
        if ($allPermissions->isEmpty()) {
            $this->warn("No permissions found in database. Run: php artisan db:seed --class=PermissionSeeder");
            return 1;
        }
        
        // Grant all permissions to user
        $permissionIds = $allPermissions->pluck('id')->toArray();
        $user->permissions()->sync($permissionIds, ['type' => 'grant']);
        
        $this->info("✅ Granted {$allPermissions->count()} permissions to user: {$user->name}");
        
        // Also set as super_admin if requested
        if ($this->confirm("Set user role as super_admin?", true)) {
            $user->role = 'super_admin';
            $user->save();
            $this->info("✅ User role set to super_admin");
        }
        
        $this->info("\n=== Granted Permissions ===");
        foreach ($allPermissions as $permission) {
            $this->line("- {$permission->name} ({$permission->display_name})");
        }
        
        return 0;
    }
}
