<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TourismObject;
use Illuminate\Support\Str;

class TourismObjectSeeder extends Seeder
{
    public function run()
    {
        $tourismData = [
            [
                'name' => 'Telaga Hijau Krandegan',
                'description' => 'Kolam alami dengan air jernih yang dikelilingi pepohonan hijau. Cocok untuk berenang dan memancing.',
                'category' => 'alam',
                'address' => 'Dusun II, 2 km dari balai desa',
                'latitude' => -7.123456,
                'longitude' => 110.654321,
                'operating_hours' => '07:00-18:00',
                'ticket_price' => 5000.00,
                'contact_phone' => '08123456789',
                'images' => json_encode([
                    'https://images.unsplash.com/photo-1571115764595-644a1f56a55c?w=400&h=250&fit=crop',
                    'https://images.unsplash.com/photo-1544966503-7cc5ac882d5f?w=800'
                ]),
                'facilities' => 'Toilet, Mushola, Area Parkir, Warung',
                'is_featured' => true,
                'is_active' => true
            ],
            [
                'name' => 'Rumah Budaya Krandegan',
                'description' => 'Pusat pelestarian budaya lokal dengan berbagai koleksi benda bersejarah dan pertunjukan seni tradisional.',
                'category' => 'budaya',
                'address' => 'Pusat desa, dekat balai desa',
                'latitude' => -7.123456,
                'longitude' => 110.654321,
                'operating_hours' => '08:00-16:00',
                'ticket_price' => 10000.00,
                'contact_phone' => '08123456790',
                'images' => json_encode([
                    'https://images.unsplash.com/photo-1544531586-fbb6cf2ea9bb?w=400&h=250&fit=crop',
                    'https://images.unsplash.com/photo-1592928302636-c0cce2ef4a5b?w=800'
                ]),
                'facilities' => 'Toilet, Museum, Toko Souvenir',
                'is_featured' => true,
                'is_active' => true
            ],
            [
                'name' => 'Warung Lesehan Krandegan',
                'description' => 'Warung lesehan tradisional dengan pemandangan sawah. Menyajikan makanan khas desa dengan cita rasa autentik.',
                'category' => 'kuliner',
                'address' => 'Jl. Raya Desa, dekat persawahan',
                'latitude' => -7.123456,
                'longitude' => 110.654321,
                'operating_hours' => '10:00-22:00',
                'ticket_price' => 0.00,
                'contact_phone' => '08123456791',
                'images' => json_encode([
                    'https://images.unsplash.com/photo-1555939594-58d7cb561ad1?w=400&h=250&fit=crop',
                    'https://images.unsplash.com/photo-1567620905732-2d1ec7ab7445?w=800'
                ]),
                'facilities' => 'Toilet, Area Parkir, WiFi',
                'is_featured' => true,
                'is_active' => true
            ],
            [
                'name' => 'Kampung Kreatif Krandegan',
                'description' => 'Pusat edukasi dan pelatihan keterampilan tradisional. Belajar membuat kerajinan tangan dan produk lokal.',
                'category' => 'edukasi',
                'address' => 'Dusun III, kompleks PKK',
                'latitude' => -7.123456,
                'longitude' => 110.654321,
                'operating_hours' => '08:00-15:00',
                'ticket_price' => 15000.00,
                'contact_phone' => '08123456792',
                'images' => json_encode([
                    'https://images.unsplash.com/photo-1581833971358-2c8b550f87b3?w=400&h=250&fit=crop',
                    'https://images.unsplash.com/photo-1570129477492-45c003edd2be?w=800'
                ]),
                'facilities' => 'Toilet, Ruang Kelas, Kantin, Area Parkir',
                'is_featured' => false,
                'is_active' => true
            ],
            [
                'name' => 'Hutan Pinus Krandegan',
                'description' => 'Kawasan hutan pinus yang asri dengan trek hiking dan spot camping. Udara segar dan pemandangan indah.',
                'category' => 'alam',
                'address' => 'Dusun IV, 5 km dari pusat desa',
                'latitude' => -7.123456,
                'longitude' => 110.654321,
                'operating_hours' => '06:00-18:00',
                'ticket_price' => 3000.00,
                'contact_phone' => '08123456793',
                'images' => json_encode([
                    'https://images.unsplash.com/photo-1441974231531-c6227db76b6e?w=400&h=250&fit=crop',
                    'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=800'
                ]),
                'facilities' => 'Toilet, Area Camping, Trek Hiking',
                'is_featured' => false,
                'is_active' => true
            ],
            [
                'name' => 'Taman Bermain Anak Krandegan',
                'description' => 'Area rekreasi keluarga dengan berbagai wahana permainan anak dan fasilitas olahraga.',
                'category' => 'rekreasi',
                'address' => 'Pusat desa, samping lapangan',
                'latitude' => -7.123456,
                'longitude' => 110.654321,
                'operating_hours' => '07:00-19:00',
                'ticket_price' => 2000.00,
                'contact_phone' => '08123456794',
                'images' => json_encode([
                    'https://images.unsplash.com/photo-1544551763-46a013bb70d5?w=400&h=250&fit=crop',
                    'https://images.unsplash.com/photo-1569859985852-de9bdc5bb6a3?w=800'
                ]),
                'facilities' => 'Toilet, Playground, Area Olahraga, Kantin',
                'is_featured' => false,
                'is_active' => true
            ]
        ];

        foreach ($tourismData as $data) {
            TourismObject::create($data);
        }
    }
}