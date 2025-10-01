<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\VillageOfficial;

class VillageOfficialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $officials = [
            // Kepala Desa
            [
                'name' => 'H. SUKARNO WIJAYA, S.Sos',
                'position' => 'kepala_desa',
                'nip' => '196801151990031008',
                'education' => 'S1 Ilmu Sosial',
                'work_period' => '2024-2029',
                'phone' => '081234567890',
                'email' => 'kepdes.krandegan@email.com',
                'specialization' => 'Administrasi Pemerintahan',
                'is_active' => true,
                'start_date' => '2024-01-01',
                'end_date' => '2029-12-31',
            ],
            
            // Sekretaris Desa
            [
                'name' => 'SITI NURJANAH, S.AP',
                'position' => 'sekretaris_desa',
                'nip' => '197805152006042009',
                'education' => 'S1 Administrasi Publik',
                'work_period' => '2006-sekarang',
                'phone' => '081234567891',
                'email' => 'sekdes.krandegan@email.com',
                'specialization' => 'Administrasi dan Kesekretariatan',
                'is_active' => true,
                'start_date' => '2006-04-15',
            ],
            
            // Kaur Pemerintahan
            [
                'name' => 'AHMAD FAUZI, S.H',
                'position' => 'kaur_pemerintahan',
                'nip' => '198203102009031015',
                'education' => 'S1 Hukum',
                'work_period' => '2009-sekarang',
                'phone' => '081234567892',
                'email' => 'pemerintahan.krandegan@email.com',
                'specialization' => 'Administrasi Umum, Kependudukan, Pertanahan',
                'is_active' => true,
                'start_date' => '2009-03-10',
            ],
            
            // Kaur Keuangan
            [
                'name' => 'RINA SURYANI, S.E',
                'position' => 'kaur_keuangan',
                'nip' => '198507212010042018',
                'education' => 'S1 Ekonomi',
                'work_period' => '2010-sekarang',
                'phone' => '081234567893',
                'email' => 'keuangan.krandegan@email.com',
                'specialization' => 'Pengelolaan Keuangan, Perencanaan & Pelaporan, Aset Desa',
                'is_active' => true,
                'start_date' => '2010-04-21',
            ],
            
            // Kaur Pelayanan
            [
                'name' => 'INDAH PERMATA, S.Sos',
                'position' => 'kaur_pelayanan',
                'nip' => '198912052015042011',
                'education' => 'S1 Ilmu Sosial',
                'work_period' => '2015-sekarang',
                'phone' => '081234567894',
                'email' => 'pelayanan.krandegan@email.com',
                'specialization' => 'Pelayanan Umum, Kesra & Pemberdayaan, Kesehatan',
                'is_active' => true,
                'start_date' => '2015-04-05',
            ],
            
            // Kepala Dusun I
            [
                'name' => 'BAMBANG SUTRISNO',
                'position' => 'kadus',
                'education' => 'SMA',
                'work_period' => '2020-sekarang',
                'phone' => '081234567895',
                'specialization' => 'Pembinaan Masyarakat Dusun',
                'work_area' => 'Dusun I (RT 01, RT 02, RT 03)',
                'is_active' => true,
                'start_date' => '2020-01-15',
            ],
            
            // Kepala Dusun II
            [
                'name' => 'SARTONO WIJAYA',
                'position' => 'kadus',
                'education' => 'SMA',
                'work_period' => '2019-sekarang',
                'phone' => '081234567896',
                'specialization' => 'Pembinaan Masyarakat Dusun',
                'work_area' => 'Dusun II (RT 04, RT 05, RT 06)',
                'is_active' => true,
                'start_date' => '2019-08-10',
            ],
            
            // Kepala Dusun III
            [
                'name' => 'HERI SETIAWAN',
                'position' => 'kadus',
                'education' => 'SMA',
                'work_period' => '2021-sekarang',
                'phone' => '081234567897',
                'specialization' => 'Pembinaan Masyarakat Dusun',
                'work_area' => 'Dusun III (RT 07, RT 08, RT 09)',
                'is_active' => true,
                'start_date' => '2021-03-01',
            ],
            
            // Kepala Dusun IV
            [
                'name' => 'JOKO PRIYANTO',
                'position' => 'kadus',
                'education' => 'SMA',
                'work_period' => '2018-sekarang',
                'phone' => '081234567898',
                'specialization' => 'Pembinaan Masyarakat Dusun',
                'work_area' => 'Dusun IV (RT 10, RT 11, RT 12)',
                'is_active' => true,
                'start_date' => '2018-05-20',
            ],
        ];

        foreach ($officials as $official) {
            VillageOfficial::create($official);
        }

        // BPD Members
        $bpd_members = [
            [
                'name' => 'H. ABDUL RAHMAN',
                'position' => 'staff',
                'education' => 'SMA',
                'work_period' => '2024-2029',
                'phone' => '081234567899',
                'specialization' => 'Ketua BPD',
                'is_active' => true,
                'start_date' => '2024-01-01',
                'end_date' => '2029-12-31',
            ],
            [
                'name' => 'SARIFUDDIN, S.Pd',
                'position' => 'staff',
                'education' => 'S1 Pendidikan',
                'work_period' => '2024-2029',
                'phone' => '081234567900',
                'specialization' => 'Wakil Ketua BPD',
                'is_active' => true,
                'start_date' => '2024-01-01',
                'end_date' => '2029-12-31',
            ],
            [
                'name' => 'SRI LESTARI, S.H',
                'position' => 'staff',
                'education' => 'S1 Hukum',
                'work_period' => '2024-2029',
                'phone' => '081234567901',
                'specialization' => 'Sekretaris BPD',
                'is_active' => true,
                'start_date' => '2024-01-01',
                'end_date' => '2029-12-31',
            ],
        ];

        foreach ($bpd_members as $member) {
            VillageOfficial::create($member);
        }
    }
}
