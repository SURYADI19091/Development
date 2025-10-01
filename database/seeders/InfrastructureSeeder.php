<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Infrastructure;

class InfrastructureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $infrastructures = [
            // Education
            ['category' => 'education', 'name' => 'sd', 'label' => 'Sekolah Dasar (SD)', 'value' => 2, 'unit' => 'unit', 'order' => 1],
            ['category' => 'education', 'name' => 'mi', 'label' => 'Madrasah Ibtidaiyah (MI)', 'value' => 1, 'unit' => 'unit', 'order' => 2],
            ['category' => 'education', 'name' => 'paud_tk', 'label' => 'PAUD/TK', 'value' => 3, 'unit' => 'unit', 'order' => 3],
            ['category' => 'education', 'name' => 'pesantren', 'label' => 'Pondok Pesantren', 'value' => 1, 'unit' => 'unit', 'order' => 4],
            
            // Health
            ['category' => 'health', 'name' => 'puskesmas_pembantu', 'label' => 'Puskesmas Pembantu', 'value' => 1, 'unit' => 'unit', 'order' => 1],
            ['category' => 'health', 'name' => 'posyandu', 'label' => 'Posyandu', 'value' => 2, 'unit' => 'unit', 'order' => 2],
            ['category' => 'health', 'name' => 'poskesdes', 'label' => 'Poskesdes', 'value' => 1, 'unit' => 'unit', 'order' => 3],
            ['category' => 'health', 'name' => 'bidan', 'label' => 'Bidan Desa', 'value' => 3, 'unit' => 'orang', 'order' => 4],
            
            // Worship
            ['category' => 'worship', 'name' => 'masjid', 'label' => 'Masjid', 'value' => 4, 'unit' => 'unit', 'order' => 1],
            ['category' => 'worship', 'name' => 'mushola', 'label' => 'Mushola', 'value' => 8, 'unit' => 'unit', 'order' => 2],
            ['category' => 'worship', 'name' => 'gereja', 'label' => 'Gereja', 'value' => 1, 'unit' => 'unit', 'order' => 3],
            
            // Economy
            ['category' => 'economy', 'name' => 'pasar', 'label' => 'Pasar Tradisional', 'value' => 1, 'unit' => 'unit', 'order' => 1],
            ['category' => 'economy', 'name' => 'warung_toko', 'label' => 'Warung/Toko', 'value' => 15, 'unit' => 'unit', 'order' => 2],
            ['category' => 'economy', 'name' => 'koperasi', 'label' => 'Koperasi', 'value' => 2, 'unit' => 'unit', 'order' => 3],
            ['category' => 'economy', 'name' => 'bumdes', 'label' => 'BUMDes', 'value' => 1, 'unit' => 'unit', 'order' => 4],
            
            // Transportation
            ['category' => 'transportation', 'name' => 'jalan_aspal', 'label' => 'Jalan Aspal', 'value' => 8.5, 'unit' => 'km', 'order' => 1],
            ['category' => 'transportation', 'name' => 'jalan_beton', 'label' => 'Jalan Beton', 'value' => 2.3, 'unit' => 'km', 'order' => 2],
            ['category' => 'transportation', 'name' => 'jalan_tanah', 'label' => 'Jalan Tanah', 'value' => 1.2, 'unit' => 'km', 'order' => 3],
            ['category' => 'transportation', 'name' => 'jembatan', 'label' => 'Jembatan', 'value' => 2, 'unit' => 'unit', 'order' => 4],
            
            // Utilities
            ['category' => 'utilities', 'name' => 'listrik_pln', 'label' => 'Listrik PLN', 'value' => 100, 'unit' => '%', 'order' => 1],
            ['category' => 'utilities', 'name' => 'air_bersih', 'label' => 'Air Bersih', 'value' => 85, 'unit' => '%', 'order' => 2],
            ['category' => 'utilities', 'name' => 'telepon_internet', 'label' => 'Telepon/Internet', 'value' => 70, 'unit' => '%', 'order' => 3],
            ['category' => 'utilities', 'name' => 'bank_sampah', 'label' => 'Bank Sampah', 'value' => 1, 'unit' => 'unit', 'order' => 4],
        ];

        foreach ($infrastructures as $infrastructure) {
            Infrastructure::create($infrastructure);
        }
    }
}
