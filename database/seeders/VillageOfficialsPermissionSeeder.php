<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;
use App\Models\Role;

class VillageOfficialsPermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Get existing village officials permission or create new one
        $permission = Permission::where('name', 'manage.village_officials')->first();
        
        if (!$permission) {
            $permission = Permission::create([
                'name' => 'manage.village_officials',
                'display_name' => 'Kelola Perangkat Desa',
                'description' => 'Mengelola data perangkat desa'
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

        $this->command->info('Village officials permission created and assigned successfully!');
    }
}