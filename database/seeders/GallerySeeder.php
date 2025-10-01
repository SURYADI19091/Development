<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Gallery;

class GallerySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $galleries = [
            [
                'title' => 'Pembangunan Jalan Desa',
                'description' => 'Dokumentasi pembangunan jalan utama desa untuk meningkatkan akses transportasi.',
                'image_path' => 'galleries/jalan-desa.jpg',
                'category' => 'infrastruktur',
                'photographer' => 'Tim Dokumentasi Desa',
                'location' => 'Jalan Utama Desa',
                'taken_at' => '2025-09-15',
                'views_count' => 125,
                'likes_count' => 25,
                'is_featured' => true,
                'uploaded_by' => 1,
            ],
            [
                'title' => 'Sistem Air Bersih',
                'description' => 'Pemasangan sistem air bersih untuk seluruh warga desa.',
                'image_path' => 'galleries/air-bersih.jpg',
                'category' => 'infrastruktur',
                'photographer' => 'Tim Dokumentasi Desa',
                'location' => 'Area Perumahan',
                'taken_at' => '2025-09-10',
                'views_count' => 98,
                'likes_count' => 18,
                'is_featured' => false,
                'uploaded_by' => 1,
            ],
            [
                'title' => 'Renovasi Balai Desa',
                'description' => 'Proses renovasi balai desa untuk meningkatkan fasilitas pelayanan.',
                'image_path' => 'galleries/balai-desa.jpg',
                'category' => 'infrastruktur',
                'photographer' => 'Tim Dokumentasi Desa',
                'location' => 'Balai Desa',
                'taken_at' => '2025-09-05',
                'views_count' => 156,
                'likes_count' => 32,
                'is_featured' => true,
                'uploaded_by' => 1,
            ],
            [
                'title' => 'Program Pemberdayaan UMKM',
                'description' => 'Kegiatan pelatihan dan pemberdayaan usaha mikro kecil menengah.',
                'image_path' => 'galleries/umkm-training.jpg',
                'category' => 'kegiatan',
                'photographer' => 'Tim Dokumentasi Desa',
                'location' => 'Balai Desa',
                'taken_at' => '2025-09-01',
                'views_count' => 87,
                'likes_count' => 22,
                'is_featured' => false,
                'uploaded_by' => 1,
            ],
            [
                'title' => 'Festival Budaya Desa',
                'description' => 'Perayaan festival budaya tahunan dengan berbagai pertunjukan seni.',
                'image_path' => 'galleries/festival-budaya.jpg',
                'category' => 'budaya',
                'photographer' => 'Tim Dokumentasi Desa',
                'location' => 'Lapangan Desa',
                'taken_at' => '2025-08-25',
                'views_count' => 234,
                'likes_count' => 45,
                'is_featured' => true,
                'uploaded_by' => 1,
            ],
            [
                'title' => 'Pengembangan Wisata Desa',
                'description' => 'Pembangunan fasilitas wisata untuk meningkatkan ekonomi desa.',
                'image_path' => 'galleries/wisata-desa.jpg',
                'category' => 'wisata',
                'photographer' => 'Tim Dokumentasi Desa',
                'location' => 'Area Wisata',
                'taken_at' => '2025-08-20',
                'views_count' => 167,
                'likes_count' => 28,
                'is_featured' => false,
                'uploaded_by' => 1,
            ]
        ];

        foreach ($galleries as $gallery) {
            Gallery::create($gallery);
        }
    }
}