<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;
use App\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create default permissions
        $permissions = [
            // System permissions
            ['name' => 'access-admin-panel', 'display_name' => 'Akses Panel Admin', 'description' => 'Akses ke panel administrasi', 'category' => 'system'],
            ['name' => 'manage-settings', 'display_name' => 'Kelola Pengaturan', 'description' => 'Mengelola pengaturan sistem', 'category' => 'system'],
            ['name' => 'view-system-info', 'display_name' => 'Lihat Info Sistem', 'description' => 'Melihat informasi sistem', 'category' => 'system'],
            ['name' => 'manage-system-backup', 'display_name' => 'Kelola Backup Sistem', 'description' => 'Mengelola backup sistem', 'category' => 'system'],
            ['name' => 'view-logs', 'display_name' => 'Lihat Log Sistem', 'description' => 'Melihat log sistem', 'category' => 'system'],
            ['name' => 'clear-logs', 'display_name' => 'Hapus Log', 'description' => 'Menghapus log sistem', 'category' => 'system'],

            // User management permissions
            ['name' => 'manage-users', 'display_name' => 'Kelola Pengguna', 'description' => 'Mengelola data pengguna', 'category' => 'users'],
            ['name' => 'create-user', 'display_name' => 'Buat Pengguna', 'description' => 'Membuat pengguna baru', 'category' => 'users'],
            ['name' => 'update-user', 'display_name' => 'Update Pengguna', 'description' => 'Mengupdate data pengguna', 'category' => 'users'],
            ['name' => 'delete-user', 'display_name' => 'Hapus Pengguna', 'description' => 'Menghapus pengguna', 'category' => 'users'],
            ['name' => 'view-user', 'display_name' => 'Lihat Pengguna', 'description' => 'Melihat detail pengguna', 'category' => 'users'],
            ['name' => 'manage-user-status', 'display_name' => 'Kelola Status Pengguna', 'description' => 'Mengubah status pengguna', 'category' => 'users'],
            ['name' => 'export-users', 'display_name' => 'Ekspor Pengguna', 'description' => 'Mengekspor data pengguna', 'category' => 'users'],

            // Content management permissions
            ['name' => 'manage-content', 'display_name' => 'Kelola Konten', 'description' => 'Mengelola konten website', 'category' => 'content'],
            ['name' => 'publish-content', 'display_name' => 'Publikasi Konten', 'description' => 'Mempublikasikan konten', 'category' => 'content'],
            ['name' => 'moderate-content', 'display_name' => 'Moderasi Konten', 'description' => 'Melakukan moderasi konten', 'category' => 'content'],
            ['name' => 'view-content', 'display_name' => 'Lihat Konten', 'description' => 'Melihat konten', 'category' => 'content'],
            ['name' => 'edit-content', 'display_name' => 'Edit Konten', 'description' => 'Mengedit konten', 'category' => 'content'],
            ['name' => 'delete-content', 'display_name' => 'Hapus Konten', 'description' => 'Menghapus konten', 'category' => 'content'],

            // Village data permissions
            ['name' => 'manage-village-data', 'display_name' => 'Kelola Data Desa', 'description' => 'Mengelola data desa', 'category' => 'village_data'],
            ['name' => 'manage-population-data', 'display_name' => 'Kelola Data Penduduk', 'description' => 'Mengelola data penduduk', 'category' => 'village_data'],
            ['name' => 'manage-village-budget', 'display_name' => 'Kelola Anggaran Desa', 'description' => 'Mengelola anggaran desa', 'category' => 'village_data'],
            ['name' => 'manage-locations', 'display_name' => 'Kelola Lokasi', 'description' => 'Mengelola data lokasi', 'category' => 'village_data'],
            ['name' => 'view-sensitive-data', 'display_name' => 'Lihat Data Sensitif', 'description' => 'Melihat data sensitif', 'category' => 'village_data'],

            // Communication permissions
            ['name' => 'manage-contact-messages', 'display_name' => 'Kelola Pesan Kontak', 'description' => 'Mengelola pesan kontak', 'category' => 'communication'],
            ['name' => 'reply-contact-messages', 'display_name' => 'Balas Pesan Kontak', 'description' => 'Membalas pesan kontak', 'category' => 'communication'],
            ['name' => 'send-notifications', 'display_name' => 'Kirim Notifikasi', 'description' => 'Mengirim notifikasi', 'category' => 'communication'],

            // Services permissions
            ['name' => 'manage-services', 'display_name' => 'Kelola Layanan', 'description' => 'Mengelola layanan desa', 'category' => 'services'],
            ['name' => 'process-service-requests', 'display_name' => 'Proses Permintaan Layanan', 'description' => 'Memproses permintaan layanan', 'category' => 'services'],
            ['name' => 'manage-letter-templates', 'display_name' => 'Kelola Template Surat', 'description' => 'Mengelola template surat', 'category' => 'services'],

            // Reports permissions
            ['name' => 'generate-reports', 'display_name' => 'Generate Laporan', 'description' => 'Membuat laporan', 'category' => 'reports'],
            ['name' => 'export-data', 'display_name' => 'Ekspor Data', 'description' => 'Mengekspor data', 'category' => 'reports'],
            ['name' => 'view-activity-logs', 'display_name' => 'Lihat Log Aktivitas', 'description' => 'Melihat log aktivitas', 'category' => 'reports'],

            // Permissions management
            ['name' => 'manage-permissions', 'display_name' => 'Kelola Permission', 'description' => 'Mengelola hak akses pengguna', 'category' => 'permissions'],
            ['name' => 'assign-super-admin-role', 'display_name' => 'Assign Super Admin', 'description' => 'Memberikan role super admin', 'category' => 'permissions'],
            ['name' => 'assign-admin-role', 'display_name' => 'Assign Admin', 'description' => 'Memberikan role admin', 'category' => 'permissions'],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission['name']],
                $permission
            );
        }

        // Create default roles if they don't exist
        $superAdminRole = Role::firstOrCreate(
            ['name' => 'super_admin'],
            [
                'display_name' => 'Super Administrator',
                'description' => 'Has access to all system features',
                'is_active' => true
            ]
        );

        $adminRole = Role::firstOrCreate(
            ['name' => 'admin'],
            [
                'display_name' => 'Administrator',
                'description' => 'Has access to most administrative features',
                'is_active' => true
            ]
        );

        // Super Admin gets all permissions (handled by Gate::before in AuthServiceProvider)
        // Admin gets specific permissions
        $adminPermissions = [
            'access-admin-panel', 'manage-users', 'create-user', 'view-user', 'update-user',
            'manage-content', 'publish-content', 'view-content', 'edit-content',
            'manage-village-data', 'manage-population-data', 'manage-locations',
            'manage-contact-messages', 'reply-contact-messages',
            'generate-reports', 'export-data', 'view-activity-logs',
            'view-system-info', 'manage-village-budget'
        ];

        $permissions = Permission::whereIn('name', $adminPermissions)->get();
        $adminRole->permissions()->syncWithoutDetaching($permissions);

        $this->command->info('Permissions and roles seeded successfully!');
    }
}
