<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PopulationData;
use Faker\Factory as Faker;
use Carbon\Carbon;

class PopulationDataSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        
        // Data template untuk keluarga Indonesia yang realistis
        $religions = ['Islam', 'Kristen Protestan', 'Katolik', 'Hindu', 'Buddha'];
        $religionWeights = [85, 7, 3, 3, 2]; // Persentase realistis
        
        $occupations = [
            'Petani', 'Buruh Tani', 'Pedagang', 'Wiraswasta', 'PNS', 
            'TNI/Polri', 'Guru', 'Perawat', 'Supir', 'Tukang', 
            'Buruh Harian', 'Ibu Rumah Tangga', 'Pelajar/Mahasiswa', 
            'Pensiunan', 'Tidak Bekerja'
        ];
        
        $maritalStatuses = ['Single', 'Married', 'Divorced', 'Widowed'];
        $familyRelations = [
            'Kepala Keluarga', 'Istri', 'Anak', 'Menantu', 
            'Cucu', 'Orang Tua', 'Mertua', 'Famili Lain'
        ];
        
        $districts = ['Krandegan', 'Srengat', 'Ponggok', 'Sutojayan'];
        
        // Generate 3000 data penduduk
        for ($i = 1; $i <= 3000; $i++) {
            // Random birth date (spread across ages)
            $ageYears = $faker->numberBetween(0, 85);
            $birthDate = Carbon::now()->subYears($ageYears)->subDays($faker->numberBetween(0, 365));
            
            // Determine occupation based on age and gender
            $gender = $faker->randomElement(['M', 'F']);
            $age = (int) $ageYears;
            
            if ($age < 6) {
                $occupation = 'Belum/Tidak Sekolah';
                $maritalStatus = 'Single';
            } elseif ($age < 18) {
                $occupation = 'Pelajar/Mahasiswa';
                $maritalStatus = 'Single';
            } elseif ($age >= 60) {
                $occupation = $faker->randomElement(['Pensiunan', 'Tidak Bekerja']);
                $maritalStatus = $faker->randomElement(['Married', 'Widowed']);
            } else {
                if ($gender == 'F' && $faker->boolean(40)) {
                    $occupation = 'Ibu Rumah Tangga';
                } else {
                    $occupation = $faker->randomElement($occupations);
                }
                
                if ($age < 20) {
                    $maritalStatus = 'Single';
                } else {
                    $maritalStatus = $faker->randomElement(['Single', 'Married', 'Married', 'Married', 'Divorced']);
                }
            }
            
            // Family card number (same for family members)
            $familyCardNumber = str_pad($faker->numberBetween(3507010001000001, 3507019999999999), 16, '0', STR_PAD_LEFT);
            
            PopulationData::create([
                'serial_number' => $i,
                'family_card_number' => $familyCardNumber,
                'identity_card_number' => str_pad($faker->unique()->numberBetween(3507010101000001, 3507019999999999), 16, '0', STR_PAD_LEFT),
                'name' => $faker->name($gender == 'M' ? 'male' : 'female'),
                'birth_place' => $faker->randomElement(['Blitar', 'Malang', 'Surabaya', 'Jakarta', 'Yogyakarta']),
                'birth_date' => $birthDate,
                'age' => $age,
                'address' => 'Dusun ' . $faker->randomElement(['Krajan', 'Sumber', 'Kauman', 'Dawuhan']) . ' RT.' . $faker->numberBetween(1, 5) . ' RW.' . $faker->numberBetween(1, 3),
                'settlement_id' => $faker->randomElement(['001', '002', '003']),
                'gender' => $gender,
                'marital_status' => $maritalStatus,
                'family_relationship' => $faker->randomElement($familyRelations),
                'head_of_family' => $faker->name('male'),
                'religion' => $faker->randomElement($religions, $religionWeights),
                'occupation' => $occupation,
                'residence_type' => $faker->randomElement(['Milik Sendiri', 'Kontrak/Sewa', 'Bebas Sewa', 'Dinas']),
                'independent_family_head' => $faker->randomElement(['Ya', 'Tidak']),
                'district' => $faker->randomElement($districts),
                'regency' => 'Blitar',
                'province' => 'Jawa Timur',
                'created_at' => $faker->dateTimeBetween('-5 years', 'now'),
                'updated_at' => now()
            ]);
        }
    }
}