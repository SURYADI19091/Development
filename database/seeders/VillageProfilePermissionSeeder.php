<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;
use App\Models\Role;

class VillageProfilePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Get existing village profile permission or create new one
        $permission = Permission::where('name', 'manage.village_profile')->first();
        
        if (!$permission) {
            $permission = Permission::create([
                'name' => 'manage.village_profile',
                'display_name' => 'Kelola Profil Desa', 
                'description' => 'Mengelola profil desa'
            ]);
        }

        // Assign to super_admin role
        $superAdmin = Role::where('name', 'super_admin')->first();
        if ($superAdmin && !$superAdmin->permissions()->where('permission_id', $permission->id)->exists()) {
            $superAdmin->permissions()->attach($permission->id);
        }

        // Also assign to admin role
        $admin = Role::where('name', 'admin')->first();
        if ($admin && !$admin->permissions()->where('permission_id', $permission->id)->exists()) {
            $admin->permissions()->attach($permission->id);
        }

        // Create village officials permission
        $officialsPermission = Permission::where('name', 'manage.village_officials')->first();
        
        if (!$officialsPermission) {
            $officialsPermission = Permission::create([
                'name' => 'manage.village_officials',
                'display_name' => 'Kelola Perangkat Desa',
                'description' => 'Mengelola data perangkat desa'
            ]);
        }

        // Assign officials permission to super_admin
        if ($superAdmin && !$superAdmin->permissions()->where('permission_id', $officialsPermission->id)->exists()) {
            $superAdmin->permissions()->attach($officialsPermission->id);
        }

        // Assign officials permission to admin
        if ($admin && !$admin->permissions()->where('permission_id', $officialsPermission->id)->exists()) {
            $admin->permissions()->attach($officialsPermission->id);
        }

        $this->command->info('Village profile and officials permissions created and assigned successfully!');
    }
}