<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PopulationData;
use Carbon\Carbon;
use Faker\Factory as Faker;

class UpdatePopulationStatusSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        
        // Penyebab kematian umum di Indonesia
        $deathCauses = [
            'Penyakit Jantung',
            'Stroke', 
            'Diabetes',
            'Hipertensi',
            'Pneumonia',
            'Kanker',
            'Gagal Ginjal',
            'Kecelakaan',
            'Usia Lanjut',
            'Komplikasi Medis'
        ];

        // Update semua data menjadi status 'Hidup' terlebih dahulu
        PopulationData::query()->update(['status' => 'Hidup']);

        // Simulasi kematian pada penduduk lansia (>70 tahun) sebanyak 3-5%
        $elderlyPeople = PopulationData::whereRaw('TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) > 70')
                                      ->inRandomOrder()
                                      ->limit(150) // Simulasi 150 orang meninggal
                                      ->get();

        foreach ($elderlyPeople as $person) {
            // Random tanggal meninggal dalam 5 tahun terakhir
            $deathDate = $faker->dateTimeBetween('-5 years', 'now');
            
            $person->update([
                'status' => 'Meninggal',
                'death_date' => $deathDate,
                'death_cause' => $faker->randomElement($deathCauses)
            ]);
        }

        // Simulasi beberapa kematian pada dewasa muda (kecelakaan, penyakit)
        $youngAdults = PopulationData::whereRaw('TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) BETWEEN 20 AND 50')
                                    ->where('status', 'Hidup')
                                    ->inRandomOrder()
                                    ->limit(50) // 50 orang dewasa muda meninggal
                                    ->get();

        foreach ($youngAdults as $person) {
            $deathDate = $faker->dateTimeBetween('-3 years', 'now');
            
            $person->update([
                'status' => 'Meninggal',
                'death_date' => $deathDate,
                'death_cause' => $faker->randomElement(['Kecelakaan', 'Penyakit Jantung', 'Kanker', 'Stroke', 'Kecelakaan Kerja'])
            ]);
        }

        // Simulasi beberapa kematian bayi/anak (sangat sedikit)
        $children = PopulationData::whereRaw('TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) < 5')
                                  ->where('status', 'Hidup')
                                  ->inRandomOrder()
                                  ->limit(10) // 10 anak meninggal
                                  ->get();

        foreach ($children as $person) {
            $deathDate = $faker->dateTimeBetween($person->birth_date, 'now');
            
            $person->update([
                'status' => 'Meninggal',
                'death_date' => $deathDate,
                'death_cause' => $faker->randomElement(['Komplikasi Kelahiran', 'Pneumonia', 'Demam', 'Kecelakaan'])
            ]);
        }

        echo "Status updated: " . PopulationData::where('status', 'Hidup')->count() . " orang hidup, ";
        echo PopulationData::where('status', 'Meninggal')->count() . " orang meninggal\n";
    }
}