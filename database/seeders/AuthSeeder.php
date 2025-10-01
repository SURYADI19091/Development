<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AuthSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Super Admin
        User::updateOrCreate(
            ['email' => 'superadmin@ciuwlan.desa.id'],
            [
                'name' => 'Super Administrator',
                'email' => 'superadmin@ciuwlan.desa.id',
                'phone' => '081234567890',
                'password' => Hash::make('SuperAdmin123!'),
                'role' => 'super_admin',
                'status' => 'active',
                'email_verified_at' => now(),
                'registered_at' => now(),
                'registered_ip' => '127.0.0.1',
                'user_agent' => 'Seeder',
            ]
        );

        // Create Admin
        User::updateOrCreate(
            ['email' => 'admin@ciuwlan.desa.id'],
            [
                'name' => 'Administrator Desa',
                'email' => 'admin@ciuwlan.desa.id',
                'phone' => '081234567891',
                'password' => Hash::make('Admin123!'),
                'role' => 'admin',
                'status' => 'active',
                'email_verified_at' => now(),
                'registered_at' => now(),
                'registered_ip' => '127.0.0.1',
                'user_agent' => 'Seeder',
            ]
        );

        // Create Village Officers
        $officers = [
            [
                'name' => 'Kepala Desa Ciuwlan',
                'email' => 'kades@ciuwlan.desa.id',
                'phone' => '081234567892',
                'role' => 'village_officer',
            ],
            [
                'name' => 'Sekretaris Desa',
                'email' => 'sekdes@ciuwlan.desa.id',
                'phone' => '081234567893',
                'role' => 'village_officer',
            ],
            [
                'name' => 'Kaur Keuangan',
                'email' => 'keuangan@ciuwlan.desa.id',
                'phone' => '081234567894',
                'role' => 'finance_officer',
            ],
            [
                'name' => 'Kaur Pelayanan',
                'email' => 'pelayanan@ciuwlan.desa.id',
                'phone' => '081234567895',
                'role' => 'service_officer',
            ],
            [
                'name' => 'Kaur Umum',
                'email' => 'umum@ciuwlan.desa.id',
                'phone' => '081234567896',
                'role' => 'cs_officer',
            ],
        ];

        foreach ($officers as $officer) {
            User::updateOrCreate(
                ['email' => $officer['email']],
                array_merge($officer, [
                    'password' => Hash::make('Officer123!'),
                    'status' => 'active',
                    'email_verified_at' => now(),
                    'registered_at' => now(),
                    'registered_ip' => '127.0.0.1',
                    'user_agent' => 'Seeder',
                ])
            );
        }

        // Create sample regular users
        $users = [
            [
                'name' => 'Budi Santoso',
                'email' => 'budi@example.com',
                'phone' => '081234567897',
                'role' => 'resident',
                'status' => 'active',
            ],
            [
                'name' => 'Siti Nurhaliza',
                'email' => 'siti@example.com',
                'phone' => '081234567898',
                'role' => 'member',
                'status' => 'active',
            ],
            [
                'name' => 'Ahmad Fauzi',
                'email' => 'ahmad@example.com',
                'phone' => '081234567899',
                'role' => 'user',
                'status' => 'pending',
            ],
        ];

        foreach ($users as $userData) {
            User::updateOrCreate(
                ['email' => $userData['email']],
                array_merge($userData, [
                    'password' => Hash::make('User123!'),
                    'email_verified_at' => $userData['status'] === 'active' ? now() : null,
                    'registered_at' => now()->subDays(rand(1, 30)),
                    'registered_ip' => '192.168.1.' . rand(1, 254),
                    'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                ])
            );
        }

        $this->command->info('Auth seeder completed successfully!');
        $this->command->info('Super Admin: superadmin@ciuwlan.desa.id / SuperAdmin123!');
        $this->command->info('Admin: admin@ciuwlan.desa.id / Admin123!');
        $this->command->info('Officers: {role}@ciuwlan.desa.id / Officer123!');
        $this->command->info('Users: {name}@example.com / User123!');
    }
}