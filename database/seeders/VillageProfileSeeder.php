<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\VillageProfile;

class VillageProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        VillageProfile::create([
            'village_name' => 'Desa Krandegan',
            'district' => 'Telagasari',
            'regency' => 'Karawang',
            'province' => 'Jawa Barat',
            'village_code' => '32.15.08.2008',
            'postal_code' => '41361',
            'area_size' => 485.76, // dalam hektar
            'total_population' => 3032,
            'total_families' => 986,
            'male_population' => 1542,
            'female_population' => 1490,
            'latitude' => -6.2384,
            'longitude' => 107.2734,
            'altitude' => '15-25 mdpl',
            'topography' => 'Dataran rendah',
            'north_border' => 'Desa Kutamekar',
            'south_border' => 'Desa Telagasari',
            'east_border' => 'Desa Sukamandi',
            'west_border' => 'Desa Margamukti',
            'description' => 'Desa Krandegan adalah salah satu desa yang terletak di Kecamatan Telagasari, Kabupaten Karawang, Provinsi Jawa Barat. Desa ini memiliki luas wilayah 485.76 hektar dengan jumlah penduduk sebanyak 3.032 jiwa yang terdiri dari 986 kepala keluarga. Desa Krandegan memiliki potensi yang cukup baik dalam bidang pertanian, dengan mayoritas penduduknya bermata pencaharian sebagai petani dan buruh tani. Wilayah desa ini sebagian besar merupakan lahan pertanian yang subur dengan sistem pengairan yang cukup memadai. Sebagai bagian dari Kabupaten Karawang yang dikenal sebagai lumbung padi Jawa Barat, Desa Krandegan turut berkontribusi dalam pemenuhan kebutuhan pangan nasional melalui produksi padi dan komoditas pertanian lainnya.',
            'vision' => 'Terwujudnya Desa Krandegan yang maju, mandiri, dan sejahtera berdasarkan potensi sumber daya lokal dan kearifan budaya.',
            'mission' => 'Meningkatkan kualitas pelayanan publik, mengembangkan potensi ekonomi desa, memberdayakan masyarakat, dan melestarikan budaya lokal.',
            'logo_path' => null,
        ]);
    }
}
