<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Settlement;

class SettlementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat 2 settlement data
        Settlement::create([
            'name' => 'Dusun I Krandegan',
            'type' => 'Dusun',
            'code' => 'KRG-DS1',
            'description' => 'Dusun I merupakan wilayah bagian utara Desa Krandegan dengan mayoritas penduduk bermata pencaharian sebagai petani padi.',
            'hamlet_name' => 'Dusun I',
            'hamlet_leader' => 'Bapak Sutrisno',
            'neighborhood_name' => 'RW 01',
            'neighborhood_number' => 1,
            'community_name' => 'RT 01, 02, 03',
            'community_number' => 3,
            'district' => 'Telagasari',
            'regency' => 'Karawang',
            'province' => 'Jawa Barat',
            'area_size' => 185.50,
            'population' => 1500,
            'postal_code' => '41361',
            'latitude' => -6.235000,
            'longitude' => 107.270000,
            'is_active' => true,
        ]);

        Settlement::create([
            'name' => 'Dusun II Krandegan',
            'type' => 'Dusun',
            'code' => 'KRG-DS2',
            'description' => 'Dusun II merupakan wilayah bagian selatan Desa Krandegan dengan akses jalan yang baik dan terdapat pasar tradisional.',
            'hamlet_name' => 'Dusun II',
            'hamlet_leader' => 'Bapak Widodo',
            'neighborhood_name' => 'RW 02',
            'neighborhood_number' => 2,
            'community_name' => 'RT 04, 05, 06',
            'community_number' => 3,
            'district' => 'Telagasari',
            'regency' => 'Karawang',
            'province' => 'Jawa Barat',
            'area_size' => 300.26,
            'population' => 1532,
            'postal_code' => '41361',
            'latitude' => -6.242000,
            'longitude' => 107.277000,
            'is_active' => true,
        ]);
    }
}
