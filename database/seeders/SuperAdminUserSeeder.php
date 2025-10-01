<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SuperAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create or update super admin user
        $superAdmin = User::firstOrCreate(
            ['email' => 'superadmin@desa.com'],
            [
                'name' => 'Super Administrator',
                'email' => 'superadmin@desa.com',
                'password' => Hash::make('superadmin123'),
                'role' => 'super_admin',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        // Update existing user if found
        if (!$superAdmin->wasRecentlyCreated) {
            $superAdmin->update([
                'name' => 'Super Administrator',
                'role' => 'super_admin',
                'is_active' => true,
                'email_verified_at' => now(),
            ]);
        }

        $this->command->info('Super Admin user created/updated successfully!');
        $this->command->info('Email: superadmin@desa.com');
        $this->command->info('Password: superadmin123');
        $this->command->info('Role: super_admin');
    }
}