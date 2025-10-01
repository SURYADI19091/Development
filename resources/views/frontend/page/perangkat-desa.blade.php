@extends('frontend.main')

@section('title', 'Perangkat Desa - ' . strtoupper($villageProfile->village_name ?? 'Desa Krandegan'))
@section('page_title', 'PERANGKAT DESA')
@section('header_icon', 'fas fa-user-friends')
@section('header_bg_color', 'bg-emerald-600')

@section('content')
<div class="xl:col-span-3">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <div class="text-center">
            <h1 class="text-2xl font-bold text-gray-900 mb-2">Perangkat Desa Krandegan</h1>
            <p class="text-gray-600">Abdi masyarakat yang siap melayani dengan sepenuh hati</p>
        </div>
    </div>

    <!-- Village Leadership -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <h2 class="text-xl font-bold text-gray-900 mb-6 text-center">Pimpinan Desa</h2>
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Kepala Desa -->
            <div class="text-center">
                <div class="w-40 h-40 bg-emerald-100 rounded-full mx-auto mb-4 flex items-center justify-center overflow-hidden">
                    <img src="/images/kepala-desa.jpg" alt="Kepala Desa" class="w-full h-full object-cover" 
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'">
                    <div class="w-full h-full bg-emerald-500 flex items-center justify-center" style="display: none;">
                        <i class="fas fa-user text-6xl text-white"></i>
                    </div>
                </div>
                <h3 class="text-xl font-bold text-gray-900">H. SUKARNO WIJAYA, S.Sos</h3>
                <p class="text-emerald-600 font-medium mb-2">Kepala Desa</p>
                <div class="text-sm text-gray-600 space-y-1 mb-4">
                    <p>Periode: 2024-2029</p>
                    <p>Pendidikan: S1 Sosiologi</p>
                    <p>Pengalaman: 15 tahun di pemerintahan</p>
                </div>
                <div class="flex justify-center space-x-4 text-sm">
                    <div class="flex items-center text-emerald-600">
                        <i class="fas fa-envelope mr-1"></i>
                        <span>kades@desakrandegan.id</span>
                    </div>
                </div>
            </div>
            
            <!-- Sekretaris Desa -->
            <div class="text-center">
                <div class="w-40 h-40 bg-blue-100 rounded-full mx-auto mb-4 flex items-center justify-center overflow-hidden">
                    <img src="/images/sekdes.jpg" alt="Sekretaris Desa" class="w-full h-full object-cover" 
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'">
                    <div class="w-full h-full bg-blue-500 flex items-center justify-center" style="display: none;">
                        <i class="fas fa-user text-6xl text-white"></i>
                    </div>
                </div>
                <h3 class="text-xl font-bold text-gray-900">SITI NURJANAH, S.AP</h3>
                <p class="text-blue-600 font-medium mb-2">Sekretaris Desa</p>
                <div class="text-sm text-gray-600 space-y-1 mb-4">
                    <p>NIP: 197805152006042009</p>
                    <p>Pendidikan: S1 Administrasi Publik</p>
                    <p>Masa Kerja: 18 tahun</p>
                </div>
                <div class="flex justify-center space-x-4 text-sm">
                    <div class="flex items-center text-blue-600">
                        <i class="fas fa-envelope mr-1"></i>
                        <span>sekdes@desakrandegan.id</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Kepala Urusan (Kaur) -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
            <i class="fas fa-users-cog text-purple-600 mr-2"></i>
            Kepala Urusan (Kaur)
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Kaur Pemerintahan -->
            <div class="bg-green-50 border border-green-200 rounded-lg p-6">
                <div class="text-center mb-4">
                    <div class="w-24 h-24 bg-green-500 rounded-full mx-auto mb-3 flex items-center justify-center overflow-hidden">
                        <img src="/images/kaur-pemerintahan.jpg" alt="Kaur Pemerintahan" class="w-full h-full object-cover" 
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'">
                        <div class="w-full h-full bg-green-500 flex items-center justify-center" style="display: none;">
                            <i class="fas fa-user text-3xl text-white"></i>
                        </div>
                    </div>
                    <h3 class="font-bold text-gray-900">AHMAD FAUZI, S.H</h3>
                    <p class="text-green-600 text-sm mb-2">Kaur Pemerintahan</p>
                </div>
                
                <div class="space-y-2 text-sm text-gray-600 mb-4">
                    <p><span class="font-medium">Pendidikan:</span> S1 Hukum</p>
                    <p><span class="font-medium">Masa Kerja:</span> 12 tahun</p>
                    <p><span class="font-medium">Spesialisasi:</span> Administrasi & Hukum</p>
                </div>
                
                <div class="border-t pt-4">
                    <h4 class="font-medium text-gray-900 mb-2">Bidang Tugas:</h4>
                    <ul class="text-xs text-gray-600 space-y-1">
                        <li>• Administrasi Umum</li>
                        <li>• Kependudukan & Catatan Sipil</li>
                        <li>• Pertanahan</li>
                        <li>• Ketentraman & Ketertiban</li>
                        <li>• Peraturan Desa</li>
                    </ul>
                </div>
            </div>
            
            <!-- Kaur Keuangan -->
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
                <div class="text-center mb-4">
                    <div class="w-24 h-24 bg-yellow-500 rounded-full mx-auto mb-3 flex items-center justify-center overflow-hidden">
                        <img src="/images/kaur-keuangan.jpg" alt="Kaur Keuangan" class="w-full h-full object-cover" 
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'">
                        <div class="w-full h-full bg-yellow-500 flex items-center justify-center" style="display: none;">
                            <i class="fas fa-user text-3xl text-white"></i>
                        </div>
                    </div>
                    <h3 class="font-bold text-gray-900">RINA SURYANI, S.E</h3>
                    <p class="text-yellow-600 text-sm mb-2">Kaur Keuangan</p>
                </div>
                
                <div class="space-y-2 text-sm text-gray-600 mb-4">
                    <p><span class="font-medium">Pendidikan:</span> S1 Ekonomi</p>
                    <p><span class="font-medium">Masa Kerja:</span> 10 tahun</p>
                    <p><span class="font-medium">Spesialisasi:</span> Akuntansi & Keuangan</p>
                </div>
                
                <div class="border-t pt-4">
                    <h4 class="font-medium text-gray-900 mb-2">Bidang Tugas:</h4>
                    <ul class="text-xs text-gray-600 space-y-1">
                        <li>• Pengelolaan Keuangan Desa</li>
                        <li>• APBDesa & Pelaporan</li>
                        <li>• Pengelolaan Aset</li>
                        <li>• Perencanaan Pembangunan</li>
                        <li>• Administrasi Keuangan</li>
                    </ul>
                </div>
            </div>
            
            <!-- Kaur Pelayanan -->
            <div class="bg-purple-50 border border-purple-200 rounded-lg p-6">
                <div class="text-center mb-4">
                    <div class="w-24 h-24 bg-purple-500 rounded-full mx-auto mb-3 flex items-center justify-center overflow-hidden">
                        <img src="/images/kaur-pelayanan.jpg" alt="Kaur Pelayanan" class="w-full h-full object-cover" 
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'">
                        <div class="w-full h-full bg-purple-500 flex items-center justify-center" style="display: none;">
                            <i class="fas fa-user text-3xl text-white"></i>
                        </div>
                    </div>
                    <h3 class="font-bold text-gray-900">INDAH PERMATA, S.Sos</h3>
                    <p class="text-purple-600 text-sm mb-2">Kaur Pelayanan</p>
                </div>
                
                <div class="space-y-2 text-sm text-gray-600 mb-4">
                    <p><span class="font-medium">Pendidikan:</span> S1 Sosiologi</p>
                    <p><span class="font-medium">Masa Kerja:</span> 8 tahun</p>
                    <p><span class="font-medium">Spesialisasi:</span> Pelayanan Publik</p>
                </div>
                
                <div class="border-t pt-4">
                    <h4 class="font-medium text-gray-900 mb-2">Bidang Tugas:</h4>
                    <ul class="text-xs text-gray-600 space-y-1">
                        <li>• Pelayanan Umum</li>
                        <li>• Kesejahteraan Rakyat</li>
                        <li>• Pemberdayaan Masyarakat</li>
                        <li>• Kesehatan & Pendidikan</li>
                        <li>• Sosial & Budaya</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Kepala Dusun -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
            <i class="fas fa-map-marked-alt text-orange-600 mr-2"></i>
            Kepala Dusun (Kadus)
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Kadus I -->
            <div class="bg-orange-50 border border-orange-200 rounded-lg p-6">
                <div class="flex items-center space-x-4 mb-4">
                    <div class="w-16 h-16 bg-orange-500 rounded-full flex items-center justify-center">
                        <i class="fas fa-user text-white text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900">BAMBANG SUTRISNO</h3>
                        <p class="text-orange-600 text-sm">Kepala Dusun I</p>
                        <p class="text-xs text-gray-600">Masa Jabatan: 2022-2028</p>
                    </div>
                </div>
                <div class="bg-white rounded-lg p-3 mb-3">
                    <h4 class="font-medium text-gray-900 mb-2">Wilayah Kerja:</h4>
                    <div class="grid grid-cols-3 gap-2 text-xs">
                        <div class="bg-orange-100 p-2 rounded text-center">
                            <div class="font-bold">RT 01</div>
                            <div class="text-gray-600">95 KK</div>
                        </div>
                        <div class="bg-orange-100 p-2 rounded text-center">
                            <div class="font-bold">RT 02</div>
                            <div class="text-gray-600">98 KK</div>
                        </div>
                        <div class="bg-orange-100 p-2 rounded text-center">
                            <div class="font-bold">RT 03</div>
                            <div class="text-gray-600">92 KK</div>
                        </div>
                    </div>
                </div>
                <div class="text-xs text-gray-600">
                    <p><strong>Total:</strong> 285 KK • 892 Jiwa</p>
                    <p><strong>Kontak:</strong> 0821-xxxx-xxxx</p>
                </div>
            </div>
            
            <!-- Kadus II -->
            <div class="bg-green-50 border border-green-200 rounded-lg p-6">
                <div class="flex items-center space-x-4 mb-4">
                    <div class="w-16 h-16 bg-green-500 rounded-full flex items-center justify-center">
                        <i class="fas fa-user text-white text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900">SARTONO WIJAYA</h3>
                        <p class="text-green-600 text-sm">Kepala Dusun II</p>
                        <p class="text-xs text-gray-600">Masa Jabatan: 2022-2028</p>
                    </div>
                </div>
                <div class="bg-white rounded-lg p-3 mb-3">
                    <h4 class="font-medium text-gray-900 mb-2">Wilayah Kerja:</h4>
                    <div class="grid grid-cols-3 gap-2 text-xs">
                        <div class="bg-green-100 p-2 rounded text-center">
                            <div class="font-bold">RT 04</div>
                            <div class="text-gray-600">102 KK</div>
                        </div>
                        <div class="bg-green-100 p-2 rounded text-center">
                            <div class="font-bold">RT 05</div>
                            <div class="text-gray-600">96 KK</div>
                        </div>
                        <div class="bg-green-100 p-2 rounded text-center">
                            <div class="font-bold">RT 06</div>
                            <div class="text-gray-600">100 KK</div>
                        </div>
                    </div>
                </div>
                <div class="text-xs text-gray-600">
                    <p><strong>Total:</strong> 298 KK • 925 Jiwa</p>
                    <p><strong>Kontak:</strong> 0822-xxxx-xxxx</p>
                </div>
            </div>
            
            <!-- Kadus III -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                <div class="flex items-center space-x-4 mb-4">
                    <div class="w-16 h-16 bg-blue-500 rounded-full flex items-center justify-center">
                        <i class="fas fa-user text-white text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900">HERI SETIAWAN</h3>
                        <p class="text-blue-600 text-sm">Kepala Dusun III</p>
                        <p class="text-xs text-gray-600">Masa Jabatan: 2022-2028</p>
                    </div>
                </div>
                <div class="bg-white rounded-lg p-3 mb-3">
                    <h4 class="font-medium text-gray-900 mb-2">Wilayah Kerja:</h4>
                    <div class="grid grid-cols-3 gap-2 text-xs">
                        <div class="bg-blue-100 p-2 rounded text-center">
                            <div class="font-bold">RT 07</div>
                            <div class="text-gray-600">88 KK</div>
                        </div>
                        <div class="bg-blue-100 p-2 rounded text-center">
                            <div class="font-bold">RT 08</div>
                            <div class="text-gray-600">94 KK</div>
                        </div>
                        <div class="bg-blue-100 p-2 rounded text-center">
                            <div class="font-bold">RT 09</div>
                            <div class="text-gray-600">90 KK</div>
                        </div>
                    </div>
                </div>
                <div class="text-xs text-gray-600">
                    <p><strong>Total:</strong> 272 KK • 845 Jiwa</p>
                    <p><strong>Kontak:</strong> 0823-xxxx-xxxx</p>
                </div>
            </div>
            
            <!-- Kadus IV -->
            <div class="bg-purple-50 border border-purple-200 rounded-lg p-6">
                <div class="flex items-center space-x-4 mb-4">
                    <div class="w-16 h-16 bg-purple-500 rounded-full flex items-center justify-center">
                        <i class="fas fa-user text-white text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900">JOKO PRIYANTO</h3>
                        <p class="text-purple-600 text-sm">Kepala Dusun IV</p>
                        <p class="text-xs text-gray-600">Masa Jabatan: 2022-2028</p>
                    </div>
                </div>
                <div class="bg-white rounded-lg p-3 mb-3">
                    <h4 class="font-medium text-gray-900 mb-2">Wilayah Kerja:</h4>
                    <div class="grid grid-cols-3 gap-2 text-xs">
                        <div class="bg-purple-100 p-2 rounded text-center">
                            <div class="font-bold">RT 10</div>
                            <div class="text-gray-600">45 KK</div>
                        </div>
                        <div class="bg-purple-100 p-2 rounded text-center">
                            <div class="font-bold">RT 11</div>
                            <div class="text-gray-600">42 KK</div>
                        </div>
                        <div class="bg-purple-100 p-2 rounded text-center">
                            <div class="font-bold">RT 12</div>
                            <div class="text-gray-600">44 KK</div>
                        </div>
                    </div>
                </div>
                <div class="text-xs text-gray-600">
                    <p><strong>Total:</strong> 131 KK • 370 Jiwa</p>
                    <p><strong>Kontak:</strong> 0824-xxxx-xxxx</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Staff Support -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
            <i class="fas fa-hands-helping text-teal-600 mr-2"></i>
            Staf Pendukung
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-teal-50 border border-teal-200 rounded-lg p-4 text-center">
                <div class="w-12 h-12 bg-teal-500 rounded-full mx-auto mb-2 flex items-center justify-center">
                    <i class="fas fa-user text-white"></i>
                </div>
                <h4 class="font-medium text-gray-900 text-sm">DEWI KUSUMA</h4>
                <p class="text-xs text-teal-600">Operator SISKEUDES</p>
            </div>
            
            <div class="bg-cyan-50 border border-cyan-200 rounded-lg p-4 text-center">
                <div class="w-12 h-12 bg-cyan-500 rounded-full mx-auto mb-2 flex items-center justify-center">
                    <i class="fas fa-user text-white"></i>
                </div>
                <h4 class="font-medium text-gray-900 text-sm">ANDI PRAYOGA</h4>
                <p class="text-xs text-cyan-600">Staf IT & Website</p>
            </div>
            
            <div class="bg-lime-50 border border-lime-200 rounded-lg p-4 text-center">
                <div class="w-12 h-12 bg-lime-500 rounded-full mx-auto mb-2 flex items-center justify-center">
                    <i class="fas fa-user text-white"></i>
                </div>
                <h4 class="font-medium text-gray-900 text-sm">SARI WULANDARI</h4>
                <p class="text-xs text-lime-600">Bidan Desa</p>
            </div>
            
            <div class="bg-rose-50 border border-rose-200 rounded-lg p-4 text-center">
                <div class="w-12 h-12 bg-rose-500 rounded-full mx-auto mb-2 flex items-center justify-center">
                    <i class="fas fa-user text-white"></i>
                </div>
                <h4 class="font-medium text-gray-900 text-sm">RAHMAN HAKIM</h4>
                <p class="text-xs text-rose-600">Keamanan Desa</p>
            </div>
        </div>
    </div>
</div>
@endsection