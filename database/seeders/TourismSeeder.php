<?php<?php



namespace Database\Seeders;namespace Database\Seeders;



use Illuminate\Database\Seeder;use Illuminate\Database\Seeder;

use App\Models\TourismObject;use App\Models\TourismObject;



class TourismSeeder extends Seederclass TourismSeeder extends Seeder

{{

    /**    /**

     * Run the database seeder.     * Run the database seeder.

     */     */

    public function run(): void    public function run(): void

    {    {

        // Create sample tourism data        // Create sample tourism data

        $tourismData = [        $tourismData = [

            [            [

                'name' => 'Air Terjun Sekumpul',                'name' => 'Air Terjun Sekumpul',

                'description' => 'Air terjun spektakuler dengan ketinggian 80 meter yang dikelilingi tebing-tebing hijau. Tempat yang sempurna untuk menikmati keindahan alam dan berfoto.',                'description' => 'Air terjun spektakuler dengan ketinggian 80 meter yang dikelilingi tebing-tebing hijau. Tempat yang sempurna untuk menikmati keindahan alam dan berfoto.',

                'category' => 'alam',                'category' => 'alam',

                'address' => 'Dusun Sekumpul, Desa Krandegan',                'address' => 'Dusun Sekumpul, Desa Krandegan',

                'latitude' => -8.1234,                'latitude' => -8.1234,

                'longitude' => 115.2345,                'longitude' => 115.2345,

                'contact_info' => 'Pak Wayan - 081234567890',                'contact_info' => 'Pak Wayan - 081234567890',

                'operating_hours' => '07:00 - 17:00 WIB',                'operating_hours' => '07:00 - 17:00 WIB',

                'entry_fee' => 15000,                'entry_fee' => 15000,

                'facilities' => ['Toilet', 'Warung', 'Area Parkir', 'Gazebo'],                'facilities' => ['Toilet', 'Warung', 'Area Parkir', 'Gazebo'],

                'images' => [                'images' => [

                    'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=800&h=600&fit=crop',                    'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=800&h=600&fit=crop',

                    'https://images.unsplash.com/photo-1439066615861-d1af74d74000?w=800&h=600&fit=crop'                    'https://images.unsplash.com/photo-1439066615861-d1af74d74000?w=800&h=600&fit=crop'

                ],                ],

                'rating' => 4.8,                'rating' => 4.8,

                'total_reviews' => 124,                'total_reviews' => 124,

                'is_active' => true,                'is_active' => true,

                'is_featured' => true,                'is_featured' => true,

            ],            ],

            [            [

                'name' => 'Kebun Teh Panorama',                'name' => 'Kebun Teh Panorama',

                'description' => 'Perkebunan teh dengan pemandangan pegunungan yang menakjubkan. Wisatawan dapat menikmati secangkir teh segar sambil menikmati udara sejuk pegunungan.',                'description' => 'Perkebunan teh dengan pemandangan pegunungan yang menakjubkan. Wisatawan dapat menikmati secangkir teh segar sambil menikmati udara sejuk pegunungan.',

                'category' => 'agrowisata',                'category' => 'agrowisata',

                'address' => 'Dusun Panorama, Desa Krandegan',                'address' => 'Dusun Panorama, Desa Krandegan',

                'latitude' => -8.1456,                'latitude' => -8.1456,

                'longitude' => 115.2567,                'longitude' => 115.2567,

                'contact_info' => 'Bu Sari - 082345678901',                'contact_info' => 'Bu Sari - 082345678901',

                'operating_hours' => '06:00 - 18:00 WIB',                'operating_hours' => '06:00 - 18:00 WIB',

                'entry_fee' => 10000,                'entry_fee' => 10000,

                'facilities' => ['Cafe', 'Toilet', 'Area Foto', 'Toko Oleh-oleh'],                'facilities' => ['Cafe', 'Toilet', 'Area Foto', 'Toko Oleh-oleh'],

                'images' => [                'images' => [

                    'https://images.unsplash.com/photo-1563822249548-9a72b6353cd1?w=800&h=600&fit=crop',                    'https://images.unsplash.com/photo-1563822249548-9a72b6353cd1?w=800&h=600&fit=crop',

                    'https://images.unsplash.com/photo-1571934811356-5cc061b6821f?w=800&h=600&fit=crop'                    'https://images.unsplash.com/photo-1571934811356-5cc061b6821f?w=800&h=600&fit=crop'

                ],                ],

                'rating' => 4.6,                'rating' => 4.6,

                'total_reviews' => 89,                'total_reviews' => 89,

                'is_active' => true,                'is_active' => true,

                'is_featured' => true,                'is_featured' => true,

            ],            ],

            [            [

                'name' => 'Kampung Wisata Tradisional',                'name' => 'Kampung Wisata Tradisional',

                'description' => 'Kampung wisata yang mempertahankan budaya dan tradisi lokal. Wisatawan dapat belajar membatik, memasak makanan tradisional, dan menginap di rumah penduduk.',                'description' => 'Kampung wisata yang mempertahankan budaya dan tradisi lokal. Wisatawan dapat belajar membatik, memasak makanan tradisional, dan menginap di rumah penduduk.',

                'category' => 'budaya',                'category' => 'budaya',

                'address' => 'Dusun Tradisional, Desa Krandegan',                'address' => 'Dusun Tradisional, Desa Krandegan',

                'latitude' => -8.1678,                'latitude' => -8.1678,

                'longitude' => 115.2789,                'longitude' => 115.2789,

                'contact_info' => 'Pak Made - 083456789012',                'contact_info' => 'Pak Made - 083456789012',

                'operating_hours' => '08:00 - 16:00 WIB',                'operating_hours' => '08:00 - 16:00 WIB',

                'entry_fee' => 25000,                'entry_fee' => 25000,

                'facilities' => ['Homestay', 'Workshop Batik', 'Resto Tradisional', 'Museum Mini'],                'facilities' => ['Homestay', 'Workshop Batik', 'Resto Tradisional', 'Museum Mini'],

                'images' => [                'images' => [

                    'https://images.unsplash.com/photo-1539650116574-75c0c6d89253?w=800&h=600&fit=crop',                    'https://images.unsplash.com/photo-1539650116574-75c0c6d89253?w=800&h=600&fit=crop',

                    'https://images.unsplash.com/photo-1518709268805-4e9042af2176?w=800&h=600&fit=crop'                    'https://images.unsplash.com/photo-1518709268805-4e9042af2176?w=800&h=600&fit=crop'

                ],                ],

                'rating' => 4.7,                'rating' => 4.7,

                'total_reviews' => 67,                'total_reviews' => 67,

                'is_active' => true,                'is_active' => true,

                'is_featured' => true,                'is_featured' => true,

            ],            ],

            [            [

                'name' => 'Bukit Sunrise',                'name' => 'Bukit Sunrise',

                'description' => 'Bukit dengan pemandangan sunrise terbaik di desa. Lokasi favorit untuk camping dan menikmati matahari terbit yang spektakuler.',                'description' => 'Bukit dengan pemandangan sunrise terbaik di desa. Lokasi favorit untuk camping dan menikmati matahari terbit yang spektakuler.',

                'category' => 'alam',                'category' => 'alam',

                'address' => 'Dusun Sunrise, Desa Krandegan',                'address' => 'Dusun Sunrise, Desa Krandegan',

                'latitude' => -8.1890,                'latitude' => -8.1890,

                'longitude' => 115.2901,                'longitude' => 115.2901,

                'contact_info' => 'Pak Ketut - 084567890123',                'contact_info' => 'Pak Ketut - 084567890123',

                'operating_hours' => '05:00 - 19:00 WIB',                'operating_hours' => '05:00 - 19:00 WIB',

                'entry_fee' => 20000,                'entry_fee' => 20000,

                'facilities' => ['Area Camping', 'Gazebo', 'Toilet', 'Warung'],                'facilities' => ['Area Camping', 'Gazebo', 'Toilet', 'Warung'],

                'images' => [                'images' => [

                    'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=800&h=600&fit=crop'                    'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=800&h=600&fit=crop'

                ],                ],

                'rating' => 4.9,                'rating' => 4.9,

                'total_reviews' => 156,                'total_reviews' => 156,

                'is_active' => true,                'is_active' => true,

                'is_featured' => false,                'is_featured' => false,

            ],            ],

            [            [

                'name' => 'Danau Serenity',                'name' => 'Danau Serenity',

                'description' => 'Danau alami dengan air jernih dan suasana tenang. Tempat ideal untuk berperahu, memancing, atau sekadar bersantai.',                'description' => 'Danau alami dengan air jernih dan suasana tenang. Tempat ideal untuk berperahu, memancing, atau sekadar bersantai.',

                'category' => 'alam',                'category' => 'alam',

                'address' => 'Dusun Serenity, Desa Krandegan',                'address' => 'Dusun Serenity, Desa Krandegan',

                'latitude' => -8.2012,                'latitude' => -8.2012,

                'longitude' => 115.3123,                'longitude' => 115.3123,

                'contact_info' => 'Bu Ni Luh - 085678901234',                'contact_info' => 'Bu Ni Luh - 085678901234',

                'operating_hours' => '07:00 - 17:00 WIB',                'operating_hours' => '07:00 - 17:00 WIB',

                'entry_fee' => 12000,                'entry_fee' => 12000,

                'facilities' => ['Perahu', 'Area Piknik', 'Toilet', 'Parkir'],                'facilities' => ['Perahu', 'Area Piknik', 'Toilet', 'Parkir'],

                'images' => [                'images' => [

                    'https://images.unsplash.com/photo-1439066615861-d1af74d74000?w=800&h=600&fit=crop'                    'https://images.unsplash.com/photo-1439066615861-d1af74d74000?w=800&h=600&fit=crop'

                ],                ],

                'rating' => 4.5,                'rating' => 4.5,

                'total_reviews' => 92,                'total_reviews' => 92,

                'is_active' => true,                'is_active' => true,

                'is_featured' => false,                'is_featured' => false,

            ],            ],

        ];        ];



        foreach ($tourismData as $data) {        foreach ($tourismData as $data) {

            TourismObject::create($data);            TourismObject::create($data);

        }        }



        $this->command->info('Tourism objects seeded successfully!');        $this->command->info('Tourism objects seeded successfully!');

    }    }

}}Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TourismSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
    }
}
