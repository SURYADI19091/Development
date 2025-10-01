@extends('frontend.main')

@section('title', 'Sejarah Desa - ' . strtoupper($villageProfile->village_name ?? 'Desa Krandegan'))
@section('page_title', 'SEJARAH DESA')
@section('header_icon', 'fas fa-history')
@section('header_bg_color', 'bg-amber-600')

@section('content')
<div class="xl:col-span-3">
    <!-- Timeline Header -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <div class="text-center mb-6">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Sejarah Desa Krandegan</h1>
            <p class="text-gray-600">Perjalanan panjang pembentukan dan perkembangan desa</p>
        </div>
        
        <div class="bg-gradient-to-r from-amber-50 to-orange-50 rounded-lg p-4 border-l-4 border-amber-500">
            <p class="text-gray-700 leading-relaxed">
                Desa Krandegan memiliki sejarah yang panjang dan kaya akan nilai-nilai budaya Jawa. 
                Nama "Krandegan" sendiri berasal dari kata dalam bahasa Jawa yang memiliki makna mendalam 
                terkait dengan karakteristik wilayah dan kehidupan masyarakatnya.
            </p>
        </div>
    </div>

    <!-- Historical Timeline -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
            <i class="fas fa-clock text-amber-600 mr-2"></i>
            Kronologi Sejarah
        </h2>
        
        <div class="relative">
            <!-- Timeline line -->
            <div class="absolute left-4 top-0 bottom-0 w-0.5 bg-amber-300"></div>
            
            <!-- Timeline items -->
            <div class="space-y-8">
                <!-- Era Kerajaan -->
                <div class="relative flex items-start">
                    <div class="flex-shrink-0 w-8 h-8 bg-amber-500 rounded-full flex items-center justify-center text-white text-sm font-bold">
                        1
                    </div>
                    <div class="ml-6">
                        <div class="bg-amber-50 rounded-lg p-4">
                            <h3 class="font-bold text-gray-900 mb-2">Era Kerajaan (Abad 15-16)</h3>
                            <p class="text-gray-700 text-sm mb-2">
                                Wilayah yang sekarang menjadi Desa Krandegan pada masa ini merupakan bagian dari 
                                Kerajaan Sumedang Larang. Daerah ini masih berupa hutan lebat dan rawa-rawa yang 
                                dihuni oleh beberapa keluarga perintis.
                            </p>
                            <span class="text-xs text-amber-700 bg-amber-100 px-2 py-1 rounded">± 1400-1600</span>
                        </div>
                    </div>
                </div>

                <!-- Masa Kolonial -->
                <div class="relative flex items-start">
                    <div class="flex-shrink-0 w-8 h-8 bg-amber-500 rounded-full flex items-center justify-center text-white text-sm font-bold">
                        2
                    </div>
                    <div class="ml-6">
                        <div class="bg-amber-50 rounded-lg p-4">
                            <h3 class="font-bold text-gray-900 mb-2">Masa Kolonial Belanda (1800-1945)</h3>
                            <p class="text-gray-700 text-sm mb-2">
                                Pembukaan lahan pertanian secara besar-besaran dimulai oleh pemerintah kolonial Belanda. 
                                Sistem irigasi mulai dibangun dan penduduk mulai berdatangan untuk membuka lahan pertanian. 
                                Nama "Krandegan" mulai dikenal pada periode ini.
                            </p>
                            <span class="text-xs text-amber-700 bg-amber-100 px-2 py-1 rounded">1800-1945</span>
                        </div>
                    </div>
                </div>

                <!-- Kemerdekaan -->
                <div class="relative flex items-start">
                    <div class="flex-shrink-0 w-8 h-8 bg-amber-500 rounded-full flex items-center justify-center text-white text-sm font-bold">
                        3
                    </div>
                    <div class="ml-6">
                        <div class="bg-amber-50 rounded-lg p-4">
                            <h3 class="font-bold text-gray-900 mb-2">Masa Kemerdekaan (1945-1950)</h3>
                            <p class="text-gray-700 text-sm mb-2">
                                Setelah proklamasi kemerdekaan, wilayah ini mengalami reorganisasi pemerintahan. 
                                Krandegan ditetapkan sebagai desa definitif dengan struktur pemerintahan yang jelas. 
                                Kepala desa pertama adalah Bapak Kartawiria.
                            </p>
                            <span class="text-xs text-amber-700 bg-amber-100 px-2 py-1 rounded">1945-1950</span>
                        </div>
                    </div>
                </div>

                <!-- Era Pembangunan -->
                <div class="relative flex items-start">
                    <div class="flex-shrink-0 w-8 h-8 bg-amber-500 rounded-full flex items-center justify-center text-white text-sm font-bold">
                        4
                    </div>
                    <div class="ml-6">
                        <div class="bg-amber-50 rounded-lg p-4">
                            <h3 class="font-bold text-gray-900 mb-2">Era Pembangunan (1970-1998)</h3>
                            <p class="text-gray-700 text-sm mb-2">
                                Masa pembangunan infrastruktur besar-besaran. Jalan beraspal, sekolah, puskesmas, 
                                dan fasilitas umum lainnya mulai dibangun. Program transmigrasi juga membawa 
                                penduduk baru yang memperkaya keragaman budaya desa.
                            </p>
                            <span class="text-xs text-amber-700 bg-amber-100 px-2 py-1 rounded">1970-1998</span>
                        </div>
                    </div>
                </div>

                <!-- Era Reformasi -->
                <div class="relative flex items-start">
                    <div class="flex-shrink-0 w-8 h-8 bg-amber-500 rounded-full flex items-center justify-center text-white text-sm font-bold">
                        5
                    </div>
                    <div class="ml-6">
                        <div class="bg-amber-50 rounded-lg p-4">
                            <h3 class="font-bold text-gray-900 mb-2">Era Reformasi & Modern (1998-Sekarang)</h3>
                            <p class="text-gray-700 text-sm mb-2">
                                Implementasi otonomi daerah memberikan keleluasaan lebih besar dalam pengelolaan desa. 
                                Program-program pemberdayaan masyarakat, teknologi informasi, dan inovasi desa mulai 
                                dikembangkan untuk meningkatkan kesejahteraan masyarakat.
                            </p>
                            <span class="text-xs text-amber-700 bg-amber-100 px-2 py-1 rounded">1998-Sekarang</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Historical Figures -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
            <i class="fas fa-users text-blue-600 mr-2"></i>
            Tokoh Bersejarah
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-gray-50 rounded-lg p-4">
                <h3 class="font-bold text-gray-900 mb-2">Bapak Kartawiria</h3>
                <p class="text-sm text-gray-600 mb-2">Kepala Desa Pertama (1945-1965)</p>
                <p class="text-sm text-gray-700">
                    Pelopor pembentukan struktur pemerintahan desa dan pencetus nama "Krandegan". 
                    Beliau juga yang memimpin pembangunan infrastruktur dasar desa.
                </p>
            </div>
            
            <div class="bg-gray-50 rounded-lg p-4">
                <h3 class="font-bold text-gray-900 mb-2">Bapak Sukarjo</h3>
                <p class="text-sm text-gray-600 mb-2">Tokoh Pendidikan (1960-1990)</p>
                <p class="text-sm text-gray-700">
                    Guru pertama di desa yang mendirikan sekolah dasar dan mengembangkan 
                    pendidikan masyarakat. Berjasa dalam memberantas buta huruf.
                </p>
            </div>
            
            <div class="bg-gray-50 rounded-lg p-4">
                <h3 class="font-bold text-gray-900 mb-2">Ibu Siti Rohayati</h3>
                <p class="text-sm text-gray-600 mb-2">Pelopor Kesehatan (1970-2000)</p>
                <p class="text-sm text-gray-700">
                    Bidan desa pertama yang mengembangkan layanan kesehatan ibu dan anak. 
                    Pendiri posyandu dan program KB di desa.
                </p>
            </div>
            
            <div class="bg-gray-50 rounded-lg p-4">
                <h3 class="font-bold text-gray-900 mb-2">H. Ahmad Sobari</h3>
                <p class="text-sm text-gray-600 mb-2">Tokoh Agama (1980-2010)</p>
                <p class="text-sm text-gray-700">
                    Pemimpin spiritual yang membangun masjid besar dan mengembangkan 
                    pendidikan agama di desa melalui pengajian dan madrasah.
                </p>
            </div>
        </div>
    </div>

    <!-- Cultural Heritage -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
            <i class="fas fa-scroll text-purple-600 mr-2"></i>
            Warisan Budaya
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="font-semibold text-gray-800 mb-3">Tradisi & Upacara</h3>
                <ul class="space-y-2 text-sm">
                    <li class="flex items-start">
                        <span class="text-purple-500 mr-2">•</span>
                        <div>
                            <span class="font-medium">Seren Taun:</span>
                            <span class="text-gray-600"> Upacara syukuran panen raya yang diadakan setiap tahun</span>
                        </div>
                    </li>
                    <li class="flex items-start">
                        <span class="text-purple-500 mr-2">•</span>
                        <div>
                            <span class="font-medium">Rajaban:</span>
                            <span class="text-gray-600"> Tradisi keagamaan di bulan Rajab</span>
                        </div>
                    </li>
                    <li class="flex items-start">
                        <span class="text-purple-500 mr-2">•</span>
                        <div>
                            <span class="font-medium">Gotong Royong:</span>
                            <span class="text-gray-600"> Kerja bakti bersama setiap hari Minggu</span>
                        </div>
                    </li>
                </ul>
            </div>
            
            <div>
                <h3 class="font-semibold text-gray-800 mb-3">Kesenian Tradisional</h3>
                <ul class="space-y-2 text-sm">
                    <li class="flex items-start">
                        <span class="text-purple-500 mr-2">•</span>
                        <div>
                            <span class="font-medium">Tari Topeng:</span>
                            <span class="text-gray-600"> Tarian khas untuk menyambut tamu kehormatan</span>
                        </div>
                    </li>
                    <li class="flex items-start">
                        <span class="text-purple-500 mr-2">•</span>
                        <div>
                            <span class="font-medium">Wayang Golek:</span>
                            <span class="text-gray-600"> Pertunjukan tradisional saat acara besar</span>
                        </div>
                    </li>
                    <li class="flex items-start">
                        <span class="text-purple-500 mr-2">•</span>
                        <div>
                            <span class="font-medium">Angklung:</span>
                            <span class="text-gray-600"> Grup musik tradisional desa</span>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection