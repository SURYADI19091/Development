<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\BudgetTransaction;
use App\Models\VillageBudget;

class BudgetTransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some budget records to link transactions to
        $budgets = VillageBudget::all();
        
        if ($budgets->isEmpty()) {
            $this->command->info('No budget records found. Please run VillageBudgetSeeder first.');
            return;
        }

        $transactions = [
            // Income transactions
            [
                'budget_id' => $budgets->where('category', 'Dana Desa')->first()?->id,
                'transaction_date' => '2025-01-15',
                'description' => 'Transfer Dana Desa Tahap I',
                'amount' => 400000000.00,
                'transaction_type' => 'income',
                'reference_number' => 'DD-2025-001',
                'notes' => 'Transfer tahap pertama dari Pemerintah Pusat',
                'created_by' => 1,
            ],
            [
                'budget_id' => $budgets->where('category', 'Alokasi Dana Desa')->first()?->id,
                'transaction_date' => '2025-02-01',
                'description' => 'Transfer ADD Tahap I',
                'amount' => 90000000.00,
                'transaction_type' => 'income',
                'reference_number' => 'ADD-2025-001',
                'notes' => 'Transfer tahap pertama dari Pemerintah Daerah',
                'created_by' => 1,
            ],
            
            // Expense transactions
            [
                'budget_id' => $budgets->where('category', 'Belanja Pegawai')->first()?->id,
                'transaction_date' => '2025-01-31',
                'description' => 'Gaji Perangkat Desa Januari 2025',
                'amount' => 25000000.00,
                'transaction_type' => 'expense',
                'reference_number' => 'GP-2025-001',
                'notes' => 'Pembayaran gaji bulan Januari',
                'created_by' => 1,
            ],
            [
                'budget_id' => $budgets->where('category', 'Belanja Barang dan Jasa')->first()?->id,
                'transaction_date' => '2025-01-15',
                'description' => 'Pembelian ATK dan Supplies',
                'amount' => 5000000.00,
                'transaction_type' => 'expense',
                'reference_number' => 'ATK-2025-001',
                'notes' => 'Pembelian alat tulis dan perlengkapan kantor',
                'created_by' => 1,
            ],
            [
                'budget_id' => $budgets->where('category', 'Belanja Modal')->first()?->id,
                'transaction_date' => '2025-02-15',
                'description' => 'Pembangunan Jalan Desa RT 01',
                'amount' => 150000000.00,
                'transaction_type' => 'expense',
                'reference_number' => 'JD-2025-001',
                'notes' => 'Pembangunan jalan perkerasan tahap I',
                'created_by' => 1,
            ],
            [
                'budget_id' => $budgets->where('category', 'Belanja Pemberdayaan')->first()?->id,
                'transaction_date' => '2025-01-20',
                'description' => 'Bantuan Sosial Lansia',
                'amount' => 15000000.00,
                'transaction_type' => 'expense',
                'reference_number' => 'BSL-2025-001',
                'notes' => 'Bantuan bulanan untuk 100 lansia @ Rp 150.000',
                'created_by' => 1,
            ],
        ];

        foreach ($transactions as $transaction) {
            if ($transaction['budget_id']) {
                BudgetTransaction::create($transaction);
            }
        }
    }
}