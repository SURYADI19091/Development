<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Announcement;

class AnnouncementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $announcements = [
            [
                'title' => 'Sistem Pelayanan Online Mulai 1 Oktober 2025',
                'content' => 'Dalam rangka peningkatan pelayanan publik, mulai tanggal 1 Oktober 2025 akan diberlakukan sistem pelayanan online untuk semua jenis surat keterangan. Warga dapat mengakses layanan melalui website desa atau datang langsung ke kantor desa. Kontak: Kepala Desa (0812-3456-7890)',
                'category' => 'pengumuman',
                'priority' => 'urgent',
                'is_active' => true,
                'valid_from' => '2025-09-27',
                'valid_until' => '2025-12-31',
                'created_by' => 1,
                'created_at' => now()->subDays(2),
            ],
            [
                'title' => 'Jadwal Pelayanan Administrasi Selama Libur Nasional',
                'content' => 'Sehubungan dengan libur nasional tanggal 28 September 2025 (Maulid Nabi Muhammad SAW), kantor desa akan tutup. Pelayanan akan kembali normal pada tanggal 30 September 2025. Kontak: Sekretaris Desa (0812-3456-7891)',
                'category' => 'pengumuman',
                'priority' => 'high',
                'is_active' => true,
                'valid_from' => '2025-09-27',
                'valid_until' => '2025-09-30',
                'created_by' => 1,
                'created_at' => now()->subDays(2),
            ],
            [
                'title' => 'Pendaftaran Bantuan Langsung Tunai (BLT) Dana Desa 2025',
                'content' => 'Pemerintah Desa membuka pendaftaran BLT Dana Desa untuk keluarga kurang mampu. Pendaftaran dibuka mulai 30 September hingga 15 Oktober 2025. Syarat: KTP, KK, Surat Keterangan Tidak Mampu dari RT/RW. Bantuan sebesar Rp 600.000 per Kepala Keluarga. Kontak: Kaur Kesra (0812-3456-7892)',
                'category' => 'pendaftaran',
                'priority' => 'high',
                'is_active' => true,
                'valid_from' => '2025-09-25',
                'valid_until' => '2025-10-15',
                'created_by' => 1,
                'created_at' => now()->subDays(4),
            ],
            [
                'title' => 'Rapat Pleno Penetapan DPT Pilkades Serentak 2025',
                'content' => 'Panitia Pemilihan Kepala Desa mengundang seluruh warga untuk menghadiri rapat pleno penetapan Daftar Pemilih Tetap (DPT) Pilkades serentak. Warga dapat mengecek dan mengajukan keberatan jika ada kesalahan data. Tempat: Balai Desa, Waktu: 1 Oktober 2025, 09:00 WIB. Kontak: Ketua Panitia Pilkades (0812-3456-7893)',
                'category' => 'kegiatan',
                'priority' => 'medium',
                'is_active' => true,
                'valid_from' => '2025-09-23',
                'valid_until' => '2025-10-01',
                'created_by' => 1,
                'created_at' => now()->subDays(6),
            ],
            [
                'title' => 'Lomba Kebersihan Lingkungan Antar RT Bulan Oktober',
                'content' => 'Dalam rangka memperingati Hari Sumpah Pemuda, Pemerintah Desa mengadakan lomba kebersihan lingkungan antar RT. Penilaian akan dilakukan tanggal 25-28 Oktober 2025. Hadiah total Rp 15.000.000 untuk 3 RT terbaik. Mari bersama-sama menjaga kebersihan lingkungan! Kontak: Seksi Lingkungan (0812-3456-7894)',
                'category' => 'lomba',
                'priority' => 'medium',
                'is_active' => true,
                'valid_from' => '2025-09-20',
                'valid_until' => '2025-10-28',
                'created_by' => 1,
                'created_at' => now()->subDays(9),
            ],
            [
                'title' => 'Perbaikan Jembatan Dusun III - Gangguan Lalu Lintas',
                'content' => 'Perbaikan jembatan penghubung Dusun III akan dilaksanakan mulai 2 Oktober 2025. Selama perbaikan, akan ada pengalihan jalur melalui Dusun II. Mohon pengertian dan kerja sama warga. Perbaikan diperkirakan selesai dalam 2 minggu. Kontak: Seksi Infrastruktur (0812-3456-7895)',
                'category' => 'infrastruktur',
                'priority' => 'high',
                'is_active' => true,
                'valid_from' => '2025-09-18',
                'valid_until' => '2025-10-16',
                'created_by' => 1,
                'created_at' => now()->subDays(11),
            ],
            [
                'title' => 'Program Vaksinasi COVID-19 Booster untuk Lansia',
                'content' => 'Puskesmas Telagasari bekerja sama dengan Pemerintah Desa mengadakan vaksinasi COVID-19 booster khusus lansia (60+ tahun). Pelaksanaan di Balai Desa, gratis. Daftar di RT masing-masing atau langsung datang ke lokasi. Waktu: 5 Oktober 2025, 08:00-12:00 WIB. Kontak: Petugas Kesehatan (0812-3456-7896)',
                'category' => 'kesehatan',
                'priority' => 'medium',
                'is_active' => true,
                'valid_from' => '2025-09-15',
                'valid_until' => '2025-10-05',
                'created_by' => 1,
                'created_at' => now()->subDays(14),
            ],
            [
                'title' => 'Pembentukan Karang Taruna Desa Periode 2025-2028',
                'content' => 'Pemerintah Desa mengundang pemuda-pemudi desa untuk bergabung dalam pembentukan Karang Taruna periode 2025-2028. Pendaftaran dibuka mulai 1-15 Oktober 2025. Syarat: Usia 17-40 tahun, berdomisili di desa, aktif dalam kegiatan kemasyarakatan. Kontak: Sekretaris Desa (0812-3456-7897)',
                'category' => 'sosial',
                'priority' => 'medium',
                'is_active' => true,
                'valid_from' => '2025-09-13',
                'valid_until' => '2025-10-15',
                'created_by' => 1,
                'created_at' => now()->subDays(16),
            ]
        ];

        foreach ($announcements as $announcement) {
            Announcement::create($announcement);
        }
    }
}