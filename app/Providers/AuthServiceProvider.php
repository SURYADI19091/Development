<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // Add your model policies here
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
        $this->registerGates();
    }

    /**
     * Register custom gates for authorization.
     */
    protected function registerGates(): void
    {
        // Database-driven permissions system
        Gate::before(function ($user, $ability) {
            // User must be active
            if (!$user || !$user->isActive()) {
                return false;
            }
            
            // Check if user has the specific permission
            if (method_exists($user, 'hasPermission')) {
                return $user->hasPermission($ability);
            }
            
            // Fallback to original role-based system
            return null;
        });
        
        // Legacy gates (will be overridden by database permissions)
        // System Access Gates
        Gate::define('access-system', function (?User $user) {
            return $user && $user->is_active === true;
        });

        Gate::define('account-active', function (?User $user) {
            return $user && $user->is_active === true;
        });

        Gate::define('account-verified', function (?User $user) {
            return $user && $user->email_verified_at !== null;
        });

        // Registration Gates
        Gate::define('register-account', function (?User $user = null) {
            // Allow registration by default, but can be configured
            return config('auth.allow_registration', true);
        });

        // Role-based Access Gates
        Gate::define('access-admin-panel', function (?User $user) {
            return $user && in_array($user->role, ['admin', 'super_admin']);
        });

        Gate::define('access-user-dashboard', function (?User $user) {
            return $user && in_array($user->role, ['user', 'member', 'resident']);
        });

        Gate::define('is-admin', function (?User $user) {
            return $user && in_array($user->role, ['admin', 'super_admin']);
        });

        Gate::define('is-super-admin', function (?User $user) {
            return $user && $user->role === 'super_admin';
        });

        Gate::define('is-user', function (?User $user) {
            return $user && in_array($user->role, ['user', 'member', 'resident']);
        });

        // Profile Management Gates
        Gate::define('view-profile', function (?User $user, ?User $targetUser = null) {
            if (!$user) return false;
            
            // Users can view their own profile
            if ($targetUser && $user->id === $targetUser->id) {
                return true;
            }
            
            // Admins can view any profile
            if (in_array($user->role, ['admin', 'super_admin'])) {
                return true;
            }

            // Default: can view own profile
            return $targetUser === null;
        });

        Gate::define('update-profile', function (?User $user, ?User $targetUser = null) {
            if (!$user) return false;
            
            // Users can update their own profile
            if ($targetUser && $user->id === $targetUser->id) {
                return $user->is_active === true;
            }
            
            // Admins can update any profile
            if (in_array($user->role, ['admin', 'super_admin'])) {
                return true;
            }

            // Default: can update own profile if active
            return $targetUser === null && $user->is_active === true;
        });

        Gate::define('change-password', function (?User $user, ?User $targetUser = null) {
            if (!$user) return false;
            
            // Users can change their own password
            if ($targetUser && $user->id === $targetUser->id) {
                return $user->is_active === true;
            }
            
            // Admins can change any password except super admin
            if ($user->role === 'admin') {
                return $targetUser === null || $targetUser->role !== 'super_admin';
            }
            
            // Super admins can change any password
            if ($user->role === 'super_admin') {
                return true;
            }

            // Default: can change own password if active
            return $targetUser === null && $user->is_active === true;
        });

        // User Management Gates
        Gate::define('manage-users', function (?User $user) {
            return $user && ($user->role === 'super_admin' || in_array($user->role, ['admin']));
        });

        Gate::define('create-user', function (?User $user) {
            return $user && ($user->role === 'super_admin' || in_array($user->role, ['admin']));
        });

        Gate::define('update-user', function (?User $user, User $targetUser) {
            if (!$user || !in_array($user->role, ['admin', 'super_admin'])) {
                return false;
            }
            
            // Super admin can update anyone
            if ($user->role === 'super_admin') {
                return true;
            }
            
            // Admin cannot update super admin or other admins
            if ($user->role === 'admin') {
                return !in_array($targetUser->role, ['admin', 'super_admin']);
            }
            
            return false;
        });

        Gate::define('delete-user', function (?User $user, User $targetUser) {
            if (!$user || !in_array($user->role, ['admin', 'super_admin'])) {
                return false;
            }
            
            // Cannot delete self
            if ($user->id === $targetUser->id) {
                return false;
            }
            
            // Super admin can delete anyone except other super admins
            if ($user->role === 'super_admin') {
                return $targetUser->role !== 'super_admin';
            }
            
            // Admin can only delete regular users
            if ($user->role === 'admin') {
                return in_array($targetUser->role, ['user', 'member', 'resident']);
            }
            
            return false;
        });

        Gate::define('approve-user', function (?User $user) {
            return $user && ($user->role === 'super_admin' || in_array($user->role, ['admin']));
        });

        Gate::define('bulk-delete-users', function (?User $user) {
            return $user && ($user->role === 'super_admin' || in_array($user->role, ['admin']));
        });

        Gate::define('manage-user-status', function (?User $user, ?User $targetUser = null) {
            if (!$user || !in_array($user->role, ['admin', 'super_admin'])) {
                return false;
            }
            
            // If no target user specified, allow for bulk operations
            if (!$targetUser) {
                return true;
            }
            
            // Cannot change own status
            if ($user->id === $targetUser->id) {
                return false;
            }
            
            // Super admin can change anyone's status except other super admins
            if ($user->role === 'super_admin') {
                return $targetUser->role !== 'super_admin';
            }
            
            // Admin can only change regular user status
            if ($user->role === 'admin') {
                return in_array($targetUser->role, ['user', 'member', 'resident']);
            }
            
            return false;
        });

        Gate::define('view-user', function (?User $user, ?User $targetUser = null) {
            if (!$user) return false;
            
            // Users can view their own profile
            if ($targetUser && $user->id === $targetUser->id) {
                return true;
            }
            
            // Admins can view any user
            return in_array($user->role, ['admin', 'super_admin']);
        });

        Gate::define('edit-user', function (?User $user, User $targetUser) {
            if (!$user || !in_array($user->role, ['admin', 'super_admin'])) {
                return false;
            }
            
            // Cannot edit self through this interface
            if ($user->id === $targetUser->id) {
                return false;
            }
            
            // Super admin can edit anyone except other super admins
            if ($user->role === 'super_admin') {
                return $targetUser->role !== 'super_admin';
            }
            
            // Admin can only edit regular users
            if ($user->role === 'admin') {
                return in_array($targetUser->role, ['user', 'member', 'resident']);
            }
            
            return false;
        });

        Gate::define('ban-user', function (?User $user, User $targetUser) {
            if (!$user || !in_array($user->role, ['admin', 'super_admin'])) {
                return false;
            }
            
            // Cannot ban self
            if ($user->id === $targetUser->id) {
                return false;
            }
            
            // Super admin can ban anyone except other super admins
            if ($user->role === 'super_admin') {
                return $targetUser->role !== 'super_admin';
            }
            
            // Admin can only ban regular users
            return in_array($targetUser->role, ['user', 'member', 'resident']);
        });

        // Role Assignment Gates
        Gate::define('assign-super-admin-role', function (?User $user) {
            return $user && $user->role === 'super_admin';
        });

        Gate::define('assign-admin-role', function (?User $user) {
            return $user && in_array($user->role, ['super_admin']);
        });

        Gate::define('assign-operator-role', function (?User $user) {
            return $user && in_array($user->role, ['admin', 'super_admin']);
        });

        // Export Gates
        Gate::define('export-users', function (?User $user) {
            return $user && ($user->role === 'super_admin' || in_array($user->role, ['admin']));
        });

        Gate::define('export-content', function (?User $user) {
            return $user && ($user->role === 'super_admin' || in_array($user->role, ['admin']));
        });

        // Content Management Gates
        Gate::define('manage-content', function (?User $user) {
            return $user && ($user->role === 'super_admin' || in_array($user->role, ['admin', 'editor']));
        });

        Gate::define('publish-content', function (?User $user) {
            return $user && ($user->role === 'super_admin' || in_array($user->role, ['admin']));
        });

        Gate::define('moderate-content', function (?User $user) {
            return $user && ($user->role === 'super_admin' || in_array($user->role, ['admin', 'moderator']));
        });

        Gate::define('view-content', function (?User $user) {
            return $user && ($user->role === 'super_admin' || in_array($user->role, ['admin', 'editor', 'moderator']));
        });

        Gate::define('edit-content', function (?User $user) {
            return $user && ($user->role === 'super_admin' || in_array($user->role, ['admin', 'editor']));
        });

        Gate::define('delete-content', function (?User $user) {
            return $user && ($user->role === 'super_admin' || in_array($user->role, ['admin']));
        });

        // Data Management Gates
        Gate::define('manage-village-data', function (?User $user) {
            return $user && ($user->role === 'super_admin' || in_array($user->role, ['admin', 'village_officer']));
        });

        Gate::define('manage-population-data', function (?User $user) {
            return $user && ($user->role === 'super_admin' || in_array($user->role, ['admin', 'population_officer']));
        });

        Gate::define('manage-budget-data', function (?User $user) {
            return $user && ($user->role === 'super_admin' || in_array($user->role, ['admin', 'finance_officer']));
        });

        Gate::define('view-sensitive-data', function (?User $user) {
            return $user && ($user->role === 'super_admin' || in_array($user->role, ['admin']));
        });

        // Communication Gates
        Gate::define('manage-contact-messages', function (?User $user) {
            return $user && ($user->role === 'super_admin' || in_array($user->role, ['admin', 'cs_officer']));
        });

        Gate::define('reply-contact-messages', function (?User $user) {
            return $user && ($user->role === 'super_admin' || in_array($user->role, ['admin', 'cs_officer']));
        });

        Gate::define('send-notifications', function (?User $user) {
            return $user && ($user->role === 'super_admin' || in_array($user->role, ['admin']));
        });

        // System Configuration Gates
        Gate::define('manage-settings', function (?User $user) {
            return $user && $user->role === 'super_admin';
        });

        Gate::define('manage-system-backup', function (?User $user) {
            return $user && $user->role === 'super_admin';
        });

        Gate::define('view-system-logs', function (?User $user) {
            return $user && ($user->role === 'super_admin' || in_array($user->role, ['admin']));
        });

        Gate::define('manage-permissions', function (?User $user) {
            return $user && $user->role === 'super_admin';
        });

        // Activity Logging Gates
        Gate::define('log-user-activity', function (?User $user = null) {
            // Always allow activity logging for security
            return true;
        });

        Gate::define('view-activity-logs', function (?User $user) {
            return $user && ($user->role === 'super_admin' || in_array($user->role, ['admin']));
        });

        // Service Management Gates
        Gate::define('manage-services', function (?User $user) {
            return $user && ($user->role === 'super_admin' || in_array($user->role, ['admin', 'service_officer']));
        });

        Gate::define('process-service-requests', function (?User $user) {
            return $user && ($user->role === 'super_admin' || in_array($user->role, ['admin', 'service_officer']));
        });

        // Report Generation Gates
        Gate::define('generate-reports', function (?User $user) {
            return $user && ($user->role === 'super_admin' || in_array($user->role, ['admin', 'report_officer']));
        });

        Gate::define('export-data', function (?User $user) {
            return $user && ($user->role === 'super_admin' || in_array($user->role, ['admin']));
        });

        // Special Permission Gates
        Gate::define('impersonate-user', function (?User $user, User $targetUser) {
            if (!$user || $user->role !== 'super_admin') {
                return false;
            }
            
            // Cannot impersonate self or other super admins
            return $user->id !== $targetUser->id && $targetUser->role !== 'super_admin';
        });

        Gate::define('access-maintenance-mode', function (?User $user) {
            return $user && ($user->role === 'super_admin' || in_array($user->role, ['admin']));
        });

        // Time-based Gates
        Gate::define('login-during-maintenance', function (?User $user) {
            if (!$user) return false;
            
            // Allow super_admin and admins to login during maintenance
            if ($user->role === 'super_admin' || in_array($user->role, ['admin'])) {
                return true;
            }
            
            // Check if system is in maintenance mode
            return !app()->isDownForMaintenance();
        });

        // IP-based Gates (example)
        Gate::define('admin-ip-restriction', function (?User $user) {
            if (!$user || !($user->role === 'super_admin' || in_array($user->role, ['admin']))) {
                return false;
            }
            
            // Super admin bypasses IP restrictions
            if ($user->role === 'super_admin') {
                return true;
            }
            
            // Check if IP restriction is enabled for regular admins
            $allowedIps = config('auth.admin_allowed_ips', []);
            
            if (empty($allowedIps)) {
                return true; // No restriction
            }
            
            $currentIp = request()->ip();
            return in_array($currentIp, $allowedIps);
        });

        // Logs and Monitoring Gates
        Gate::define('view-logs', function (?User $user) {
            return $user && ($user->role === 'super_admin' || in_array($user->role, ['admin']));
        });

        Gate::define('view-activity-logs', function (?User $user) {
            return $user && ($user->role === 'super_admin' || in_array($user->role, ['admin']));
        });

        Gate::define('view-system-info', function (?User $user) {
            return $user && ($user->role === 'super_admin' || in_array($user->role, ['admin']));
        });

        Gate::define('clear-logs', function (?User $user) {
            return $user && $user->role === 'super_admin';
        });
    }
}