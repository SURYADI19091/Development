<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

class ShowUserPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permission:show {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show user permissions and test access';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');

        // Find user
        $user = User::where('email', $email)->first();
        if (!$user) {
            $this->error("User with email '{$email}' not found!");
            return 1;
        }

        $this->info("=== User Information ===");
        $this->info("Name: {$user->name}");
        $this->info("Email: {$user->email}");
        $this->info("Role: {$user->role}");
        $this->info("Active: " . ($user->is_active ? 'Yes' : 'No'));

        // Test key permissions
        $permissions = [
            'access-admin-panel',
            'manage-users',
            'manage-content',
            'manage-village-data',
            'manage-population-data',
            'manage-locations',
            'manage-village-budget',
            'manage-contact-messages',
            'view-system-info',
            'manage-settings'
        ];

        $this->info("\n=== Permission Check ===");
        
        // Set current user for Gate testing
        auth()->login($user);
        
        foreach ($permissions as $permission) {
            $hasAccess = Gate::allows($permission);
            $status = $hasAccess ? '✅ ALLOWED' : '❌ DENIED';
            $this->info("{$permission}: {$status}");
        }

        // Show role permissions if available
        if (method_exists($user, 'role') && $user->role) {
            $role = \App\Models\Role::where('name', $user->role)->first();
            if ($role) {
                $rolePermissions = $role->permissions()->pluck('name')->toArray();
                if (!empty($rolePermissions)) {
                    $this->info("\n=== Role Permissions ({$user->role}) ===");
                    foreach ($rolePermissions as $perm) {
                        $this->info("- {$perm}");
                    }
                } else {
                    $this->warn("\n⚠️  Role '{$user->role}' has no specific permissions assigned");
                }
            }
        }

        // Show direct user permissions if available
        if (method_exists($user, 'permissions')) {
            $userPermissions = $user->permissions()->pluck('name')->toArray();
            if (!empty($userPermissions)) {
                $this->info("\n=== Direct User Permissions ===");
                foreach ($userPermissions as $perm) {
                    $this->info("- {$perm}");
                }
            }
        }

        return 0;
    }
}
