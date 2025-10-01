<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Agenda;
use Carbon\Carbon;

class AddMoreAgendaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $agendas = [
            [
                'title' => 'Sosialisasi Program Bantuan Sosial',
                'description' => 'Sosialisasi program bantuan sosial dari pemerintah pusat untuk masyarakat kurang mampu. Informasi tentang persyaratan, cara pendaftaran, dan mekanisme penyaluran bantuan.',
                'event_date' => Carbon::today()->addDays(7),
                'start_time' => '09:00:00',
                'end_time' => '12:00:00',
                'location' => 'Balai Desa Krandegan',
                'category' => 'pelayanan',
                'contact_person' => 'Sekdes',
                'contact_phone' => '08123456789',
                'is_public' => true,
                'is_completed' => false,
                'requirements' => 'Membawa KTP dan KK'
            ],
            [
                'title' => 'Rapat Evaluasi Kinerja Perangkat Desa',
                'description' => 'Rapat internal evaluasi kinerja perangkat desa untuk periode semester pertama. Membahas pencapaian target dan perencanaan program kerja semester kedua.',
                'event_date' => Carbon::today()->addDays(10),
                'start_time' => '14:00:00',
                'end_time' => '16:00:00',
                'location' => 'Ruang Rapat Balai Desa',
                'category' => 'rapat',
                'contact_person' => 'Sekdes',
                'contact_phone' => '08123456789',
                'is_public' => false,
                'is_completed' => false,
                'requirements' => 'Khusus perangkat desa'
            ],
            [
                'title' => 'Posyandu Balita dan Lansia',
                'description' => 'Pelayanan kesehatan rutin untuk balita dan lansia. Tersedia penimbangan, imunisasi, pemeriksaan kesehatan dasar, dan konsultasi gizi.',
                'event_date' => Carbon::today()->addDays(12),
                'start_time' => '08:00:00',
                'end_time' => '11:00:00',
                'location' => 'Posyandu Melati RT 02',
                'category' => 'pelayanan',
                'contact_person' => 'Bu Siti',
                'contact_phone' => '08987654321',
                'is_public' => true,
                'is_completed' => false,
                'requirements' => 'Bawa KMS/Buku Kesehatan'
            ],
            [
                'title' => 'Pelatihan Kewirausahaan UMKM',
                'description' => 'Pelatihan pengembangan usaha mikro kecil menengah untuk masyarakat. Materi meliputi manajemen usaha, pemasaran digital, dan akses permodalan.',
                'event_date' => Carbon::today()->addDays(18),
                'start_time' => '13:00:00',
                'end_time' => '17:00:00',
                'location' => 'Gedung Serbaguna Desa',
                'category' => 'lainnya',
                'contact_person' => 'Pak Budi',
                'contact_phone' => '08555666777',
                'is_public' => true,
                'is_completed' => false,
                'requirements' => 'Membawa alat tulis'
            ],
            [
                'title' => 'Senam Sehat Ibu-ibu PKK',
                'description' => 'Kegiatan senam aerobik rutin untuk ibu-ibu anggota PKK. Dipimpin oleh instruktur senam bersertifikat untuk menjaga kebugaran dan kesehatan.',
                'event_date' => Carbon::today()->addDays(21),
                'start_time' => '16:30:00',
                'end_time' => '17:30:00',
                'location' => 'Lapangan Desa Krandegan',
                'category' => 'olahraga',
                'contact_person' => 'Bu Wati',
                'contact_phone' => '08222333444',
                'is_public' => true,
                'is_completed' => false,
                'requirements' => 'Pakaian olahraga dan matras'
            ],

            [
                'title' => 'Pengajian Rutin Ibu-ibu Muslimah',
                'description' => 'Kajian rutin bulanan untuk ibu-ibu muslimah dengan tema "Akhlak dalam Keluarga". Dilengkapi dengan tausiyah dan diskusi keagamaan.',
                'event_date' => Carbon::today()->addDays(30),
                'start_time' => '19:00:00',
                'end_time' => '21:00:00',
                'location' => 'Masjid Al-Ikhlas',
                'category' => 'keagamaan',
                'contact_person' => 'Bu Aminah',
                'contact_phone' => '08777888999',
                'is_public' => true,
                'is_completed' => false,
                'requirements' => 'Membawa Al-Quran'
            ]
        ];

        foreach ($agendas as $agenda) {
            Agenda::create($agenda);
        }
    }
}