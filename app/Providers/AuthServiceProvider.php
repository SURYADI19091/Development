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
            // Allow if no user (for public access)
            if (!$user) {
                return null; // Continue to individual gates
            }
            
            // User must be active for most operations
            if (!$user->isActive()) {
                // But allow some basic operations even for inactive users
                $allowedForInactive = ['account-active', 'access-system', 'view-profile'];
                if (!in_array($ability, $allowedForInactive)) {
                    return false;
                }
            }
            
            // Check if user has explicit permission in database first
            if (method_exists($user, 'hasPermission')) {
                try {
                    $userPermission = $user->permissions()
                        ->where('permissions.name', $ability)
                        ->first();
                        
                    if ($userPermission) {
                        // If explicitly granted or denied, use that
                        if ($userPermission->pivot->type === 'grant') {
                            return true;
                        } elseif ($userPermission->pivot->type === 'deny') {
                            return false;
                        }
                    }
                } catch (\Exception $e) {
                    \Log::error('Permission check failed: ' . $e->getMessage());
                }
            }
            
            // Super admin fallback - has access unless explicitly denied
            if (isset($user->role) && $user->role === 'super_admin') {
                return true;
            }
            
            // Continue to check individual gates below for fallback
            return null;
        });
        
        // Define database permissions if they don't exist
        $this->defineDefaultPermissions();
        
        // Assign default permissions to roles
        $this->assignDefaultRolePermissions();
        
        // Legacy gates (fallback for role-based system)
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
            if (!$user) return false;
            
            // Check if user is active
            if (!$user->isActive()) return false;
            
            // Allow admin and super_admin roles
            if (isset($user->role)) {
                return in_array($user->role, ['admin', 'super_admin']);
            }
            
            return false;
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
            if (!$user || !$user->isActive()) return false;
            
            // Check database permission first
            if (method_exists($user, 'hasPermission') && $user->hasPermission('manage-users')) {
                return true;
            }
            
            // Fallback to role-based check
            return $user->role === 'super_admin' || in_array($user->role, ['admin']);
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
            if (!$user || !$user->isActive()) return false;
            
            // Check database permission first
            if (method_exists($user, 'hasPermission') && $user->hasPermission('manage-content')) {
                return true;
            }
            
            // Fallback to role-based check
            return $user->role === 'super_admin' || in_array($user->role, ['admin', 'editor']);
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
            if (!$user || !$user->isActive()) return false;
            
            // Check database permission first
            if (method_exists($user, 'hasPermission') && $user->hasPermission('manage-village-data')) {
                return true;
            }
            
            // Fallback to role-based check
            return $user->role === 'super_admin' || in_array($user->role, ['admin', 'village_officer']);
        });

        Gate::define('manage-population-data', function (?User $user) {
            if (!$user || !$user->isActive()) return false;
            
            // Check database permission first
            if (method_exists($user, 'hasPermission') && $user->hasPermission('manage-population-data')) {
                return true;
            }
            
            // Fallback to role-based check
            return $user->role === 'super_admin' || in_array($user->role, ['admin', 'population_officer']);
        });

        Gate::define('manage-budget-data', function (?User $user) {
            return $user && ($user->role === 'super_admin' || in_array($user->role, ['admin', 'finance_officer']));
        });

        Gate::define('view-sensitive-data', function (?User $user) {
            return $user && ($user->role === 'super_admin' || in_array($user->role, ['admin']));
        });

        // Communication Gates
        Gate::define('manage-contact-messages', function (?User $user) {
            if (!$user || !$user->isActive()) return false;
            
            // Check database permission first
            if (method_exists($user, 'hasPermission') && $user->hasPermission('manage-contact-messages')) {
                return true;
            }
            
            // Fallback to role-based check
            return $user->role === 'super_admin' || in_array($user->role, ['admin', 'cs_officer']);
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
            if (!$user || !$user->isActive()) return false;
            
            // Check database permission first
            if (method_exists($user, 'hasPermission') && $user->hasPermission('manage-permissions')) {
                return true;
            }
            
            // Fallback to role-based check
            return $user->role === 'super_admin';
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

        // Letter Template Management Gates
        Gate::define('manage.letter_templates', function (?User $user) {
            return $user && ($user->role === 'super_admin' || in_array($user->role, ['admin']));
        });

        Gate::define('letter_templates.view', function (?User $user) {
            return $user && ($user->role === 'super_admin' || in_array($user->role, ['admin', 'editor']));
        });

        Gate::define('letter_templates.create', function (?User $user) {
            return $user && ($user->role === 'super_admin' || in_array($user->role, ['admin']));
        });

        Gate::define('letter_templates.edit', function (?User $user) {
            return $user && ($user->role === 'super_admin' || in_array($user->role, ['admin']));
        });

        Gate::define('letter_templates.delete', function (?User $user) {
            return $user && ($user->role === 'super_admin' || in_array($user->role, ['admin']));
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
            if (!$user || !$user->isActive()) return false;
            
            // Check database permission first
            if (method_exists($user, 'hasPermission') && $user->hasPermission('view-system-info')) {
                return true;
            }
            
            // Fallback to role-based check
            return $user->role === 'super_admin' || in_array($user->role, ['admin']);
        });

        Gate::define('clear-logs', function (?User $user) {
            return $user && $user->role === 'super_admin';
        });

        // Additional gates for specific features
        Gate::define('manage-locations', function (?User $user) {
            if (!$user || !$user->isActive()) return false;
            
            // Check database permission first
            if (method_exists($user, 'hasPermission') && $user->hasPermission('manage-locations')) {
                return true;
            }
            
            // Fallback to role-based check
            return $user->role === 'super_admin' || in_array($user->role, ['admin']);
        });

        Gate::define('manage-village-budget', function (?User $user) {
            if (!$user || !$user->isActive()) return false;
            
            // Check database permission first
            if (method_exists($user, 'hasPermission') && $user->hasPermission('manage-village-budget')) {
                return true;
            }
            
            // Fallback to role-based check
            return $user->role === 'super_admin' || in_array($user->role, ['admin', 'finance_officer']);
        });
    }

    /**
     * Define default permissions in database if they don't exist
     */
    protected function defineDefaultPermissions(): void
    {
        if (!class_exists(\App\Models\Permission::class)) {
            return;
        }

        try {
            $defaultPermissions = $this->getDefaultPermissions();
            
            foreach ($defaultPermissions as $category => $permissions) {
                foreach ($permissions as $permission) {
                    \App\Models\Permission::firstOrCreate(
                        ['name' => $permission['name']],
                        [
                            'display_name' => $permission['display_name'],
                            'description' => $permission['description'],
                            'category' => $category,
                            'is_active' => true
                        ]
                    );
                }
            }
        } catch (\Exception $e) {
            // Silently fail if database is not ready
            \Log::error('Failed to define default permissions: ' . $e->getMessage());
        }
    }

    /**
     * Get default permission definitions
     */
    protected function getDefaultPermissions(): array
    {
        return [
            'system' => [
                ['name' => 'access-admin-panel', 'display_name' => 'Akses Panel Admin', 'description' => 'Akses ke panel administrasi'],
                ['name' => 'manage-settings', 'display_name' => 'Kelola Pengaturan', 'description' => 'Mengelola pengaturan sistem'],
                ['name' => 'view-system-info', 'display_name' => 'Lihat Info Sistem', 'description' => 'Melihat informasi sistem'],
                ['name' => 'manage-system-backup', 'display_name' => 'Kelola Backup Sistem', 'description' => 'Mengelola backup sistem'],
                ['name' => 'view-logs', 'display_name' => 'Lihat Log Sistem', 'description' => 'Melihat log sistem'],
                ['name' => 'clear-logs', 'display_name' => 'Hapus Log', 'description' => 'Menghapus log sistem'],
            ],
            'users' => [
                ['name' => 'manage-users', 'display_name' => 'Kelola Pengguna', 'description' => 'Mengelola data pengguna'],
                ['name' => 'create-user', 'display_name' => 'Buat Pengguna', 'description' => 'Membuat pengguna baru'],
                ['name' => 'update-user', 'display_name' => 'Update Pengguna', 'description' => 'Mengupdate data pengguna'],
                ['name' => 'delete-user', 'display_name' => 'Hapus Pengguna', 'description' => 'Menghapus pengguna'],
                ['name' => 'view-user', 'display_name' => 'Lihat Pengguna', 'description' => 'Melihat detail pengguna'],
                ['name' => 'manage-user-status', 'display_name' => 'Kelola Status Pengguna', 'description' => 'Mengubah status pengguna'],
                ['name' => 'export-users', 'display_name' => 'Ekspor Pengguna', 'description' => 'Mengekspor data pengguna'],
            ],
            'content' => [
                ['name' => 'manage-content', 'display_name' => 'Kelola Konten', 'description' => 'Mengelola konten website'],
                ['name' => 'publish-content', 'display_name' => 'Publikasi Konten', 'description' => 'Mempublikasikan konten'],
                ['name' => 'moderate-content', 'display_name' => 'Moderasi Konten', 'description' => 'Melakukan moderasi konten'],
                ['name' => 'view-content', 'display_name' => 'Lihat Konten', 'description' => 'Melihat konten'],
                ['name' => 'edit-content', 'display_name' => 'Edit Konten', 'description' => 'Mengedit konten'],
                ['name' => 'delete-content', 'display_name' => 'Hapus Konten', 'description' => 'Menghapus konten'],
            ],
            'village_data' => [
                ['name' => 'manage-village-data', 'display_name' => 'Kelola Data Desa', 'description' => 'Mengelola data desa'],
                ['name' => 'manage-population-data', 'display_name' => 'Kelola Data Penduduk', 'description' => 'Mengelola data penduduk'],
                ['name' => 'manage-village-budget', 'display_name' => 'Kelola Anggaran Desa', 'description' => 'Mengelola anggaran desa'],
                ['name' => 'manage-locations', 'display_name' => 'Kelola Lokasi', 'description' => 'Mengelola data lokasi'],
                ['name' => 'view-sensitive-data', 'display_name' => 'Lihat Data Sensitif', 'description' => 'Melihat data sensitif'],
            ],
            'communication' => [
                ['name' => 'manage-contact-messages', 'display_name' => 'Kelola Pesan Kontak', 'description' => 'Mengelola pesan kontak'],
                ['name' => 'reply-contact-messages', 'display_name' => 'Balas Pesan Kontak', 'description' => 'Membalas pesan kontak'],
                ['name' => 'send-notifications', 'display_name' => 'Kirim Notifikasi', 'description' => 'Mengirim notifikasi'],
            ],
            'services' => [
                ['name' => 'manage-services', 'display_name' => 'Kelola Layanan', 'description' => 'Mengelola layanan desa'],
                ['name' => 'process-service-requests', 'display_name' => 'Proses Permintaan Layanan', 'description' => 'Memproses permintaan layanan'],
                ['name' => 'manage-letter-templates', 'display_name' => 'Kelola Template Surat', 'description' => 'Mengelola template surat'],
            ],
            'reports' => [
                ['name' => 'generate-reports', 'display_name' => 'Generate Laporan', 'description' => 'Membuat laporan'],
                ['name' => 'export-data', 'display_name' => 'Ekspor Data', 'description' => 'Mengekspor data'],
                ['name' => 'view-activity-logs', 'display_name' => 'Lihat Log Aktivitas', 'description' => 'Melihat log aktivitas'],
            ],
            'permissions' => [
                ['name' => 'manage-permissions', 'display_name' => 'Kelola Permission', 'description' => 'Mengelola hak akses pengguna'],
                ['name' => 'assign-super-admin-role', 'display_name' => 'Assign Super Admin', 'description' => 'Memberikan role super admin'],
                ['name' => 'assign-admin-role', 'display_name' => 'Assign Admin', 'description' => 'Memberikan role admin'],
            ]
        ];
    }

    /**
     * Assign default permissions to roles
     */
    protected function assignDefaultRolePermissions(): void
    {
        try {
            $superAdminRole = \App\Models\Role::firstOrCreate(
                ['name' => 'super_admin'],
                [
                    'display_name' => 'Super Administrator',
                    'description' => 'Has access to all system features',
                    'is_active' => true
                ]
            );

            $adminRole = \App\Models\Role::firstOrCreate(
                ['name' => 'admin'],
                [
                    'display_name' => 'Administrator',
                    'description' => 'Has access to most administrative features',
                    'is_active' => true
                ]
            );

            // Super Admin gets all permissions (handled by Gate::before)
            // Admin gets specific permissions
            $adminPermissions = [
                'access-admin-panel', 'manage-users', 'create-user', 'view-user', 'update-user',
                'manage-content', 'publish-content', 'view-content', 'edit-content',
                'manage-village-data', 'manage-population-data', 'manage-locations',
                'manage-contact-messages', 'reply-contact-messages',
                'generate-reports', 'export-data', 'view-activity-logs'
            ];

            $permissions = \App\Models\Permission::whereIn('name', $adminPermissions)->get();
            $adminRole->permissions()->syncWithoutDetaching($permissions);

        } catch (\Exception $e) {
            \Log::error('Failed to assign default role permissions: ' . $e->getMessage());
        }
    }
}