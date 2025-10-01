@extends('frontend.main')

@section('title', 'Layanan Surat - ' . strtoupper($villageProfile->village_name ?? 'Desa Krandegan'))
@section('page_title', 'LAYANAN SURAT')
@section('header_icon', 'fas fa-file-alt')
@section('header_bg_color', 'bg-violet-600')

@section('content')
<div class="xl:col-span-3">
    <!-- Service Header -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <div class="text-center">
            <h1 class="text-2xl font-bold text-gray-900 mb-2">Layanan Administrasi Desa</h1>
            <p class="text-gray-600 mb-4">Pelayanan cepat, mudah, dan terpercaya untuk semua kebutuhan administrasi Anda</p>
            <div class="flex items-center justify-center space-x-4 text-sm">
                <div class="flex items-center text-green-600">
                    <i class="fas fa-clock mr-1"></i>
                    <span>Senin-Jumat: 08:00-15:00</span>
                </div>
                <div class="flex items-center text-blue-600">
                    <i class="fas fa-phone mr-1"></i>
                    <span>(0267) 123-456</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Service Categories -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
        <!-- Surat Keterangan -->
        <div class="bg-white rounded-lg shadow-lg p-6 border-t-4 border-blue-500">
            <div class="text-center mb-4">
                <div class="w-16 h-16 bg-blue-100 rounded-full mx-auto mb-3 flex items-center justify-center">
                    <i class="fas fa-certificate text-2xl text-blue-600"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-900">Surat Keterangan</h3>
            </div>
            <ul class="space-y-2 text-sm text-gray-700">
                <li class="flex items-center">
                    <i class="fas fa-check text-green-500 mr-2"></i>
                    Surat Keterangan Domisili
                </li>
                <li class="flex items-center">
                    <i class="fas fa-check text-green-500 mr-2"></i>
                    Surat Keterangan Usaha
                </li>
                <li class="flex items-center">
                    <i class="fas fa-check text-green-500 mr-2"></i>
                    Surat Keterangan Tidak Mampu
                </li>
                <li class="flex items-center">
                    <i class="fas fa-check text-green-500 mr-2"></i>
                    Surat Keterangan Penghasilan
                </li>
            </ul>
            <div class="mt-4 p-3 bg-blue-50 rounded-lg">
                <p class="text-xs text-blue-700">
                    <i class="fas fa-info-circle mr-1"></i>
                    Proses: 1-2 hari kerja
                </p>
            </div>
        </div>

        <!-- Surat Pengantar -->
        <div class="bg-white rounded-lg shadow-lg p-6 border-t-4 border-green-500">
            <div class="text-center mb-4">
                <div class="w-16 h-16 bg-green-100 rounded-full mx-auto mb-3 flex items-center justify-center">
                    <i class="fas fa-paper-plane text-2xl text-green-600"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-900">Surat Pengantar</h3>
            </div>
            <ul class="space-y-2 text-sm text-gray-700">
                <li class="flex items-center">
                    <i class="fas fa-check text-green-500 mr-2"></i>
                    Pengantar KTP
                </li>
                <li class="flex items-center">
                    <i class="fas fa-check text-green-500 mr-2"></i>
                    Pengantar Kartu Keluarga
                </li>
                <li class="flex items-center">
                    <i class="fas fa-check text-green-500 mr-2"></i>
                    Pengantar Akta Kelahiran
                </li>
                <li class="flex items-center">
                    <i class="fas fa-check text-green-500 mr-2"></i>
                    Pengantar Nikah
                </li>
            </ul>
            <div class="mt-4 p-3 bg-green-50 rounded-lg">
                <p class="text-xs text-green-700">
                    <i class="fas fa-info-circle mr-1"></i>
                    Proses: Langsung (hari yang sama)
                </p>
            </div>
        </div>

        <!-- Legalisir -->
        <div class="bg-white rounded-lg shadow-lg p-6 border-t-4 border-purple-500">
            <div class="text-center mb-4">
                <div class="w-16 h-16 bg-purple-100 rounded-full mx-auto mb-3 flex items-center justify-center">
                    <i class="fas fa-stamp text-2xl text-purple-600"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-900">Legalisir Dokumen</h3>
            </div>
            <ul class="space-y-2 text-sm text-gray-700">
                <li class="flex items-center">
                    <i class="fas fa-check text-green-500 mr-2"></i>
                    Legalisir KTP
                </li>
                <li class="flex items-center">
                    <i class="fas fa-check text-green-500 mr-2"></i>
                    Legalisir Kartu Keluarga
                </li>
                <li class="flex items-center">
                    <i class="fas fa-check text-green-500 mr-2"></i>
                    Legalisir Ijazah
                </li>
                <li class="flex items-center">
                    <i class="fas fa-check text-green-500 mr-2"></i>
                    Legalisir Sertifikat
                </li>
            </ul>
            <div class="mt-4 p-3 bg-purple-50 rounded-lg">
                <p class="text-xs text-purple-700">
                    <i class="fas fa-info-circle mr-1"></i>
                    Proses: Langsung (hari yang sama)
                </p>
            </div>
        </div>
    </div>

    <!-- Requirements & Procedure -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
            <i class="fas fa-list-check text-orange-600 mr-2"></i>
            Syarat & Prosedur Umum
        </h2>
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Requirements -->
            <div>
                <h3 class="font-bold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-clipboard-list text-blue-600 mr-2"></i>
                    Persyaratan Umum
                </h3>
                <div class="space-y-3">
                    <div class="flex items-start space-x-3 p-3 bg-blue-50 rounded-lg">
                        <i class="fas fa-id-card text-blue-600 mt-0.5"></i>
                        <div>
                            <p class="font-medium text-gray-900">KTP Asli</p>
                            <p class="text-sm text-gray-600">Fotokopi KTP yang masih berlaku</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start space-x-3 p-3 bg-green-50 rounded-lg">
                        <i class="fas fa-home text-green-600 mt-0.5"></i>
                        <div>
                            <p class="font-medium text-gray-900">Kartu Keluarga</p>
                            <p class="text-sm text-gray-600">Fotokopi KK dan asli untuk dicocokkan</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start space-x-3 p-3 bg-yellow-50 rounded-lg">
                        <i class="fas fa-file-text text-yellow-600 mt-0.5"></i>
                        <div>
                            <p class="font-medium text-gray-900">Surat Pengantar RT/RW</p>
                            <p class="text-sm text-gray-600">Dari RT/RW setempat (jika diperlukan)</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start space-x-3 p-3 bg-purple-50 rounded-lg">
                        <i class="fas fa-money-bill text-purple-600 mt-0.5"></i>
                        <div>
                            <p class="font-medium text-gray-900">Biaya Administrasi</p>
                            <p class="text-sm text-gray-600">Sesuai dengan jenis layanan yang diperlukan</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Procedure -->
            <div>
                <h3 class="font-bold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-route text-green-600 mr-2"></i>
                    Alur Pelayanan
                </h3>
                <div class="space-y-3">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white text-sm font-bold">
                            1
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Datang ke Kantor Desa</p>
                            <p class="text-sm text-gray-600">Bawa persyaratan lengkap</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center text-white text-sm font-bold">
                            2
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Mengisi Formulir</p>
                            <p class="text-sm text-gray-600">Isi data dengan lengkap dan benar</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center text-white text-sm font-bold">
                            3
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Verifikasi Berkas</p>
                            <p class="text-sm text-gray-600">Petugas memeriksa kelengkapan</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center text-white text-sm font-bold">
                            4
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Proses & Selesai</p>
                            <p class="text-sm text-gray-600">Surat dapat diambil sesuai jadwal</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Service Fees -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
            <i class="fas fa-receipt text-green-600 mr-2"></i>
            Biaya Layanan
        </h2>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Jenis Layanan
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Biaya
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Waktu Proses
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            Surat Keterangan Domisili
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <span class="text-green-600 font-bold">GRATIS</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            1-2 hari kerja
                        </td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            Surat Keterangan Usaha
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <span class="text-green-600 font-bold">GRATIS</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            1-2 hari kerja
                        </td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            Surat Keterangan Tidak Mampu
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <span class="text-green-600 font-bold">GRATIS</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            1-2 hari kerja
                        </td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            Surat Pengantar (semua jenis)
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <span class="text-green-600 font-bold">GRATIS</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            Langsung
                        </td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            Legalisir Dokumen
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            Rp 2,000/lembar
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            Langsung
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <div class="mt-4 p-4 bg-green-50 border border-green-200 rounded-lg">
            <p class="text-sm text-green-700">
                <i class="fas fa-info-circle mr-2"></i>
                <strong>Catatan:</strong> Sebagian besar layanan administrasi desa disediakan secara GRATIS sesuai dengan ketentuan perundang-undangan yang berlaku.
            </p>
        </div>
    </div>

    <!-- Contact & Location -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
            <i class="fas fa-map-marker-alt text-red-600 mr-2"></i>
            Kontak & Lokasi Layanan
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="font-bold text-gray-900 mb-3">Informasi Kontak</h3>
                <div class="space-y-3">
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-phone text-blue-600"></i>
                        <div>
                            <p class="font-medium text-gray-900">Telepon</p>
                            <p class="text-sm text-gray-600">(0267) 123-456</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-envelope text-green-600"></i>
                        <div>
                            <p class="font-medium text-gray-900">Email</p>
                            <p class="text-sm text-gray-600">admin@desakrandegan.id</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-3">
                        <i class="fab fa-whatsapp text-green-500"></i>
                        <div>
                            <p class="font-medium text-gray-900">WhatsApp</p>
                            <p class="text-sm text-gray-600">+62 821-xxxx-xxxx</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div>
                <h3 class="font-bold text-gray-900 mb-3">Jam Pelayanan</h3>
                <div class="space-y-2">
                    <div class="flex justify-between items-center p-2 bg-gray-50 rounded">
                        <span class="text-sm font-medium">Senin - Kamis</span>
                        <span class="text-sm text-gray-600">08:00 - 15:00</span>
                    </div>
                    <div class="flex justify-between items-center p-2 bg-gray-50 rounded">
                        <span class="text-sm font-medium">Jumat</span>
                        <span class="text-sm text-gray-600">08:00 - 11:30</span>
                    </div>
                    <div class="flex justify-between items-center p-2 bg-red-50 rounded">
                        <span class="text-sm font-medium">Sabtu - Minggu</span>
                        <span class="text-sm text-red-600">TUTUP</span>
                    </div>
                </div>
                
                <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                    <p class="text-xs text-blue-700">
                        <i class="fas fa-clock mr-1"></i>
                        Untuk layanan mendesak, silakan hubungi nomor kontak yang tersedia.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection