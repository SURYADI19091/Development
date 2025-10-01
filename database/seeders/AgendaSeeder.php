<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Agenda;
use Carbon\Carbon;

class AgendaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $agendas = [
            [
                'title' => 'Rapat Koordinasi RT/RW',
                'description' => 'Pembahasan program kerja bulan Oktober dan evaluasi kegiatan September',
                'category' => 'rapat',
                'event_date' => Carbon::today(),
                'start_time' => '09:00:00',
                'end_time' => '11:00:00',
                'location' => 'Balai Desa',
                'organizer' => 'Sekretaris Desa',
                'priority' => 'medium',
                'max_participants' => 50,
                'current_participants' => 0,
                'requirements' => 'Membawa alat tulis',
                'contact_person' => 'Pak Sekretaris',
                'contact_phone' => '08123456789',
                'is_public' => true,
                'is_completed' => false,
            ],
            [
                'title' => 'Musyawarah Desa Penyusunan RKP Desa 2026',
                'description' => 'Musyawarah perencanaan pembangunan desa tahun 2026 melibatkan seluruh elemen masyarakat untuk menentukan prioritas pembangunan dan program kerja desa.',
                'category' => 'rapat',
                'event_date' => Carbon::tomorrow(),
                'start_time' => '08:00:00',
                'end_time' => '12:00:00',
                'location' => 'Balai Desa Krandegan',
                'organizer' => 'Kepala Desa',
                'priority' => 'high',
                'max_participants' => 100,
                'current_participants' => 0,
                'requirements' => 'Terbuka untuk umum',
                'contact_person' => 'Kepala Desa',
                'contact_phone' => '08123456788',
                'is_public' => true,
                'is_completed' => false,
            ],
            [
                'title' => 'Pelayanan Pembuatan E-KTP Keliling',
                'description' => 'Tim Disdukcapil Kabupaten akan melayani pembuatan e-KTP langsung di desa. Warga yang belum memiliki e-KTP atau perlu perpanjangan dapat memanfaatkan layanan ini.',
                'category' => 'pelayanan',
                'event_date' => Carbon::parse('2025-10-01'),
                'start_time' => '08:00:00',
                'end_time' => '16:00:00',
                'location' => 'Halaman Balai Desa',
                'organizer' => 'Disdukcapil Karawang',
                'priority' => 'high',
                'max_participants' => 200,
                'current_participants' => 0,
                'requirements' => 'Bawa KK & Akta Lahir',
                'contact_person' => 'Kaur Pelayanan',
                'contact_phone' => '08123456787',
                'is_public' => true,
                'is_completed' => false,
            ],
            [
                'title' => 'Turnamen Bulu Tangkis Antar RT',
                'description' => 'Turnamen bulu tangkis dalam rangka memeriahkan Hari Sumpah Pemuda. Terbuka untuk kategori remaja, dewasa, dan veteran. Hadiah menarik untuk para juara.',
                'category' => 'olahraga',
                'event_date' => Carbon::parse('2025-10-03'),
                'start_time' => '07:00:00',
                'end_time' => '17:00:00',
                'location' => 'GOR Desa Krandegan',
                'organizer' => 'Karang Taruna',
                'priority' => 'medium',
                'max_participants' => 64,
                'current_participants' => 0,
                'requirements' => 'Hadiah Rp 5.000.000',
                'contact_person' => 'Ketua Karang Taruna',
                'contact_phone' => '08123456786',
                'is_public' => true,
                'is_completed' => false,
            ],
            [
                'title' => 'Kerja Bakti Membersihkan Saluran Air',
                'description' => 'Gotong royong membersihkan saluran air dan drainase desa sebagai persiapan musim penghujan. Seluruh warga diharapkan berpartisipasi aktif.',
                'category' => 'gotong_royong',
                'event_date' => Carbon::parse('2025-10-05'),
                'start_time' => '06:00:00',
                'end_time' => '10:00:00',
                'location' => 'Seluruh Wilayah Desa',
                'organizer' => 'RT/RW',
                'priority' => 'high',
                'max_participants' => 500,
                'current_participants' => 0,
                'requirements' => 'Bawa alat bersih-bersih',
                'contact_person' => 'Ketua RT',
                'contact_phone' => '08123456785',
                'is_public' => true,
                'is_completed' => false,
            ],
            [
                'title' => 'Peringatan Maulid Nabi Muhammad SAW',
                'description' => 'Acara peringatan Maulid Nabi Muhammad SAW dengan ceramah agama, pembacaan sholawat, dan santunan anak yatim. Seluruh warga muslim diundang untuk hadir.',
                'category' => 'keagamaan',
                'event_date' => Carbon::parse('2025-10-10'),
                'start_time' => '19:30:00',
                'end_time' => '22:00:00',
                'location' => 'Masjid Al-Ikhlas',
                'organizer' => 'Takmir Masjid',
                'priority' => 'medium',
                'max_participants' => 300,
                'current_participants' => 0,
                'requirements' => 'Santunan Anak Yatim',
                'contact_person' => 'Ketua Takmir',
                'contact_phone' => '08123456784',
                'is_public' => true,
                'is_completed' => false,
            ],
            [
                'title' => 'Pelatihan Komputer untuk Warga',
                'description' => 'Pelatihan dasar komputer dan internet untuk meningkatkan literasi digital warga desa.',
                'category' => 'lainnya',
                'event_date' => Carbon::parse('2025-10-15'),
                'start_time' => '13:00:00',
                'end_time' => '16:00:00',
                'location' => 'Balai Desa',
                'organizer' => 'Dinas Kominfo',
                'priority' => 'medium',
                'max_participants' => 30,
                'current_participants' => 0,
                'requirements' => 'Bawa laptop jika ada',
                'contact_person' => 'Kaur Pelayanan',
                'contact_phone' => '08123456783',
                'is_public' => true,
                'is_completed' => false,
            ],
        ];

        foreach ($agendas as $agenda) {
            Agenda::create($agenda);
        }
    }
}
