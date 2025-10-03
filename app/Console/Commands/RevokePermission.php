<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Permission;

class RevokePermission extends Command
{
    protected $signature = 'user:revoke {email} {permission}';
    protected $description = 'Revoke specific permission from a user';

    public function handle()
    {
        $email = $this->argument('email');
        $permissionName = $this->argument('permission');
        
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            $this->error("User with email '{$email}' not found!");
            return 1;
        }
        
        $permission = Permission::where('name', $permissionName)->first();
        
        if (!$permission) {
            $this->error("Permission '{$permissionName}' not found!");
            $this->info("Available permissions:");
            Permission::pluck('name')->each(function($name) {
                $this->line("- {$name}");
            });
            return 1;
        }
        
        // Add explicit deny permission
        $user->permissions()->syncWithoutDetaching([
            $permission->id => ['type' => 'deny']
        ]);
        
        $this->info("✅ Permission '{$permissionName}' DENIED for user: {$user->name}");
        $this->warn("ℹ️  This user will be explicitly denied access to: {$permission->display_name}");
        
        return 0;
    }
}
