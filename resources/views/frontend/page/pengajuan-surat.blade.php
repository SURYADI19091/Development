@extends('frontend.main')

@section('title', 'Pengajuan Surat - ' . strtoupper($villageProfile->village_name ?? 'Desa Krandegan'))
@section('page_title', 'PENGAJUAN SURAT')
@section('header_icon', 'fas fa-edit')
@section('header_bg_color', 'bg-emerald-600')

@section('content')
<div class="xl:col-span-3">
    <!-- Form Header -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <div class="text-center">
            <h1 class="text-2xl font-bold text-gray-900 mb-2">Form Pengajuan Surat</h1>
            <p class="text-gray-600 mb-4">Silakan lengkapi formulir di bawah ini untuk mengajukan surat yang diperlukan</p>
            <div class="inline-flex items-center px-4 py-2 bg-green-100 text-green-800 rounded-full text-sm">
                <i class="fas fa-info-circle mr-2"></i>
                Pastikan semua data yang diisi adalah benar dan sesuai dokumen resmi
            </div>
        </div>
    </div>

    <!-- Main Form -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <form id="letterForm" class="space-y-6">
            @csrf
            
            <!-- Jenis Surat -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-file-alt text-blue-600 mr-1"></i>
                    Jenis Surat yang Diajukan *
                </label>
                <select id="letterType" name="letter_type" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" required>
                    <option value="">-- Pilih Jenis Surat --</option>
                    <option value="domisili">Surat Keterangan Domisili</option>
                    <option value="usaha">Surat Keterangan Usaha</option>
                    <option value="tidak_mampu">Surat Keterangan Tidak Mampu</option>
                    <option value="penghasilan">Surat Keterangan Penghasilan</option>
                    <option value="pengantar_ktp">Surat Pengantar KTP</option>
                    <option value="pengantar_kk">Surat Pengantar Kartu Keluarga</option>
                    <option value="pengantar_akta">Surat Pengantar Akta Kelahiran</option>
                    <option value="pengantar_nikah">Surat Pengantar Nikah</option>
                    <option value="lainnya">Lainnya</option>
                </select>
            </div>

            <!-- Custom Letter Type (shown when "Lainnya" selected) -->
            <div id="customLetterType" class="hidden">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Sebutkan Jenis Surat *
                </label>
                <input type="text" name="custom_letter_type" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" placeholder="Tuliskan jenis surat yang diperlukan">
            </div>

            <!-- Data Pemohon -->
            <div class="border-t pt-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-user text-emerald-600 mr-2"></i>
                    Data Pemohon
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap *</label>
                        <input type="text" name="full_name" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" placeholder="Masukkan nama lengkap" required>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">NIK *</label>
                        <input type="text" name="nik" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" placeholder="16 digit NIK" maxlength="16" required>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tempat Lahir *</label>
                        <input type="text" name="birth_place" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" placeholder="Kota/Kabupaten" required>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Lahir *</label>
                        <input type="date" name="birth_date" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" required>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Kelamin *</label>
                        <select name="gender" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" required>
                            <option value="">-- Pilih --</option>
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Agama *</label>
                        <select name="religion" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" required>
                            <option value="">-- Pilih --</option>
                            <option value="Islam">Islam</option>
                            <option value="Kristen">Kristen</option>
                            <option value="Katolik">Katolik</option>
                            <option value="Hindu">Hindu</option>
                            <option value="Buddha">Buddha</option>
                            <option value="Konghucu">Konghucu</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status Perkawinan *</label>
                        <select name="marital_status" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" required>
                            <option value="">-- Pilih --</option>
                            <option value="Belum Kawin">Belum Kawin</option>
                            <option value="Kawin">Kawin</option>
                            <option value="Cerai Hidup">Cerai Hidup</option>
                            <option value="Cerai Mati">Cerai Mati</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pekerjaan *</label>
                        <input type="text" name="occupation" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" placeholder="Contoh: Petani, Wiraswasta" required>
                    </div>
                </div>
            </div>

            <!-- Alamat -->
            <div class="border-t pt-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-map-marker-alt text-emerald-600 mr-2"></i>
                    Alamat Lengkap
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Alamat *</label>
                        <textarea name="address" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" placeholder="Jalan, Gang, Nomor Rumah" required></textarea>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">RT *</label>
                        <select name="rt" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" required>
                            <option value="">-- Pilih RT --</option>
                            @for($i = 1; $i <= 12; $i++)
                                <option value="{{ sprintf('%02d', $i) }}">{{ sprintf('%02d', $i) }}</option>
                            @endfor
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">RW *</label>
                        <select name="rw" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" required>
                            <option value="">-- Pilih RW --</option>
                            @for($i = 1; $i <= 4; $i++)
                                <option value="{{ sprintf('%02d', $i) }}">{{ sprintf('%02d', $i) }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
            </div>

            <!-- Kontak -->
            <div class="border-t pt-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-phone text-emerald-600 mr-2"></i>
                    Informasi Kontak
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon/HP</label>
                        <input type="tel" name="phone" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" placeholder="08xxxxxxxxxx">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" name="email" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" placeholder="nama@email.com">
                    </div>
                </div>
            </div>

            <!-- Keperluan -->
            <div class="border-t pt-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-info-circle text-emerald-600 mr-2"></i>
                    Keperluan & Keterangan
                </h3>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Keperluan Surat *</label>
                    <textarea name="purpose" rows="4" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" placeholder="Jelaskan untuk keperluan apa surat ini digunakan" required></textarea>
                </div>
            </div>

            <!-- Upload Dokumen -->
            <div class="border-t pt-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-upload text-emerald-600 mr-2"></i>
                    Dokumen Pendukung
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Foto KTP *</label>
                        <input type="file" name="ktp_file" accept="image/*,.pdf" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" required>
                        <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG, PDF (Max: 2MB)</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Foto Kartu Keluarga *</label>
                        <input type="file" name="kk_file" accept="image/*,.pdf" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" required>
                        <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG, PDF (Max: 2MB)</p>
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Dokumen Lainnya (Opsional)</label>
                        <input type="file" name="other_files[]" accept="image/*,.pdf" multiple class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        <p class="text-xs text-gray-500 mt-1">Dokumen pendukung lainnya jika ada</p>
                    </div>
                </div>
            </div>

            <!-- Terms Agreement -->
            <div class="border-t pt-6">
                <div class="flex items-start space-x-3">
                    <input type="checkbox" id="terms" name="terms" class="mt-1 h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded" required>
                    <label for="terms" class="text-sm text-gray-700">
                        Saya menyatakan bahwa data yang saya isikan adalah benar dan dapat dipertanggungjawabkan. 
                        Apabila dikemudian hari ditemukan data yang tidak benar, saya siap mempertanggungjawabkannya 
                        sesuai dengan ketentuan hukum yang berlaku.
                    </label>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="border-t pt-6">
                <div class="flex flex-col sm:flex-row gap-4">
                    <button type="submit" class="flex-1 bg-emerald-600 text-white px-6 py-3 rounded-lg hover:bg-emerald-700 focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition duration-200 flex items-center justify-center">
                        <i class="fas fa-paper-plane mr-2"></i>
                        Kirim Pengajuan
                    </button>
                    <button type="reset" class="flex-1 bg-gray-600 text-white px-6 py-3 rounded-lg hover:bg-gray-700 focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition duration-200 flex items-center justify-center">
                        <i class="fas fa-undo mr-2"></i>
                        Reset Form
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Information Panel -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
            <i class="fas fa-exclamation-triangle text-yellow-600 mr-2"></i>
            Informasi Penting
        </h2>
        
        <div class="space-y-4">
            <div class="flex items-start space-x-3 p-3 bg-blue-50 rounded-lg">
                <i class="fas fa-clock text-blue-600 mt-0.5"></i>
                <div>
                    <p class="font-medium text-blue-900">Waktu Proses</p>
                    <p class="text-sm text-blue-700">Pengajuan akan diproses dalam 1-3 hari kerja setelah berkas lengkap diterima.</p>
                </div>
            </div>
            
            <div class="flex items-start space-x-3 p-3 bg-green-50 rounded-lg">
                <i class="fas fa-bell text-green-600 mt-0.5"></i>
                <div>
                    <p class="font-medium text-green-900">Notifikasi</p>
                    <p class="text-sm text-green-700">Anda akan dihubungi via telepon/WA ketika surat sudah selesai dan siap diambil.</p>
                </div>
            </div>
            
            <div class="flex items-start space-x-3 p-3 bg-yellow-50 rounded-lg">
                <i class="fas fa-money-bill text-yellow-600 mt-0.5"></i>
                <div>
                    <p class="font-medium text-yellow-900">Biaya</p>
                    <p class="text-sm text-yellow-700">Sebagian besar layanan GRATIS. Biaya legalisir Rp 2.000/lembar.</p>
                </div>
            </div>
            
            <div class="flex items-start space-x-3 p-3 bg-red-50 rounded-lg">
                <i class="fas fa-map-marker-alt text-red-600 mt-0.5"></i>
                <div>
                    <p class="font-medium text-red-900">Pengambilan</p>
                    <p class="text-sm text-red-700">Surat dapat diambil di Kantor Desa pada jam kerja dengan membawa KTP asli.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Show/hide custom letter type field
    document.getElementById('letterType').addEventListener('change', function() {
        const customField = document.getElementById('customLetterType');
        if (this.value === 'lainnya') {
            customField.classList.remove('hidden');
            customField.querySelector('input').required = true;
        } else {
            customField.classList.add('hidden');
            customField.querySelector('input').required = false;
        }
    });

    // Form submission
    document.getElementById('letterForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Show loading state
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Mengirim...';
        submitBtn.disabled = true;
        
        // Simulate form submission
        setTimeout(() => {
            alert('Pengajuan berhasil dikirim! Anda akan dihubungi dalam 1-3 hari kerja.');
            
            // Reset button
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
            
            // Reset form
            this.reset();
            document.getElementById('customLetterType').classList.add('hidden');
        }, 2000);
    });

    // NIK validation (16 digits only)
    document.querySelector('input[name="nik"]').addEventListener('input', function() {
        this.value = this.value.replace(/\D/g, '');
        if (this.value.length > 16) {
            this.value = this.value.slice(0, 16);
        }
    });

    // Phone number validation
    document.querySelector('input[name="phone"]').addEventListener('input', function() {
        this.value = this.value.replace(/\D/g, '');
        if (this.value.length > 15) {
            this.value = this.value.slice(0, 15);
        }
    });
</script>
@endsection