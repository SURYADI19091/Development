<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Permission;

class GrantPermission extends Command
{
    protected $signature = 'user:grant {email} {permission}';
    protected $description = 'Grant specific permission to a user';

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
        
        // Add explicit grant permission
        $user->permissions()->syncWithoutDetaching([
            $permission->id => ['type' => 'grant']
        ]);
        
        $this->info("✅ Permission '{$permissionName}' GRANTED to user: {$user->name}");
        $this->info("ℹ️  User can now access: {$permission->display_name}");
        
        return 0;
    }
}
