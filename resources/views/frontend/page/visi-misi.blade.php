@extends('frontend.main')

@section('title', 'Visi Misi - ' . strtoupper($villageProfile->village_name ?? 'Desa Krandegan'))
@section('page_title', 'VISI & MISI')
@section('header_icon', 'fas fa-eye')
@section('header_bg_color', 'bg-indigo-600')

@section('content')
<div class="xl:col-span-3">
    <!-- Vision Statement -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <div class="text-center mb-6">
            <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-eye text-2xl text-indigo-600"></i>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 mb-2">VISI DESA KRANDEGAN</h1>
            <div class="w-24 h-1 bg-indigo-600 mx-auto"></div>
        </div>
        
        <div class="bg-gradient-to-br from-indigo-50 to-purple-50 rounded-lg p-6 border border-indigo-200">
            <blockquote class="text-center">
                <p class="text-xl md:text-2xl font-semibold text-gray-900 leading-relaxed mb-4">
                    "Terwujudnya Desa Krandegan yang Maju, Mandiri, dan Sejahtera 
                    Berdasarkan Nilai-Nilai Gotong Royong dan Kearifan Lokal"
                </p>
                <footer class="text-sm text-indigo-600 font-medium">
                    Periode 2024-2029
                </footer>
            </blockquote>
        </div>
        
        <div class="mt-6 text-center">
            <p class="text-gray-700 leading-relaxed max-w-3xl mx-auto">
                Visi ini mencerminkan tekad dan cita-cita bersama seluruh warga Desa Krandegan untuk membangun 
                desa yang berkembang pesat dalam segala aspek kehidupan, dengan tetap menjaga nilai-nilai 
                tradisional dan kearifan lokal yang telah mengakar dalam masyarakat.
            </p>
        </div>
    </div>

    <!-- Mission Statement -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <div class="text-center mb-6">
            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-bullseye text-2xl text-green-600"></i>
            </div>
            <h2 class="text-2xl font-bold text-gray-900 mb-2">MISI DESA KRANDEGAN</h2>
            <div class="w-24 h-1 bg-green-600 mx-auto"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Misi 1 -->
            <div class="bg-green-50 rounded-lg p-6 border-l-4 border-green-500">
                <div class="flex items-start">
                    <div class="flex-shrink-0 w-8 h-8 bg-green-500 rounded-full flex items-center justify-center text-white font-bold text-sm mr-4">
                        1
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900 mb-2">Pemerintahan Transparan dan Akuntabel</h3>
                        <p class="text-sm text-gray-700">
                            Menyelenggarakan pemerintahan desa yang bersih, transparan, dan akuntabel 
                            dengan melibatkan partisipasi aktif masyarakat dalam setiap proses pengambilan keputusan.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Misi 2 -->
            <div class="bg-blue-50 rounded-lg p-6 border-l-4 border-blue-500">
                <div class="flex items-start">
                    <div class="flex-shrink-0 w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold text-sm mr-4">
                        2
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900 mb-2">Pembangunan Infrastruktur Berkelanjutan</h3>
                        <p class="text-sm text-gray-700">
                            Meningkatkan kualitas dan kuantitas infrastruktur desa yang ramah lingkungan 
                            untuk mendukung aktivitas ekonomi dan sosial masyarakat.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Misi 3 -->
            <div class="bg-yellow-50 rounded-lg p-6 border-l-4 border-yellow-500">
                <div class="flex items-start">
                    <div class="flex-shrink-0 w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center text-white font-bold text-sm mr-4">
                        3
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900 mb-2">Pemberdayaan Ekonomi Masyarakat</h3>
                        <p class="text-sm text-gray-700">
                            Mengembangkan potensi ekonomi lokal melalui peningkatan kapasitas UMKM, 
                            koperasi, dan BUMDes untuk menciptakan lapangan kerja dan meningkatkan pendapatan.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Misi 4 -->
            <div class="bg-red-50 rounded-lg p-6 border-l-4 border-red-500">
                <div class="flex items-start">
                    <div class="flex-shrink-0 w-8 h-8 bg-red-500 rounded-full flex items-center justify-center text-white font-bold text-sm mr-4">
                        4
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900 mb-2">Peningkatan Kualitas SDM</h3>
                        <p class="text-sm text-gray-700">
                            Meningkatkan kualitas sumber daya manusia melalui program pendidikan, 
                            pelatihan keterampilan, dan pembinaan kesehatan masyarakat.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Misi 5 -->
            <div class="bg-purple-50 rounded-lg p-6 border-l-4 border-purple-500">
                <div class="flex items-start">
                    <div class="flex-shrink-0 w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center text-white font-bold text-sm mr-4">
                        5
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900 mb-2">Pelestarian Budaya dan Lingkungan</h3>
                        <p class="text-sm text-gray-700">
                            Melestarikan nilai-nilai budaya lokal dan menjaga kelestarian lingkungan 
                            untuk generasi mendatang melalui program-program konservasi.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Misi 6 -->
            <div class="bg-teal-50 rounded-lg p-6 border-l-4 border-teal-500">
                <div class="flex items-start">
                    <div class="flex-shrink-0 w-8 h-8 bg-teal-500 rounded-full flex items-center justify-center text-white font-bold text-sm mr-4">
                        6
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900 mb-2">Penguatan Keamanan dan Ketertiban</h3>
                        <p class="text-sm text-gray-700">
                            Menciptakan lingkungan yang aman, tentram, dan tertib melalui 
                            penguatan sistem keamanan berbasis masyarakat dan nilai-nilai gotong royong.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Strategic Goals -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
            <i class="fas fa-target text-orange-600 mr-2"></i>
            Tujuan Strategis 2024-2029
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="text-center">
                <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-chart-line text-3xl text-blue-600"></i>
                </div>
                <h3 class="font-bold text-gray-900 mb-2">Peningkatan Ekonomi</h3>
                <p class="text-sm text-gray-700">
                    Meningkatkan pendapatan per kapita masyarakat sebesar 25% dalam 5 tahun
                </p>
            </div>
            
            <div class="text-center">
                <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-graduation-cap text-3xl text-green-600"></i>
                </div>
                <h3 class="font-bold text-gray-900 mb-2">Peningkatan Pendidikan</h3>
                <p class="text-sm text-gray-700">
                    Mencapai tingkat pendidikan minimal SMA untuk 90% penduduk usia produktif
                </p>
            </div>
            
            <div class="text-center">
                <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-heartbeat text-3xl text-red-600"></i>
                </div>
                <h3 class="font-bold text-gray-900 mb-2">Peningkatan Kesehatan</h3>
                <p class="text-sm text-gray-700">
                    Menurunkan angka kesakitan dan meningkatkan derajat kesehatan masyarakat
                </p>
            </div>
        </div>
    </div>

    <!-- Implementation Strategy -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
            <i class="fas fa-cogs text-gray-600 mr-2"></i>
            Strategi Implementasi
        </h2>
        
        <div class="space-y-4">
            <div class="flex items-start space-x-4">
                <div class="flex-shrink-0 w-8 h-8 bg-indigo-500 rounded-full flex items-center justify-center">
                    <i class="fas fa-users text-white text-sm"></i>
                </div>
                <div>
                    <h3 class="font-bold text-gray-900 mb-1">Partisipasi Masyarakat</h3>
                    <p class="text-sm text-gray-700">
                        Melibatkan seluruh lapisan masyarakat dalam perencanaan, pelaksanaan, 
                        dan evaluasi program pembangunan desa.
                    </p>
                </div>
            </div>
            
            <div class="flex items-start space-x-4">
                <div class="flex-shrink-0 w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                    <i class="fas fa-handshake text-white text-sm"></i>
                </div>
                <div>
                    <h3 class="font-bold text-gray-900 mb-1">Kemitraan Strategis</h3>
                    <p class="text-sm text-gray-700">
                        Membangun kerjasama dengan pemerintah, swasta, dan organisasi masyarakat 
                        untuk mempercepat pencapaian tujuan pembangunan.
                    </p>
                </div>
            </div>
            
            <div class="flex items-start space-x-4">
                <div class="flex-shrink-0 w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center">
                    <i class="fas fa-laptop text-white text-sm"></i>
                </div>
                <div>
                    <h3 class="font-bold text-gray-900 mb-1">Pemanfaatan Teknologi</h3>
                    <p class="text-sm text-gray-700">
                        Mengintegrasikan teknologi informasi dalam pelayanan publik dan 
                        pemberdayaan masyarakat untuk efisiensi dan transparansi.
                    </p>
                </div>
            </div>
            
            <div class="flex items-start space-x-4">
                <div class="flex-shrink-0 w-8 h-8 bg-red-500 rounded-full flex items-center justify-center">
                    <i class="fas fa-chart-bar text-white text-sm"></i>
                </div>
                <div>
                    <h3 class="font-bold text-gray-900 mb-1">Monitoring dan Evaluasi</h3>
                    <p class="text-sm text-gray-700">
                        Melakukan pemantauan dan evaluasi berkala terhadap pencapaian target 
                        dan indikator kinerja yang telah ditetapkan.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection