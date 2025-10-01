<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Permission;
use App\Models\Role;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define all permissions based on current gates
        $permissions = [
            // Dashboard access
            ['name' => 'access.dashboard', 'display_name' => 'Access Dashboard', 'description' => 'Access admin dashboard'],
            ['name' => 'access.backend', 'display_name' => 'Access Backend', 'description' => 'Access backend panel'],
            
            // User management
            ['name' => 'manage.users', 'display_name' => 'Manage Users', 'description' => 'Manage users'],
            ['name' => 'view.users', 'display_name' => 'View Users', 'description' => 'View users list'],
            ['name' => 'create.users', 'display_name' => 'Create Users', 'description' => 'Create new users'],
            ['name' => 'edit.users', 'display_name' => 'Edit Users', 'description' => 'Edit users'],
            ['name' => 'delete.users', 'display_name' => 'Delete Users', 'description' => 'Delete users'],
            
            // Village profile management
            ['name' => 'manage.village_profile', 'display_name' => 'Manage Village Profile', 'description' => 'Manage village profile'],
            ['name' => 'view.village_profile', 'display_name' => 'View Village Profile', 'description' => 'View village profile'],
            ['name' => 'edit.village_profile', 'display_name' => 'Edit Village Profile', 'description' => 'Edit village profile'],
            
            // Village officials
            ['name' => 'manage.village_officials', 'display_name' => 'Manage Village Officials', 'description' => 'Manage village officials'],
            ['name' => 'view.village_officials', 'display_name' => 'View Village Officials', 'description' => 'View village officials'],
            ['name' => 'create.village_officials', 'display_name' => 'Create Village Officials', 'description' => 'Create village officials'],
            ['name' => 'edit.village_officials', 'display_name' => 'Edit Village Officials', 'description' => 'Edit village officials'],
            ['name' => 'delete.village_officials', 'display_name' => 'Delete Village Officials', 'description' => 'Delete village officials'],
            
            // Population data
            ['name' => 'manage.population', 'display_name' => 'Manage Population', 'description' => 'Manage population data'],
            ['name' => 'view.population', 'display_name' => 'View Population', 'description' => 'View population data'],
            ['name' => 'create.population', 'display_name' => 'Create Population', 'description' => 'Create population data'],
            ['name' => 'edit.population', 'display_name' => 'Edit Population', 'description' => 'Edit population data'],
            ['name' => 'delete.population', 'display_name' => 'Delete Population', 'description' => 'Delete population data'],
            
            // News management
            ['name' => 'manage.news', 'display_name' => 'Manage News', 'description' => 'Manage news'],
            ['name' => 'view.news', 'display_name' => 'View News', 'description' => 'View news'],
            ['name' => 'create.news', 'display_name' => 'Create News', 'description' => 'Create news'],
            ['name' => 'edit.news', 'display_name' => 'Edit News', 'description' => 'Edit news'],
            ['name' => 'delete.news', 'display_name' => 'Delete News', 'description' => 'Delete news'],
            ['name' => 'publish.news', 'display_name' => 'Publish News', 'description' => 'Publish news'],
            
            // Agenda management
            ['name' => 'manage.agendas', 'display_name' => 'Manage Agendas', 'description' => 'Manage agendas'],
            ['name' => 'view.agendas', 'display_name' => 'View Agendas', 'description' => 'View agendas'],
            ['name' => 'create.agendas', 'display_name' => 'Create Agendas', 'description' => 'Create agendas'],
            ['name' => 'edit.agendas', 'display_name' => 'Edit Agendas', 'description' => 'Edit agendas'],
            ['name' => 'delete.agendas', 'display_name' => 'Delete Agendas', 'description' => 'Delete agendas'],
            
            // Announcements
            ['name' => 'manage.announcements', 'display_name' => 'Manage Announcements', 'description' => 'Manage announcements'],
            ['name' => 'view.announcements', 'display_name' => 'View Announcements', 'description' => 'View announcements'],
            ['name' => 'create.announcements', 'display_name' => 'Create Announcements', 'description' => 'Create announcements'],
            ['name' => 'edit.announcements', 'display_name' => 'Edit Announcements', 'description' => 'Edit announcements'],
            ['name' => 'delete.announcements', 'display_name' => 'Delete Announcements', 'description' => 'Delete announcements'],
            
            // Gallery
            ['name' => 'manage.gallery', 'display_name' => 'Manage Gallery', 'description' => 'Manage gallery'],
            ['name' => 'view.gallery', 'display_name' => 'View Gallery', 'description' => 'View gallery'],
            ['name' => 'create.gallery', 'display_name' => 'Create Gallery', 'description' => 'Create gallery items'],
            ['name' => 'edit.gallery', 'display_name' => 'Edit Gallery', 'description' => 'Edit gallery items'],
            ['name' => 'delete.gallery', 'display_name' => 'Delete Gallery', 'description' => 'Delete gallery items'],
            
            // UMKM
            ['name' => 'manage.umkm', 'display_name' => 'Manage UMKM', 'description' => 'Manage UMKM'],
            ['name' => 'view.umkm', 'display_name' => 'View UMKM', 'description' => 'View UMKM'],
            ['name' => 'create.umkm', 'display_name' => 'Create UMKM', 'description' => 'Create UMKM'],
            ['name' => 'edit.umkm', 'display_name' => 'Edit UMKM', 'description' => 'Edit UMKM'],
            ['name' => 'delete.umkm', 'display_name' => 'Delete UMKM', 'description' => 'Delete UMKM'],
            
            // Budget management
            ['name' => 'manage.budget', 'display_name' => 'Manage Budget', 'description' => 'Manage village budget'],
            ['name' => 'view.budget', 'display_name' => 'View Budget', 'description' => 'View village budget'],
            ['name' => 'create.budget', 'display_name' => 'Create Budget', 'description' => 'Create budget entries'],
            ['name' => 'edit.budget', 'display_name' => 'Edit Budget', 'description' => 'Edit budget entries'],
            ['name' => 'delete.budget', 'display_name' => 'Delete Budget', 'description' => 'Delete budget entries'],
            
            // Letter requests
            ['name' => 'manage.letter_requests', 'display_name' => 'Manage Letter Requests', 'description' => 'Manage letter requests'],
            ['name' => 'view.letter_requests', 'display_name' => 'View Letter Requests', 'description' => 'View letter requests'],
            ['name' => 'approve.letter_requests', 'display_name' => 'Approve Letter Requests', 'description' => 'Approve letter requests'],
            ['name' => 'reject.letter_requests', 'display_name' => 'Reject Letter Requests', 'description' => 'Reject letter requests'],
            
            // Banners
            ['name' => 'manage.banners', 'display_name' => 'Manage Banners', 'description' => 'Manage banners'],
            ['name' => 'view.banners', 'display_name' => 'View Banners', 'description' => 'View banners'],
            ['name' => 'create.banners', 'display_name' => 'Create Banners', 'description' => 'Create banners'],
            ['name' => 'edit.banners', 'display_name' => 'Edit Banners', 'description' => 'Edit banners'],
            ['name' => 'delete.banners', 'display_name' => 'Delete Banners', 'description' => 'Delete banners'],
            
            // Tourism
            ['name' => 'manage.tourism', 'display_name' => 'Manage Tourism', 'description' => 'Manage tourism objects'],
            ['name' => 'view.tourism', 'display_name' => 'View Tourism', 'description' => 'View tourism objects'],
            ['name' => 'create.tourism', 'display_name' => 'Create Tourism', 'description' => 'Create tourism objects'],
            ['name' => 'edit.tourism', 'display_name' => 'Edit Tourism', 'description' => 'Edit tourism objects'],
            ['name' => 'delete.tourism', 'display_name' => 'Delete Tourism', 'description' => 'Delete tourism objects'],
            
            // Statistics
            ['name' => 'manage.statistics', 'display_name' => 'Manage Statistics', 'description' => 'Manage village statistics'],
            ['name' => 'view.statistics', 'display_name' => 'View Statistics', 'description' => 'View village statistics'],
            ['name' => 'create.statistics', 'display_name' => 'Create Statistics', 'description' => 'Create statistics entries'],
            ['name' => 'edit.statistics', 'display_name' => 'Edit Statistics', 'description' => 'Edit statistics entries'],
            ['name' => 'delete.statistics', 'display_name' => 'Delete Statistics', 'description' => 'Delete statistics entries'],
            
            // System permissions
            ['name' => 'manage.permissions', 'display_name' => 'Manage Permissions', 'description' => 'Manage system permissions'],
            ['name' => 'manage.roles', 'display_name' => 'Manage Roles', 'description' => 'Manage user roles'],
            ['name' => 'view.logs', 'display_name' => 'View Logs', 'description' => 'View system logs'],
            ['name' => 'manage.settings', 'display_name' => 'Manage Settings', 'description' => 'Manage system settings'],
        ];

        // Create permissions
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission['name']],
                $permission
            );
        }

        // Define roles with their permissions
        $roles = [
            'super_admin' => [
                'display_name' => 'Super Administrator',
                'description' => 'Super Administrator with full access',
                'permissions' => array_column($permissions, 'name') // All permissions
            ],
            'admin' => [
                'display_name' => 'Administrator',
                'description' => 'Administrator with management access',
                'permissions' => [
                    'access.dashboard',
                    'access.backend',
                    'manage.users',
                    'view.users',
                    'create.users',
                    'edit.users',
                    'manage.village_profile',
                    'view.village_profile',
                    'edit.village_profile',
                    'manage.village_officials',
                    'view.village_officials',
                    'create.village_officials',
                    'edit.village_officials',
                    'delete.village_officials',
                    'manage.population',
                    'view.population',
                    'create.population',
                    'edit.population',
                    'delete.population',
                    'manage.news',
                    'view.news',
                    'create.news',
                    'edit.news',
                    'delete.news',
                    'publish.news',
                    'manage.agendas',
                    'view.agendas',
                    'create.agendas',
                    'edit.agendas',
                    'delete.agendas',
                    'manage.announcements',
                    'view.announcements',
                    'create.announcements',
                    'edit.announcements',
                    'delete.announcements',
                    'manage.gallery',
                    'view.gallery',
                    'create.gallery',
                    'edit.gallery',
                    'delete.gallery',
                    'manage.umkm',
                    'view.umkm',
                    'create.umkm',
                    'edit.umkm',
                    'delete.umkm',
                    'manage.budget',
                    'view.budget',
                    'create.budget',
                    'edit.budget',
                    'delete.budget',
                    'manage.letter_requests',
                    'view.letter_requests',
                    'approve.letter_requests',
                    'reject.letter_requests',
                    'manage.banners',
                    'view.banners',
                    'create.banners',
                    'edit.banners',
                    'delete.banners',
                    'manage.tourism',
                    'view.tourism',
                    'create.tourism',
                    'edit.tourism',
                    'delete.tourism',
                    'manage.statistics',
                    'view.statistics',
                    'create.statistics',
                    'edit.statistics',
                    'delete.statistics',
                    'view.logs',
                    'manage.settings'
                ]
            ],
            'staff' => [
                'display_name' => 'Staff',
                'description' => 'Staff with limited access',
                'permissions' => [
                    'access.dashboard',
                    'access.backend',
                    'view.village_profile',
                    'view.village_officials',
                    'view.population',
                    'create.population',
                    'edit.population',
                    'view.news',
                    'create.news',
                    'edit.news',
                    'view.agendas',
                    'create.agendas',
                    'edit.agendas',
                    'view.announcements',
                    'create.announcements',
                    'edit.announcements',
                    'view.gallery',
                    'create.gallery',
                    'edit.gallery',
                    'view.umkm',
                    'create.umkm',
                    'edit.umkm',
                    'view.budget',
                    'view.letter_requests',
                    'view.banners',
                    'view.tourism',
                    'view.statistics'
                ]
            ],
            'user' => [
                'display_name' => 'User',
                'description' => 'Regular user with basic access',
                'permissions' => [
                    'access.dashboard'
                ]
            ]
        ];

        // Create roles and assign permissions
        foreach ($roles as $roleName => $roleData) {
            $role = Role::firstOrCreate(
                ['name' => $roleName],
                [
                    'display_name' => $roleData['display_name'],
                    'description' => $roleData['description']
                ]
            );

            // Get permission IDs
            $permissionIds = Permission::whereIn('name', $roleData['permissions'])->pluck('id');
            
            // Attach permissions to role
            $role->permissions()->sync($permissionIds);
        }

        $this->command->info('Permissions and roles seeded successfully!');
    }
}