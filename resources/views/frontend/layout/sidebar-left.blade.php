<!-- Mobile Sidebar Overlay -->
<div id="mobile-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden hidden"></div>

<!-- Left Sidebar -->
<aside id="sidebar" 
    class="w-80 bg-white shadow-lg fixed lg:static inset-y-0 left-0 z-50
           transform -translate-x-full lg:translate-x-0 
           transition-transform duration-300 ease-in-out
           lg:h-auto lg:overflow-visible h-screen overflow-y-auto">
    <!-- Village Header -->
    <a href="{{ route('home') }}" class="block">
        <div class="bg-gradient-to-r from-green-400 to-green-600 text-white p-6 text-center hover:from-green-500 hover:to-green-700 transition-colors duration-200">
            <h2 class="text-xl font-bold">@yield('village_name', 'DESA CIWULAN')</h2>
            <p class="text-sm text-green-100 mt-1">@yield('village_location', 'Telagasari, Karawang')</p>
        </div>
    </a>

    <!-- Navigation Grid -->
    <div class="p-3 sm:p-4">
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 sm:gap-3 mb-4 sm:mb-6">
            <!-- Row 1 -->
            <a href="/profile-desa" class="bg-blue-500 hover:bg-blue-600 text-white p-2 sm:p-4 rounded-lg text-center cursor-pointer transition-colors block">
                <i class="fas fa-users text-lg sm:text-2xl mb-1 sm:mb-2"></i>
                <p class="text-xs font-medium">Identitas Desa</p>
            </a>
            <a href="/perangkat-desa" class="bg-gray-600 hover:bg-gray-700 text-white p-2 sm:p-4 rounded-lg text-center cursor-pointer transition-colors block">
                <i class="fas fa-desktop text-lg sm:text-2xl mb-1 sm:mb-2"></i>
                <p class="text-xs font-medium">Aparatur Desa</p>
            </a>
            <a href="/data-penduduk" class="bg-red-500 hover:bg-red-600 text-white p-2 sm:p-4 rounded-lg text-center cursor-pointer transition-colors sm:block hidden">
                <i class="fas fa-chart-line text-lg sm:text-2xl mb-1 sm:mb-2"></i>
                <p class="text-xs font-medium">Data Penduduk</p>
            </a>
            
            <!-- Show Statistik Wilayah on mobile (moved from row 1) -->
            <a href="/data-penduduk" class="bg-red-500 hover:bg-red-600 text-white p-2 sm:p-4 rounded-lg text-center cursor-pointer transition-colors sm:hidden block">
                <i class="fas fa-chart-line text-lg sm:text-2xl mb-1 sm:mb-2"></i>
                <p class="text-xs font-medium">Statistik Wilayah</p>
            </a>
            
            <!-- Row 2 -->
            <a href="/galeri" class="bg-orange-500 hover:bg-orange-600 text-white p-2 sm:p-4 rounded-lg text-center cursor-pointer transition-colors block">
                <i class="fas fa-camera text-lg sm:text-2xl mb-1 sm:mb-2"></i>
                <p class="text-xs font-medium">Galeri Foto</p>
            </a>
            <a href="/wisata" class="bg-purple-500 hover:bg-purple-600 text-white p-2 sm:p-4 rounded-lg text-center cursor-pointer transition-colors block">
                <i class="fas fa-mountain text-lg sm:text-2xl mb-1 sm:mb-2"></i>
                <p class="text-xs font-medium">Wisata Desa</p>
            </a>
            <a href="/umkm" class="bg-yellow-500 hover:bg-yellow-600 text-white p-2 sm:p-4 rounded-lg text-center cursor-pointer transition-colors block">
                <i class="fas fa-store text-lg sm:text-2xl mb-1 sm:mb-2"></i>
                <p class="text-xs font-medium">UMKM Desa</p>
            </a>
            
            <!-- Row 3 -->
            <a href="/kontak" class="bg-red-600 hover:bg-red-700 text-white p-2 sm:p-4 rounded-lg text-center cursor-pointer transition-colors block">
                <i class="fas fa-map-marker-alt text-lg sm:text-2xl mb-1 sm:mb-2"></i>
                <p class="text-xs font-medium">Kontak & Peta</p>
            </a>
            <a href="/berita" class="bg-yellow-600 hover:bg-yellow-700 text-white p-2 sm:p-4 rounded-lg text-center cursor-pointer transition-colors block">
                <i class="fas fa-newspaper text-lg sm:text-2xl mb-1 sm:mb-2"></i>
                <p class="text-xs font-medium">Berita Desa</p>
            </a>
            <a href="/layanan-surat" class="bg-teal-500 hover:bg-teal-600 text-white p-2 sm:p-4 rounded-lg text-center cursor-pointer transition-colors block">
                <i class="fas fa-file-alt text-lg sm:text-2xl mb-1 sm:mb-2"></i>
                <p class="text-xs font-medium">Layanan Surat</p>
            </a>
        </div>

        <!-- Menu List -->
        <div class="space-y-1">
            <!-- Profil Dropdown -->
            <div class="border-b border-gray-200">
                <div class="p-2 sm:p-3 hover:bg-gray-100 cursor-pointer flex justify-between items-center" onclick="toggleDropdown('profil')">
                    <p class="font-medium text-gray-800 text-sm sm:text-base">Profil</p>
                    <i class="fas fa-chevron-down transform transition-transform duration-200" id="profil-icon"></i>
                </div>
                <div class="hidden bg-gray-50 pl-4" id="profil-dropdown">
                    <a href="/profile-desa" class="p-2 sm:p-3 hover:bg-gray-200 cursor-pointer border-b border-gray-300 block">
                        <p class="text-gray-700 text-xs sm:text-sm">Profile</p>
                    </a>
                    <a href="/sejarah" class="p-2 sm:p-3 hover:bg-gray-200 cursor-pointer border-b border-gray-300 block">
                        <p class="text-gray-700 text-xs sm:text-sm">Sejarah Desa</p>
                    </a>
                    <a href="/visi-misi" class="p-2 sm:p-3 hover:bg-gray-200 cursor-pointer border-b border-gray-300 block">
                        <p class="text-gray-700 text-xs sm:text-sm">Visi & Misi</p>
                    </a>
                    <a href="/struktur-pemerintahan" class="p-2 sm:p-3 hover:bg-gray-200 cursor-pointer block">
                        <p class="text-gray-700 text-xs sm:text-sm">Struktur Organisasi</p>
                    </a>
                </div>
            </div>

            <!-- Statistik -->
            <a href="/statistik-penduduk" class="p-2 sm:p-3 hover:bg-gray-100 cursor-pointer border-b border-gray-200 block">
                <p class="font-medium text-gray-800 text-sm sm:text-base">Statistik</p>
            </a>

            <!-- APBDes Dropdown -->
            <div class="border-b border-gray-200">
                <div class="p-2 sm:p-3 hover:bg-gray-100 cursor-pointer flex justify-between items-center" onclick="toggleDropdown('apbdes')">
                    <p class="font-medium text-gray-800 text-sm sm:text-base">APBDes</p>
                    <i class="fas fa-chevron-down transform transition-transform duration-200" id="apbdes-icon"></i>
                </div>
                <div class="hidden bg-gray-50 pl-4" id="apbdes-dropdown">
                    <a href="{{ route('budget.index') }}" class="p-2 sm:p-3 hover:bg-gray-200 cursor-pointer border-b border-gray-300 block">
                        <p class="text-gray-700 text-xs sm:text-sm">Overview</p>
                    </a>
                    <a href="{{ route('budget.plan') }}" class="p-2 sm:p-3 hover:bg-gray-200 cursor-pointer border-b border-gray-300 block">
                        <p class="text-gray-700 text-xs sm:text-sm">Anggaran</p>
                    </a>
                    <a href="{{ route('budget.realization') }}" class="p-2 sm:p-3 hover:bg-gray-200 cursor-pointer border-b border-gray-300 block">
                        <p class="text-gray-700 text-xs sm:text-sm">Realisasi</p>
                    </a>
                    <a href="{{ route('budget.report') }}" class="p-2 sm:p-3 hover:bg-gray-200 cursor-pointer block">
                        <p class="text-gray-700 text-xs sm:text-sm">Laporan</p>
                    </a>
                </div>
            </div>

            <!-- Artikel Dropdown -->
            <div class="border-b border-gray-200">
                <div class="p-2 sm:p-3 hover:bg-gray-100 cursor-pointer flex justify-between items-center" onclick="toggleDropdown('artikel')">
                    <p class="font-medium text-gray-800 text-sm sm:text-base">Artikel</p>
                    <i class="fas fa-chevron-down transform transition-transform duration-200" id="artikel-icon"></i>
                </div>
                <div class="hidden bg-gray-50 pl-4" id="artikel-dropdown">
                    <a href="/berita" class="p-2 sm:p-3 hover:bg-gray-200 cursor-pointer border-b border-gray-300 block">
                        <p class="text-gray-700 text-xs sm:text-sm">Berita Desa</p>
                    </a>
                    <a href="/pengumuman" class="p-2 sm:p-3 hover:bg-gray-200 cursor-pointer border-b border-gray-300 block">
                        <p class="text-gray-700 text-xs sm:text-sm">Pengumuman</p>
                    </a>
                    <a href="/agenda" class="p-2 sm:p-3 hover:bg-gray-200 cursor-pointer block">
                        <p class="text-gray-700 text-xs sm:text-sm">Agenda Kegiatan</p>
                    </a>
                </div>
            </div>

            <!-- Ekonomi Dropdown -->
            <div class="border-b border-gray-200">
                <div class="p-2 sm:p-3 hover:bg-gray-100 cursor-pointer flex justify-between items-center" onclick="toggleDropdown('ekonomi')">
                    <p class="font-medium text-gray-800 text-sm sm:text-base">Ekonomi</p>
                    <i class="fas fa-chevron-down transform transition-transform duration-200" id="ekonomi-icon"></i>
                </div>
                <div class="hidden bg-gray-50 pl-4" id="ekonomi-dropdown">
                    <a href="/umkm" class="p-2 sm:p-3 hover:bg-gray-200 cursor-pointer border-b border-gray-300 block">
                        <p class="text-gray-700 text-xs sm:text-sm">UMKM</p>
                    </a>
                    <a href="/wisata" class="p-2 sm:p-3 hover:bg-gray-200 cursor-pointer border-b border-gray-300 block">
                        <p class="text-gray-700 text-xs sm:text-sm">Wisata Desa</p>
                    </a>
                </div>
            </div>

            <!-- Informasi Dropdown -->
            <div class="border-b border-gray-200">
                <div class="p-2 sm:p-3 hover:bg-gray-100 cursor-pointer flex justify-between items-center" onclick="toggleDropdown('informasi')">
                    <p class="font-medium text-gray-800 text-sm sm:text-base">Informasi</p>
                    <i class="fas fa-chevron-down transform transition-transform duration-200" id="informasi-icon"></i>
                </div>
                <div class="hidden bg-gray-50 pl-4" id="informasi-dropdown">
                    <a href="/layanan-surat" class="p-2 sm:p-3 hover:bg-gray-200 cursor-pointer border-b border-gray-300 block">
                        <p class="text-gray-700 text-xs sm:text-sm">Layanan Surat</p>
                    </a>
                    <a href="/pengajuan-surat" class="p-2 sm:p-3 hover:bg-gray-200 cursor-pointer border-b border-gray-300 block">
                        <p class="text-gray-700 text-xs sm:text-sm">Pengajuan Online</p>
                    </a>
                    <a href="/kontak" class="p-2 sm:p-3 hover:bg-gray-200 cursor-pointer block">
                        <p class="text-gray-700 text-xs sm:text-sm">Kontak Desa</p>
                    </a>
                </div>
            </div>

            <!-- Keamanan Dropdown -->
            <div class="border-b border-gray-200">
                <div class="p-2 sm:p-3 hover:bg-gray-100 cursor-pointer flex justify-between items-center" onclick="toggleDropdown('keamanan')">
                    <p class="font-medium text-gray-800 text-sm sm:text-base">Keamanan</p>
                    <i class="fas fa-chevron-down transform transition-transform duration-200" id="keamanan-icon"></i>
                </div>
                <div class="hidden bg-gray-50 pl-4" id="keamanan-dropdown">
                    <div class="p-2 sm:p-3 hover:bg-gray-200 cursor-pointer border-b border-gray-300">
                        <p class="text-gray-700 text-xs sm:text-sm">Pos Kamling</p>
                    </div>
                    <div class="p-2 sm:p-3 hover:bg-gray-200 cursor-pointer border-b border-gray-300">
                        <p class="text-gray-700 text-xs sm:text-sm">Hansip</p>
                    </div>
                    <div class="p-2 sm:p-3 hover:bg-gray-200 cursor-pointer">
                        <p class="text-gray-700 text-xs sm:text-sm">Laporan Kejadian</p>
                    </div>
                </div>
            </div>

            <!-- BUMDes Dropdown -->
            <div class="border-b border-gray-200">
                <div class="p-2 sm:p-3 hover:bg-gray-100 cursor-pointer flex justify-between items-center" onclick="toggleDropdown('bumdes')">
                    <p class="font-medium text-gray-800 text-sm sm:text-base">BUMDes</p>
                    <i class="fas fa-chevron-down transform transition-transform duration-200" id="bumdes-icon"></i>
                </div>
                <div class="hidden bg-gray-50 pl-4" id="bumdes-dropdown">
                    <div class="p-2 sm:p-3 hover:bg-gray-200 cursor-pointer border-b border-gray-300">
                        <p class="text-gray-700 text-xs sm:text-sm">Unit Usaha</p>
                    </div>
                    <div class="p-2 sm:p-3 hover:bg-gray-200 cursor-pointer border-b border-gray-300">
                        <p class="text-gray-700 text-xs sm:text-sm">Keuangan</p>
                    </div>
                    <div class="p-2 sm:p-3 hover:bg-gray-200 cursor-pointer">
                        <p class="text-gray-700 text-xs sm:text-sm">Program</p>
                    </div>
                </div>
            </div>

            <!-- Authentication Section -->
            <div class="mt-4 pt-4 border-t border-gray-300">
                <div class="px-2 mb-2">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Akun Pengguna</p>
                </div>
                <div class="space-y-1">
                    <a href="{{ route('login') }}" class="flex items-center p-2 sm:p-3 hover:bg-blue-50 cursor-pointer rounded-lg mx-2 transition-colors group">
                        <div class="flex items-center justify-center w-8 h-8 bg-blue-100 rounded-lg mr-3 group-hover:bg-blue-200">
                            <i class="fas fa-sign-in-alt text-blue-600 text-sm"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-800 text-sm">Masuk</p>
                            <p class="text-xs text-gray-600">Login ke akun Anda</p>
                        </div>
                    </a>          
                </div>
            </div>
        </div>
    </div>
</aside>

<!-- Dropdown Toggle Script -->
<script>
    function toggleDropdown(menuId) {
        const dropdown = document.getElementById(menuId + '-dropdown');
        const icon = document.getElementById(menuId + '-icon');
        
        if (dropdown.classList.contains('hidden')) {
            // Close all other dropdowns first
            const allDropdowns = document.querySelectorAll('[id$="-dropdown"]');
            const allIcons = document.querySelectorAll('[id$="-icon"]');
            
            allDropdowns.forEach(dd => dd.classList.add('hidden'));
            allIcons.forEach(ic => ic.classList.remove('rotate-180'));
            
            // Open the clicked dropdown
            dropdown.classList.remove('hidden');
            icon.classList.add('rotate-180');
        } else {
            // Close the clicked dropdown
            dropdown.classList.add('hidden');
            icon.classList.remove('rotate-180');
        }
    }
</script>
