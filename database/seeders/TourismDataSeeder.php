<?php

na    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Update existing tourism objects to be featured (first 3)
        \App\Models\TourismObject::limit(3)->update(['is_featured' => true]);
        
        // Add more tourism objects if needed
        $tourismData = [
            [
                'name' => 'Air Terjun Sekumpul',
                'description' => 'Air terjun spektakuler dengan ketinggian 80 meter yang dikelilingi tebing-tebing hijau.',
                'category' => 'alam',
                'address' => 'Dusun Sekumpul, Desa Krandegan',
                'ticket_price' => 15000,
                'operating_hours' => '07:00 - 17:00 WIB',
                'contact_phone' => '081234567890',
                'images' => ['https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=800&h=600&fit=crop'],
                'is_active' => true,
                'is_featured' => true,
            ],
            [
                'name' => 'Kebun Teh Panorama', 
                'description' => 'Perkebunan teh dengan pemandangan pegunungan yang menakjubkan.',
                'category' => 'agrowisata',
                'address' => 'Dusun Panorama, Desa Krandegan',
                'ticket_price' => 10000,
                'operating_hours' => '06:00 - 18:00 WIB',
                'contact_phone' => '082345678901',
                'images' => ['https://images.unsplash.com/photo-1563822249548-9a72b6353cd1?w=800&h=600&fit=crop'],
                'is_active' => true,
                'is_featured' => false,
            ]
        ];

        foreach ($tourismData as $data) {
            \App\Models\TourismObject::updateOrCreate(
                ['name' => $data['name']], 
                $data
            );
        }

        $this->command->info('Tourism data seeded successfully!');
    } Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TourismDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
    }
}
