<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;
use App\Models\Role;

class SidebarPermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Define all permissions for sidebar menu
        $permissions = [
            // Dashboard
            'view.dashboard' => 'Lihat Dashboard',
            
            // Data Management
            'manage.users' => 'Kelola Data Pengguna',
            'manage.population_data' => 'Kelola Data Penduduk',
            'manage.village_data' => 'Kelola Data Desa',
            'manage.village_profile' => 'Kelola Profil Desa',
            'manage.village_officials' => 'Kelola Perangkat Desa',
            
            // Content Management
            'manage.content' => 'Kelola Konten',
            'manage.news' => 'Kelola Berita',
            'manage.announcements' => 'Kelola Pengumuman',
            'manage.agenda' => 'Kelola Agenda',
            'manage.gallery' => 'Kelola Galeri',
            
            // Services
            'manage.services' => 'Kelola Layanan Publik',
            'manage.umkm' => 'Kelola UMKM',
            'manage.tourism' => 'Kelola Wisata',
            'manage.banners' => 'Kelola Banner',
            
            // Communication
            'manage.contact_messages' => 'Kelola Pesan Kontak',
            'process.service_requests' => 'Proses Pengajuan Surat',
            
            // Financial
            'manage.budget_data' => 'Kelola Data Keuangan',
            'manage.budget' => 'Kelola APBDes',
            
            // Reports
            'generate.reports' => 'Generate Laporan',
            'view.statistics' => 'Lihat Statistik',
            'view.reports' => 'Lihat Laporan',
            
            // System
            'manage.settings' => 'Kelola Pengaturan Sistem',
            'manage.system_backup' => 'Kelola Backup Sistem',
            'view.system_logs' => 'Lihat System Logs',
            
            // Special permissions
            'access.admin_panel' => 'Akses Panel Admin',
            'manage.permissions' => 'Kelola Permission System',
        ];

        // Create permissions
        foreach ($permissions as $name => $displayName) {
            $permission = Permission::where('name', $name)->first();
            if (!$permission) {
                Permission::create([
                    'name' => $name,
                    'display_name' => $displayName,
                    'description' => "Permission untuk {$displayName}"
                ]);
            }
        }

        // Assign permissions to roles
        $this->assignPermissionsToRoles();
        
        $this->command->info('Sidebar permissions created and assigned successfully!');
    }

    private function assignPermissionsToRoles()
    {
        // Super Admin - All permissions
        $superAdmin = Role::where('name', 'super_admin')->first();
        if ($superAdmin) {
            $allPermissions = Permission::pluck('id')->toArray();
            $superAdmin->permissions()->syncWithoutDetaching($allPermissions);
        }

        // Admin - Most permissions except system management
        $admin = Role::where('name', 'admin')->first();
        if ($admin) {
            $adminPermissions = Permission::whereIn('name', [
                'view.dashboard',
                'manage.users',
                'manage.population_data',
                'manage.village_data',
                'manage.village_profile',
                'manage.village_officials',
                'manage.content',
                'manage.news',
                'manage.announcements',
                'manage.agenda',
                'manage.gallery',
                'manage.services',
                'manage.umkm',
                'manage.tourism',
                'manage.banners',
                'manage.contact_messages',
                'process.service_requests',
                'manage.budget_data',
                'manage.budget',
                'generate.reports',
                'view.statistics',
                'view.reports',
                'access.admin_panel',
            ])->pluck('id')->toArray();
            
            $admin->permissions()->syncWithoutDetaching($adminPermissions);
        }

        // Staff - Limited permissions
        $staff = Role::where('name', 'staff')->first();
        if ($staff) {
            $staffPermissions = Permission::whereIn('name', [
                'view.dashboard',
                'manage.population_data',
                'manage.content',
                'manage.news',
                'manage.announcements',
                'manage.agenda',
                'manage.gallery',
                'manage.umkm',
                'manage.contact_messages',
                'process.service_requests',
                'view.statistics',
                'access.admin_panel',
            ])->pluck('id')->toArray();
            
            $staff->permissions()->syncWithoutDetaching($staffPermissions);
        }

        // User - Very limited permissions
        $user = Role::where('name', 'user')->first();
        if ($user) {
            $userPermissions = Permission::whereIn('name', [
                'view.dashboard',
            ])->pluck('id')->toArray();
            
            $user->permissions()->syncWithoutDetaching($userPermissions);
        }
    }
}