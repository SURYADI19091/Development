<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\VillageBudget;

class VillageBudgetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $budgets = [
            // Income/Revenue (Pendapatan)
            [
                'fiscal_year' => 2025,
                'budget_type' => 'pendapatan',
                'category' => 'Dana Desa',
                'sub_category' => 'Transfer Pemerintah Pusat',
                'description' => 'Dana Desa dari Pemerintah Pusat',
                'planned_amount' => 800000000.00,
                'realized_amount' => 750000000.00,
                'created_by' => 1,
            ],
            [
                'fiscal_year' => 2025,
                'budget_type' => 'pendapatan',
                'category' => 'Alokasi Dana Desa',
                'sub_category' => 'Transfer Pemerintah Daerah',
                'description' => 'Alokasi Dana Desa dari Pemerintah Daerah',
                'planned_amount' => 200000000.00,
                'realized_amount' => 180000000.00,
                'created_by' => 1,
            ],
            [
                'fiscal_year' => 2025,
                'budget_type' => 'pendapatan',
                'category' => 'PADes',
                'sub_category' => 'Pendapatan Asli Desa',
                'description' => 'Hasil Usaha BUMDes dan Retribusi',
                'planned_amount' => 50000000.00,
                'realized_amount' => 35000000.00,
                'created_by' => 1,
            ],
            
            // Expenses (Belanja)
            [
                'fiscal_year' => 2025,
                'budget_type' => 'belanja',
                'category' => 'Penyelenggaraan Pemerintahan',
                'sub_category' => 'Gaji dan Tunjangan',
                'description' => 'Gaji Perangkat Desa dan Tunjangan',
                'planned_amount' => 300000000.00,
                'realized_amount' => 280000000.00,
                'created_by' => 1,
            ],
            [
                'fiscal_year' => 2025,
                'budget_type' => 'belanja',
                'category' => 'Pelaksanaan Pembangunan',
                'sub_category' => 'Infrastruktur Desa',
                'description' => 'Pembangunan Jalan dan Jembatan',
                'planned_amount' => 400000000.00,
                'realized_amount' => 300000000.00,
                'created_by' => 1,
            ],
            [
                'fiscal_year' => 2025,
                'budget_type' => 'belanja',
                'category' => 'Pembinaan Kemasyarakatan',
                'sub_category' => 'Program Sosial',
                'description' => 'Bantuan Sosial dan Pemberdayaan Masyarakat',
                'planned_amount' => 150000000.00,
                'realized_amount' => 120000000.00,
                'created_by' => 1,
            ],
            [
                'fiscal_year' => 2025,
                'budget_type' => 'belanja',
                'category' => 'Pemberdayaan Masyarakat',
                'sub_category' => 'Operasional Perkantoran',
                'description' => 'ATK, Listrik, Air, Telepon, dan Internet',
                'planned_amount' => 100000000.00,
                'realized_amount' => 85000000.00,
                'created_by' => 1,
            ],
            [
                'fiscal_year' => 2025,
                'budget_type' => 'belanja',
                'category' => 'Belanja Tak Terduga',
                'sub_category' => 'Dana Darurat',
                'description' => 'Alokasi untuk Keadaan Darurat',
                'planned_amount' => 50000000.00,
                'realized_amount' => 15000000.00,
                'created_by' => 1,
            ],
        ];

        foreach ($budgets as $budget) {
            VillageBudget::create($budget);
        }
    }
}