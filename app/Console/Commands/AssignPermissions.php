<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;

class AssignPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permission:assign {email} {role=admin}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign role and permissions to user by email';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        $roleName = $this->argument('role');

        // Find user
        $user = User::where('email', $email)->first();
        if (!$user) {
            $this->error("User with email '{$email}' not found!");
            return 1;
        }

        // Check if role exists
        $role = Role::where('name', $roleName)->first();
        if (!$role) {
            $this->error("Role '{$roleName}' not found!");
            
            // Show available roles
            $availableRoles = Role::pluck('name')->toArray();
            $this->info('Available roles: ' . implode(', ', $availableRoles));
            return 1;
        }

        // Update user
        $user->update([
            'role' => $roleName,
            'is_active' => true
        ]);

        $this->info("✅ User '{$user->name}' ({$email}) updated:");
        $this->info("   - Role: {$roleName}");
        $this->info("   - Status: Active");

        // Show permissions for this role
        $permissions = $role->permissions()->pluck('name')->toArray();
        if (!empty($permissions)) {
            $this->info("   - Permissions: " . implode(', ', $permissions));
        } else {
            $this->warn("   - No specific permissions (using fallback system)");
        }

        return 0;
    }
}
