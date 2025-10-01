<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Location;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $locations = [
            [
                'name' => 'Kantor Desa Ciwulan',
                'description' => 'Kantor pemerintahan desa untuk pelayanan administrasi warga',
                'type' => 'office',
                'latitude' => -6.258346,
                'longitude' => 107.435520,
                'address' => 'Jl. Raya Desa No. 1, Ciwulan, Telagasari, Karawang',
                'phone' => '0267-123456',
                'email' => 'kantor@ciwulan.desa.id',
                'operating_hours' => [
                    'senin' => '08:00-16:00',
                    'selasa' => '08:00-16:00',
                    'rabu' => '08:00-16:00',
                    'kamis' => '08:00-16:00',
                    'jumat' => '08:00-16:00',
                    'sabtu' => 'Tutup',
                    'minggu' => 'Tutup'
                ],
                'icon' => 'fas fa-building',
                'color' => 'blue',
                'is_active' => true,
                'show_on_map' => true,
                'sort_order' => 1,
                'created_by' => 1,
            ],
            [
                'name' => 'SDN Ciwulan 01',
                'description' => 'Sekolah Dasar Negeri untuk pendidikan anak-anak desa',
                'type' => 'school',
                'latitude' => -6.259000,
                'longitude' => 107.436000,
                'address' => 'Jl. Pendidikan No. 5, Ciwulan, Telagasari, Karawang',
                'phone' => '0267-123457',
                'email' => 'sdn.ciwulan01@gmail.com',
                'operating_hours' => [
                    'senin' => '07:00-12:00',
                    'selasa' => '07:00-12:00',
                    'rabu' => '07:00-12:00',
                    'kamis' => '07:00-12:00',
                    'jumat' => '07:00-11:00',
                    'sabtu' => 'Tutup',
                    'minggu' => 'Tutup'
                ],
                'icon' => 'fas fa-school',
                'color' => 'green',
                'is_active' => true,
                'show_on_map' => true,
                'sort_order' => 2,
                'created_by' => 1,
            ],
            [
                'name' => 'Puskesmas Pembantu Ciwulan',
                'description' => 'Pusat kesehatan masyarakat untuk pelayanan kesehatan dasar',
                'type' => 'health',
                'latitude' => -6.257800,
                'longitude' => 107.434800,
                'address' => 'Jl. Kesehatan No. 12, Ciwulan, Telagasari, Karawang',
                'phone' => '0267-123458',
                'email' => 'pustu.ciwulan@dinkes.karawang.go.id',
                'operating_hours' => [
                    'senin' => '08:00-15:00',
                    'selasa' => '08:00-15:00',
                    'rabu' => '08:00-15:00',
                    'kamis' => '08:00-15:00',
                    'jumat' => '08:00-15:00',
                    'sabtu' => '08:00-12:00',
                    'minggu' => 'Tutup'
                ],
                'icon' => 'fas fa-hospital',
                'color' => 'red',
                'is_active' => true,
                'show_on_map' => true,
                'sort_order' => 3,
                'created_by' => 1,
            ],
            [
                'name' => 'Masjid Al-Ikhlas',
                'description' => 'Masjid utama desa untuk kegiatan ibadah dan keagamaan',
                'type' => 'religious',
                'latitude' => -6.258800,
                'longitude' => 107.436200,
                'address' => 'Jl. Masjid No. 3, Ciwulan, Telagasari, Karawang',
                'phone' => '0267-123459',
                'operating_hours' => [
                    'senin' => '05:00-21:00',
                    'selasa' => '05:00-21:00',
                    'rabu' => '05:00-21:00',
                    'kamis' => '05:00-21:00',
                    'jumat' => '05:00-21:00',
                    'sabtu' => '05:00-21:00',
                    'minggu' => '05:00-21:00'
                ],
                'icon' => 'fas fa-mosque',
                'color' => 'purple',
                'is_active' => true,
                'show_on_map' => true,
                'sort_order' => 4,
                'created_by' => 1,
            ],
            [
                'name' => 'Pasar Desa Ciwulan',
                'description' => 'Pasar tradisional untuk kebutuhan sehari-hari warga',
                'type' => 'commercial',
                'latitude' => -6.257000,
                'longitude' => 107.435000,
                'address' => 'Jl. Pasar No. 8, Ciwulan, Telagasari, Karawang',
                'phone' => '0267-123460',
                'operating_hours' => [
                    'senin' => '06:00-17:00',
                    'selasa' => '06:00-17:00',
                    'rabu' => '06:00-17:00',
                    'kamis' => '06:00-17:00',
                    'jumat' => '06:00-17:00',
                    'sabtu' => '06:00-17:00',
                    'minggu' => '06:00-12:00'
                ],
                'icon' => 'fas fa-shopping-basket',
                'color' => 'orange',
                'is_active' => true,
                'show_on_map' => true,
                'sort_order' => 5,
                'created_by' => 1,
            ],
            [
                'name' => 'Balai Desa Ciwulan',
                'description' => 'Balai untuk kegiatan sosial dan pertemuan warga',
                'type' => 'public',
                'latitude' => -6.258500,
                'longitude' => 107.435700,
                'address' => 'Jl. Raya Desa No. 2, Ciwulan, Telagasari, Karawang',
                'phone' => '0267-123461',
                'operating_hours' => [
                    'senin' => '08:00-21:00',
                    'selasa' => '08:00-21:00',
                    'rabu' => '08:00-21:00',
                    'kamis' => '08:00-21:00',
                    'jumat' => '08:00-21:00',
                    'sabtu' => '08:00-21:00',
                    'minggu' => '08:00-21:00'
                ],
                'icon' => 'fas fa-users',
                'color' => 'indigo',
                'is_active' => true,
                'show_on_map' => true,
                'sort_order' => 6,
                'created_by' => 1,
            ]
        ];

        foreach ($locations as $location) {
            Location::create($location);
        }
    }
}