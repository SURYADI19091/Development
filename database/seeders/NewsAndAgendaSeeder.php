<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\News;
use App\Models\Agenda;
use Faker\Factory as Faker;
use Carbon\Carbon;

class NewsAndAgendaSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // Create sample news
        $newsData = [
            [
                'title' => 'Pembukaan Festival Desa Krandegan 2025',
                'content' => 'Festival tahunan Desa Krandegan akan dibuka pada bulan Oktober dengan berbagai kegiatan menarik termasuk pameran UMKM, pertunjukan seni tradisional, dan lomba-lomba berhadiah.',
                'views' => 2650
            ],
            [
                'title' => 'Program Bantuan Sosial Terbaru untuk Masyarakat',
                'content' => 'Pemerintah desa mengumumkan program bantuan sosial terbaru yang akan disalurkan kepada keluarga kurang mampu dan lansia di wilayah Desa Krandegan.',
                'views' => 1890
            ],
            [
                'title' => 'Pembangunan Jalan Desa Tahap II Dimulai',
                'content' => 'Proyek pembangunan jalan desa tahap kedua telah dimulai dengan target selesai pada akhir tahun 2025. Pembangunan ini akan meningkatkan akses transportasi masyarakat.',
                'views' => 1456
            ],
            [
                'title' => 'Pelatihan Keterampilan untuk Pemuda Desa',
                'content' => 'Karang taruna bekerja sama dengan dinas terkait mengadakan pelatihan keterampilan untuk pemuda desa dalam bidang teknologi informasi dan kewirausahaan.',
                'views' => 987
            ],
            [
                'title' => 'Penyuluhan Kesehatan dan Vaksinasi Gratis',
                'content' => 'Puskesmas setempat akan mengadakan penyuluhan kesehatan dan program vaksinasi gratis untuk seluruh masyarakat Desa Krandegan.',
                'views' => 756
            ]
        ];

        foreach ($newsData as $index => $data) {
            News::create([
                'title' => $data['title'],
                'slug' => \Str::slug($data['title']),
                'content' => $data['content'],
                'excerpt' => \Str::limit($data['content'], 100),
                'category' => 'kegiatan',
                'author_id' => null,
                'is_published' => true,
                'featured_image' => null,
                'views_count' => $data['views'],
                'published_at' => Carbon::now()->subDays($index * 3),
                'created_at' => Carbon::now()->subDays($index * 3),
                'updated_at' => Carbon::now()->subDays($index * 3)
            ]);
        }

        // Create sample agenda
        $agendaData = [
            [
                'title' => 'Rapat Koordinasi RT/RW',
                'description' => 'Rapat bulanan koordinasi antara RT/RW dengan perangkat desa untuk membahas program pembangunan dan kegiatan masyarakat.',
                'location' => 'Balai Desa Krandegan',
                'date' => Carbon::now()->addDays(3),
                'time' => '09:00:00'
            ],
            [
                'title' => 'Posyandu Balita dan Lansia',
                'description' => 'Kegiatan rutin posyandu untuk pemeriksaan kesehatan balita dan lansia serta pemberian vitamin.',
                'location' => 'Poskesdes Krandegan',
                'date' => Carbon::now()->addDays(7),
                'time' => '08:00:00'
            ],
            [
                'title' => 'Gotong Royong Kebersihan Desa',
                'description' => 'Kegiatan gotong royong membersihkan lingkungan desa, saluran air, dan fasilitas umum.',
                'location' => 'Seluruh Wilayah Desa',
                'date' => Carbon::now()->addDays(10),
                'time' => '07:00:00'
            ],
            [
                'title' => 'Pelatihan Pembuatan Kompos',
                'description' => 'Pelatihan pembuatan kompos dari sampah organik untuk mendukung program lingkungan hidup desa.',
                'location' => 'Taman Desa Krandegan',
                'date' => Carbon::now()->addDays(14),
                'time' => '14:00:00'
            ],
            [
                'title' => 'Festival Panen Raya',
                'description' => 'Perayaan festival panen dengan pameran hasil pertanian, kompetisi, dan hiburan rakyat.',
                'location' => 'Lapangan Desa',
                'date' => Carbon::now()->addDays(21),
                'time' => '16:00:00'
            ]
        ];

        foreach ($agendaData as $data) {
            Agenda::create([
                'title' => $data['title'],
                'description' => $data['description'],
                'location' => $data['location'],
                'event_date' => $data['date'],
                'start_time' => $data['time'],
                'category' => 'rapat',
                'organizer' => 'Pemerintah Desa Krandegan',
                'is_public' => true,
                'is_completed' => false,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
        }

        echo "Created " . count($newsData) . " news articles and " . count($agendaData) . " agenda items\n";
    }
}