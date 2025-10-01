@extends('frontend.main')

@section('title', 'Kontak - ' . strtoupper($villageProfile->village_name ?? 'Desa Krandegan'))
@section('page_title', 'KONTAK KAMI')
@section('header_icon', 'fas fa-phone-alt')
@section('header_bg_color', 'bg-gray-600')

@section('content')
<div class="xl:col-span-3">
    <!-- Contact Information -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Office Info -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-building text-blue-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="font-bold text-gray-900">Kantor Desa</h3>
                    <p class="text-sm text-gray-600">Jam Pelayanan</p>
                </div>
            </div>
            <div class="space-y-2 text-sm text-gray-700">
                <div class="flex items-center">
                    <i class="fas fa-map-marker-alt text-blue-500 mr-3 w-4"></i>
                    <span>{{ $villageProfile->address ?? 'Alamat belum diatur' }}</span>
                </div>
                <div class="flex items-center">
                    <i class="fas fa-clock text-blue-500 mr-3 w-4"></i>
                    <span>{{ $villageProfile->office_hours ?? 'Senin - Jumat: 08:00 - 16:00 WIB' }}</span>
                </div>
            </div>
        </div>

        <!-- Contact Numbers -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-phone text-green-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="font-bold text-gray-900">Telepon & WhatsApp</h3>
                    <p class="text-sm text-gray-600">Hubungi Kami</p>
                </div>
            </div>
            <div class="space-y-3">
                @if($villageProfile->phone)
                <div class="flex items-center justify-between">
                    <div class="flex items-center text-sm text-gray-700">
                        <i class="fas fa-phone text-green-500 mr-3 w-4"></i>
                        <span>{{ $villageProfile->phone }}</span>
                    </div>
                    <button class="px-2 py-1 bg-green-100 text-green-700 rounded text-xs hover:bg-green-200" onclick="window.open('tel:{{ $villageProfile->phone }}')">
                        <i class="fas fa-phone mr-1"></i>Call
                    </button>
                </div>
                @endif
                @if($villageProfile->whatsapp)
                <div class="flex items-center justify-between">
                    <div class="flex items-center text-sm text-gray-700">
                        <i class="fab fa-whatsapp text-green-500 mr-3 w-4"></i>
                        <span>{{ $villageProfile->whatsapp }}</span>
                    </div>
                    <button class="px-2 py-1 bg-green-100 text-green-700 rounded text-xs hover:bg-green-200" onclick="window.open('https://wa.me/{{ str_replace(['+', '-', ' '], '', $villageProfile->whatsapp) }}')">
                        <i class="fab fa-whatsapp mr-1"></i>WA
                    </button>
                </div>
                @endif
                @if($villageProfile->fax)
                <div class="flex items-center justify-between">
                    <div class="flex items-center text-sm text-gray-700">
                        <i class="fas fa-fax text-green-500 mr-3 w-4"></i>
                        <span>{{ $villageProfile->fax }}</span>
                    </div>
                    <span class="text-xs text-gray-500">Fax</span>
                </div>
                @endif
            </div>
        </div>

        <!-- Digital Contact -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-envelope text-purple-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="font-bold text-gray-900">Email & Media Sosial</h3>
                    <p class="text-sm text-gray-600">Kontak Digital</p>
                </div>
            </div>
            <div class="space-y-3">
                @if($villageProfile->email)
                <div class="flex items-center justify-between">
                    <div class="flex items-center text-sm text-gray-700">
                        <i class="fas fa-envelope text-purple-500 mr-3 w-4"></i>
                        <span>{{ $villageProfile->email }}</span>
                    </div>
                    <button class="px-2 py-1 bg-purple-100 text-purple-700 rounded text-xs hover:bg-purple-200" onclick="window.open('mailto:{{ $villageProfile->email }}')">
                        <i class="fas fa-envelope mr-1"></i>Email
                    </button>
                </div>
                @endif
                @if($villageProfile->facebook)
                <div class="flex items-center justify-between">
                    <div class="flex items-center text-sm text-gray-700">
                        <i class="fab fa-facebook text-blue-500 mr-3 w-4"></i>
                        <span>{{ $villageProfile->facebook }}</span>
                    </div>
                    <button class="px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs hover:bg-blue-200" onclick="window.open('{{ $villageProfile->facebook }}')">
                        <i class="fab fa-facebook mr-1"></i>FB
                    </button>
                </div>
                @endif
                @if($villageProfile->instagram)
                <div class="flex items-center justify-between">
                    <div class="flex items-center text-sm text-gray-700">
                        <i class="fab fa-instagram text-pink-500 mr-3 w-4"></i>
                        <span>{{ $villageProfile->instagram }}</span>
                    </div>
                    <button class="px-2 py-1 bg-pink-100 text-pink-700 rounded text-xs hover:bg-pink-200" onclick="window.open('{{ $villageProfile->instagram }}')">
                        <i class="fab fa-instagram mr-1"></i>IG
                    </button>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Contact Form -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Form -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center mb-6">
                <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-paper-plane text-gray-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="font-bold text-gray-900">Kirim Pesan</h3>
                    <p class="text-sm text-gray-600">Sampaikan pertanyaan atau saran Anda</p>
                </div>
            </div>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle mr-2"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            <form action="{{ route('contact.submit') }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap *</label>
                        <input type="text" name="name" required value="{{ old('name') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 focus:border-gray-500 @error('name') border-red-300 @enderror"
                               placeholder="Masukkan nama lengkap">
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">No. Telepon</label>
                        <input type="tel" name="phone" value="{{ old('phone') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 focus:border-gray-500 @error('phone') border-red-300 @enderror"
                               placeholder="Contoh: 081234567890">
                        @error('phone')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                    <input type="email" name="email" required value="{{ old('email') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 focus:border-gray-500 @error('email') border-red-300 @enderror"
                           placeholder="email@example.com">
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kategori Pesan *</label>
                    <select name="subject" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 focus:border-gray-500 @error('subject') border-red-300 @enderror">
                        <option value="">Pilih kategori...</option>
                        <option value="Informasi Umum" {{ old('subject') == 'Informasi Umum' ? 'selected' : '' }}>Informasi Umum</option>
                        <option value="Keluhan Pelayanan" {{ old('subject') == 'Keluhan Pelayanan' ? 'selected' : '' }}>Keluhan Pelayanan</option>
                        <option value="Saran & Kritik" {{ old('subject') == 'Saran & Kritik' ? 'selected' : '' }}>Saran & Kritik</option>
                        <option value="Pengaduan" {{ old('subject') == 'Pengaduan' ? 'selected' : '' }}>Pengaduan</option>
                        <option value="Kerjasama" {{ old('subject') == 'Kerjasama' ? 'selected' : '' }}>Kerjasama</option>
                        <option value="Lainnya" {{ old('subject') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                    @error('subject')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pesan *</label>
                    <textarea name="message" required rows="4" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 focus:border-gray-500 resize-none @error('message') border-red-300 @enderror"
                              placeholder="Tuliskan pesan Anda dengan jelas...">{{ old('message') }}</textarea>
                    @error('message')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-start">
                    <input type="checkbox" required 
                           class="mt-1 h-4 w-4 text-gray-600 focus:ring-gray-500 border-gray-300 rounded">
                    <label class="ml-2 text-sm text-gray-700">
                        Saya menyetujui bahwa data yang saya berikan akan diproses sesuai dengan 
                        <button type="button" class="text-gray-600 hover:text-gray-800 underline">kebijakan privasi</button>
                    </label>
                </div>

                <button type="submit" 
                        class="w-full bg-gray-600 text-white py-3 px-4 rounded-lg hover:bg-gray-700 focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition duration-200">
                    <i class="fas fa-paper-plane mr-2"></i>
                    Kirim Pesan
                </button>
            </form>
        </div>

        <!-- Emergency Contacts & FAQ -->
        <div class="space-y-6">
            <!-- Emergency -->
            <div class="bg-red-50 border border-red-200 rounded-lg p-6">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-exclamation-triangle text-red-600"></i>
                    </div>
                    <h3 class="font-bold text-red-900">Kontak Darurat</h3>
                </div>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center text-sm text-red-800">
                            <i class="fas fa-ambulance mr-2 w-4"></i>
                            <span>Ambulance Desa</span>
                        </div>
                        <button class="px-3 py-1 bg-red-200 text-red-800 rounded text-sm hover:bg-red-300">
                            0812-1111-2222
                        </button>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center text-sm text-red-800">
                            <i class="fas fa-fire-extinguisher mr-2 w-4"></i>
                            <span>Damkar Terdekat</span>
                        </div>
                        <button class="px-3 py-1 bg-red-200 text-red-800 rounded text-sm hover:bg-red-300">
                            113
                        </button>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center text-sm text-red-800">
                            <i class="fas fa-shield-alt mr-2 w-4"></i>
                            <span>Polsek Telagasari</span>
                        </div>
                        <button class="px-3 py-1 bg-red-200 text-red-800 rounded text-sm hover:bg-red-300">
                            (0267) 8431100
                        </button>
                    </div>
                </div>
            </div>

            <!-- FAQ -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="font-bold text-gray-900 mb-4">Pertanyaan Umum</h3>
                <div class="space-y-4">
                    <div class="border-b border-gray-200 pb-3">
                        <button class="faq-question flex items-center justify-between w-full text-left text-sm font-medium text-gray-900 hover:text-gray-700"
                                data-target="faq1">
                            <span>Bagaimana cara mengurus surat keterangan?</span>
                            <i class="fas fa-chevron-down transform transition-transform duration-200"></i>
                        </button>
                        <div id="faq1" class="faq-answer hidden mt-2 text-sm text-gray-600">
                            Surat keterangan dapat diurus di kantor desa pada jam kerja. Bawa KTP, KK, dan dokumen pendukung lainnya. 
                            Atau gunakan layanan online melalui menu "Layanan Surat" di website ini.
                        </div>
                    </div>

                    <div class="border-b border-gray-200 pb-3">
                        <button class="faq-question flex items-center justify-between w-full text-left text-sm font-medium text-gray-900 hover:text-gray-700"
                                data-target="faq2">
                            <span>Kapan jadwal pasar desa?</span>
                            <i class="fas fa-chevron-down transform transition-transform duration-200"></i>
                        </button>
                        <div id="faq2" class="faq-answer hidden mt-2 text-sm text-gray-600">
                            Pasar desa beroperasi setiap hari Minggu pukul 06:00 - 12:00 WIB di lapangan desa. 
                            Tersedia berbagai produk segar dan makanan tradisional.
                        </div>
                    </div>

                    <div class="border-b border-gray-200 pb-3">
                        <button class="faq-question flex items-center justify-between w-full text-left text-sm font-medium text-gray-900 hover:text-gray-700"
                                data-target="faq3">
                            <span>Bagaimana cara mendaftar UMKM?</span>
                            <i class="fas fa-chevron-down transform transition-transform duration-200"></i>
                        </button>
                        <div id="faq3" class="faq-answer hidden mt-2 text-sm text-gray-600">
                            Kunjungi kantor desa atau hubungi bagian ekonomi desa. Siapkan dokumen identitas, 
                            deskripsi usaha, dan foto produk. Pendaftaran gratis dan mendapat pembinaan rutin.
                        </div>
                    </div>

                    <div class="pb-3">
                        <button class="faq-question flex items-center justify-between w-full text-left text-sm font-medium text-gray-900 hover:text-gray-700"
                                data-target="faq4">
                            <span>Adakah program bantuan untuk warga?</span>
                            <i class="fas fa-chevron-down transform transition-transform duration-200"></i>
                        </button>
                        <div id="faq4" class="faq-answer hidden mt-2 text-sm text-gray-600">
                            Ya, tersedia berbagai program seperti BLT Dana Desa, bantuan sembako, beasiswa, 
                            dan program kesehatan gratis. Informasi lengkap dapat dilihat di menu "Pengumuman".
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Map & Location -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="font-bold text-gray-900">Peta Lokasi</h3>
                <p class="text-sm text-gray-600">Kantor {{ $villageProfile->village_name ?? 'Desa Krandegan' }}</p>
            </div>
            <button class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 text-sm transition duration-200">
                <i class="fas fa-directions mr-2"></i>
                Petunjuk Arah
            </button>
        </div>

        <!-- Map Container -->
        <div class="relative">
            <!-- OpenStreetMap Container -->
            <div id="contact-map" class="w-full h-96 bg-gray-200 rounded-lg overflow-hidden relative">
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="text-center">
                        <i class="fas fa-spinner fa-spin text-2xl text-gray-400 mb-2"></i>
                        <p class="text-gray-500 text-sm">Memuat peta lokasi...</p>
                    </div>
                </div>
            </div>
            
            <!-- Map Controls -->
            <div class="mt-3 flex flex-wrap gap-2 justify-center sm:justify-start">
                <button onclick="centerContactMap()" class="px-3 py-1 bg-green-600 text-white text-xs rounded-lg hover:bg-green-700 transition-colors">
                    <i class="fas fa-home mr-1"></i> Kantor Desa
                </button>
                <button onclick="toggleContactSatellite()" class="px-3 py-1 bg-blue-600 text-white text-xs rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-satellite mr-1"></i> Satelit
                </button>
                <button onclick="fullscreenContactMap()" class="px-3 py-1 bg-purple-600 text-white text-xs rounded-lg hover:bg-purple-700 transition-colors">
                    <i class="fas fa-expand mr-1"></i> Layar Penuh
                </button>
                <button onclick="openGoogleMaps()" class="px-3 py-1 bg-red-600 text-white text-xs rounded-lg hover:bg-red-700 transition-colors">
                    <i class="fas fa-external-link-alt mr-1"></i> Google Maps
                </button>
            </div>
        </div>

        <!-- Location Details -->
        <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
            <div class="flex items-center">
                <i class="fas fa-car text-gray-500 mr-2"></i>
                <span class="text-gray-700">15 menit dari Stasiun Telagasari</span>
            </div>
            <div class="flex items-center">
                <i class="fas fa-bus text-gray-500 mr-2"></i>
                <span class="text-gray-700">Dilalui angkot jurusan Telagasari</span>
            </div>
            <div class="flex items-center">
                <i class="fas fa-parking text-gray-500 mr-2"></i>
                <span class="text-gray-700">Tersedia area parkir gratis</span>
            </div>
        </div>
    </div>

    <!-- Response Time & Statistics -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h3 class="font-bold text-gray-900 mb-4">Statistik Layanan Kontak</h3>
        
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
            <div class="text-center p-4 bg-blue-50 rounded-lg">
                <div class="text-2xl font-bold text-blue-600">< 24 Jam</div>
                <div class="text-sm text-gray-600">Respon Email</div>
            </div>
            <div class="text-center p-4 bg-green-50 rounded-lg">
                <div class="text-2xl font-bold text-green-600">< 1 Jam</div>
                <div class="text-sm text-gray-600">Respon WhatsApp</div>
            </div>
            <div class="text-center p-4 bg-yellow-50 rounded-lg">
                <div class="text-2xl font-bold text-yellow-600">98%</div>
                <div class="text-sm text-gray-600">Tingkat Kepuasan</div>
            </div>
            <div class="text-center p-4 bg-purple-50 rounded-lg">
                <div class="text-2xl font-bold text-purple-600">450+</div>
                <div class="text-sm text-gray-600">Pesan/Bulan</div>
            </div>
        </div>

        <div class="text-center">
            <p class="text-gray-600 mb-4">
                Kami berkomitmen memberikan pelayanan terbaik untuk seluruh warga dan pengunjung {{ $villageProfile->village_name ?? 'Desa Krandegan' }}
            </p>
            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <button class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition duration-200">
                    <i class="fab fa-whatsapp mr-2"></i>
                    Chat WhatsApp
                </button>
                <button class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200">
                    <i class="fas fa-phone mr-2"></i>
                    Call Center
                </button>
                <button class="px-6 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition duration-200">
                    <i class="fas fa-star mr-2"></i>
                    Beri Rating
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

<!-- Leaflet CSS and JS for OpenStreetMap -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" 
      integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" 
      crossorigin="" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" 
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" 
        crossorigin=""></script>

@section('scripts')
<script>
    // Contact form submission loading state
    const contactForm = document.querySelector('form[action*="contact"]');
    if (contactForm) {
        contactForm.addEventListener('submit', function() {
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            
            // Show loading state
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Mengirim...';
            submitBtn.disabled = true;
        });
    }

    // FAQ accordion functionality
    document.querySelectorAll('.faq-question').forEach(button => {
        button.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            const answer = document.getElementById(targetId);
            const icon = this.querySelector('i');
            
            // Toggle answer visibility
            answer.classList.toggle('hidden');
            
            // Rotate icon
            if (answer.classList.contains('hidden')) {
                icon.classList.remove('rotate-180');
            } else {
                icon.classList.add('rotate-180');
            }
        });
    });

    // Contact buttons (phone, WhatsApp, email)
    document.querySelectorAll('button[class*="bg-green-100"]').forEach(button => {
        button.addEventListener('click', function() {
            const type = this.textContent.trim();
            const contactInfo = this.closest('.flex').querySelector('span').textContent;
            
            if (type === 'Call') {
                window.open(`tel:${contactInfo}`, '_blank');
            } else if (type === 'WA') {
                const waNumber = contactInfo.replace(/\D/g, ''); // Remove non-digits
                window.open(`https://wa.me/${waNumber}`, '_blank');
            }
        });
    });

    // Email and social media buttons
    document.querySelectorAll('button[class*="bg-purple-100"], button[class*="bg-blue-100"], button[class*="bg-pink-100"]').forEach(button => {
        button.addEventListener('click', function() {
            const type = this.textContent.trim();
            
            if (type === 'Email') {
                window.open('mailto:desa.krandegan@gmail.com', '_blank');
            } else if (type === 'FB') {
                window.open('https://facebook.com/desakrandegan', '_blank');
            } else if (type === 'IG') {
                window.open('https://instagram.com/desakrandegan', '_blank');
            }
        });
    });

    // Emergency contact buttons
    document.querySelectorAll('button[class*="bg-red-200"]').forEach(button => {
        button.addEventListener('click', function() {
            const number = this.textContent.trim();
            window.open(`tel:${number}`, '_blank');
        });
    });

    // Contact Map Variables
    let contactMap = null;
    let isContactSatellite = false;
    let contactMarker = null;
    
    // Village coordinates from database or default
    const contactCoords = [
        @if($villageProfile && $villageProfile->latitude && $villageProfile->longitude)
            {{ $villageProfile->latitude }}, {{ $villageProfile->longitude }}
        @else
            -6.258346, 107.435520 // Default coordinates
        @endif
    ];
    
    function initContactMap() {
        // Check if map container exists
        if (!document.getElementById('contact-map')) return;
        
        try {
            // Initialize map
            contactMap = L.map('contact-map').setView(contactCoords, 16);
            
            // Add OpenStreetMap tile layer
            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
            }).addTo(contactMap);
            
            // Add village office marker
            contactMarker = L.marker(contactCoords).addTo(contactMap)
                .bindPopup('<b>Kantor {{ $villageProfile->village_name ?? "Desa Krandegan" }}</b><br>{{ $villageProfile->address ?? "Alamat kantor desa" }}<br><small>Kantor Pelayanan Desa</small>')
                .openPopup();
            
            console.log('Contact map initialized successfully');
            
        } catch (error) {
            console.error('Error initializing contact map:', error);
            document.getElementById('contact-map').innerHTML = 
                '<div class="flex items-center justify-center h-full"><div class="text-center"><i class="fas fa-exclamation-triangle text-2xl text-red-400 mb-2"></i><p class="text-red-500 text-sm">Gagal memuat peta</p></div></div>';
        }
    }
    
    function centerContactMap() {
        if (contactMap) {
            contactMap.setView(contactCoords, 16);
            if (contactMarker) {
                contactMarker.openPopup();
            }
        }
    }
    
    function toggleContactSatellite() {
        if (!contactMap) return;
        
        // Remove all tile layers
        contactMap.eachLayer(function(layer) {
            if (layer instanceof L.TileLayer) {
                contactMap.removeLayer(layer);
            }
        });
        
        if (!isContactSatellite) {
            // Switch to satellite view
            L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                maxZoom: 19,
                attribution: '© Esri, Maxar, Earthstar Geographics'
            }).addTo(contactMap);
            isContactSatellite = true;
        } else {
            // Switch back to street view
            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
            }).addTo(contactMap);
            isContactSatellite = false;
        }
    }
    
    function fullscreenContactMap() {
        const mapContainer = document.getElementById('contact-map');
        if (mapContainer) {
            if (mapContainer.requestFullscreen) {
                mapContainer.requestFullscreen();
            } else if (mapContainer.webkitRequestFullscreen) {
                mapContainer.webkitRequestFullscreen();
            } else if (mapContainer.msRequestFullscreen) {
                mapContainer.msRequestFullscreen();
            }
            
            // Handle fullscreen change
            document.addEventListener('fullscreenchange', function() {
                if (contactMap) {
                    setTimeout(() => {
                        contactMap.invalidateSize();
                    }, 100);
                }
            });
        }
    }
    
    // Function to open Google Maps
    function openGoogleMaps() {
        @if($villageProfile && ($villageProfile->latitude && $villageProfile->longitude))
            window.open('https://maps.google.com/?q={{ $villageProfile->latitude }},{{ $villageProfile->longitude }}', '_blank');
        @elseif($villageProfile && $villageProfile->address)
            window.open('https://maps.google.com/?q={{ urlencode($villageProfile->address) }}', '_blank');
        @else
            window.open('https://maps.google.com/?q={{ urlencode($villageProfile->village_name ?? 'Desa Krandegan') }}', '_blank');
        @endif
    }

    // Map and directions
    document.querySelector('button[class*="fas fa-directions"]').addEventListener('click', function() {
        openGoogleMaps();
    });

    // Quick action buttons  
    const whatsappBtn = document.querySelector('button[class*="bg-green-600"]');
    if (whatsappBtn && whatsappBtn.textContent.includes('Chat WhatsApp')) {
        whatsappBtn.addEventListener('click', function() {
            @if($villageProfile && $villageProfile->whatsapp)
                const waNumber = '{{ str_replace(["+", "-", " "], "", $villageProfile->whatsapp) }}';
                window.open(`https://wa.me/${waNumber}`, '_blank');
            @else
                alert('Nomor WhatsApp belum tersedia');
            @endif
        });
    }

    const callBtn = document.querySelector('button[class*="bg-blue-600"]');
    if (callBtn && callBtn.textContent.includes('Call Center')) {
        callBtn.addEventListener('click', function() {
            @if($villageProfile && $villageProfile->phone)
                window.open('tel:{{ $villageProfile->phone }}', '_blank');
            @else
                alert('Nomor telepon belum tersedia');
            @endif
        });
    }

    document.querySelector('button[class*="bg-gray-600"]:contains("Beri Rating")').addEventListener('click', function() {
        // In a real application, this would open a rating form
        alert('Membuka formulir rating pelayanan...');
    });

    // Initialize contact map when page loads
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize map after a short delay to ensure DOM is ready
        setTimeout(initContactMap, 500);
    });
    
    // Initialize map on window load as fallback
    window.addEventListener('load', function() {
        if (!contactMap) {
            initContactMap();
        }
    });

    // Form validation enhancements
    const form = document.getElementById('contactForm');
    const inputs = form.querySelectorAll('input, select, textarea');
    
    inputs.forEach(input => {
        input.addEventListener('blur', function() {
            if (this.hasAttribute('required') && !this.value.trim()) {
                this.classList.add('border-red-300', 'focus:border-red-500', 'focus:ring-red-500');
                this.classList.remove('border-gray-300', 'focus:border-gray-500', 'focus:ring-gray-500');
            } else {
                this.classList.remove('border-red-300', 'focus:border-red-500', 'focus:ring-red-500');
                this.classList.add('border-gray-300', 'focus:border-gray-500', 'focus:ring-gray-500');
            }
        });
    });
</script>
@endsection