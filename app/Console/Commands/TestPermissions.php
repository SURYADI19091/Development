<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Support\Facades\Gate;

class TestPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permissions:test {email=superadmin@desa.com}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test permissions system for a specific user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error("User with email {$email} not found!");
            return 1;
        }

        $this->info("Testing permissions for: {$user->name} ({$user->email})");
        $this->info("Role: {$user->role}");
        $this->info("Active: " . ($user->is_active ? 'Yes' : 'No'));
        $this->newLine();

        // Test key permissions
        $testPermissions = [
            'access.dashboard',
            'access.backend', 
            'manage.users',
            'view.users',
            'manage.population',
            'view.population',
            'manage.news',
            'view.news',
            'manage.agendas',
            'view.agendas'
        ];

        $this->info('Testing key permissions:');
        $this->table(
            ['Permission', 'Has Permission', 'Gate Check'],
            collect($testPermissions)->map(function($permission) use ($user) {
                $hasPermission = method_exists($user, 'hasPermission') ? 
                    ($user->hasPermission($permission) ? 'Yes' : 'No') : 'Method N/A';
                
                // Simulate gate check as user
                auth()->login($user);
                $gateCheck = Gate::allows($permission) ? 'Yes' : 'No';
                auth()->logout();
                
                return [
                    $permission,
                    $hasPermission,
                    $gateCheck
                ];
            })
        );

        $this->newLine();
        
        // Check database stats
        $this->info('Database Statistics:');
        $this->info('Total Permissions: ' . Permission::count());
        $this->info('Total Roles: ' . Role::count());
        
        $superAdminRole = Role::where('name', 'super_admin')->first();
        if ($superAdminRole) {
            $this->info('Super Admin Role Permissions: ' . $superAdminRole->permissions()->count());
        }

        return 0;
    }
}
