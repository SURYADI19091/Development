<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CommunityInstitution;

class CommunityInstitutionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $institutions = [
            [
                'name' => 'PKK Desa',
                'type' => 'PKK',
                'leader_name' => 'Hj. Siti Maryam',
                'leader_title' => 'Ketua',
                'member_count' => 45,
                'description' => 'Pemberdayaan dan kesejahteraan keluarga, posyandu, dan kegiatan perempuan',
                'contact_phone' => '081234567902',
                'contact_email' => 'pkk.krandegan@email.com',
                'meeting_schedule' => 'Setiap Rabu minggu kedua',
                'icon_class' => 'fas fa-female',
                'color_class' => 'pink',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Karang Taruna',
                'type' => 'Karang Taruna',
                'leader_name' => 'Rizky Ramadhan',
                'leader_title' => 'Ketua',
                'member_count' => 62,
                'description' => 'Pemberdayaan pemuda, olahraga, seni budaya, dan kegiatan sosial',
                'contact_phone' => '081234567903',
                'contact_email' => 'karangtaruna.krandegan@email.com',
                'meeting_schedule' => 'Setiap Sabtu sore',
                'icon_class' => 'fas fa-users',
                'color_class' => 'indigo',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'LPMD',
                'type' => 'LPMD',
                'leader_name' => 'Drs. Agus Wahyudi',
                'leader_title' => 'Ketua',
                'member_count' => 15,
                'description' => 'Lembaga Pemberdayaan Masyarakat Desa untuk pembangunan partisipatif',
                'contact_phone' => '081234567904',
                'contact_email' => 'lpmd.krandegan@email.com',
                'meeting_schedule' => 'Setiap bulan minggu pertama',
                'icon_class' => 'fas fa-hands-helping',
                'color_class' => 'green',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'RT/RW',
                'type' => 'RT/RW',
                'leader_name' => 'Koordinator RT/RW',
                'leader_title' => 'Koordinator',
                'member_count' => 16, // 12 RT + 4 RW
                'description' => 'Pengelolaan administrasi kependudukan tingkat basis dan kegiatan kemasyarakatan',
                'contact_phone' => '081234567905',
                'meeting_schedule' => 'Sesuai kebutuhan',
                'icon_class' => 'fas fa-home',
                'color_class' => 'blue',
                'is_active' => true,
                'sort_order' => 4,
            ],
        ];

        foreach ($institutions as $institution) {
            CommunityInstitution::create($institution);
        }
    }
}
