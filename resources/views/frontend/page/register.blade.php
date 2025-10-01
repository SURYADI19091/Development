@extends('frontend.main')

@section('title', 'Daftar Akun - Sistem Informasi Desa')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-green-50 to-emerald-100 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-lg mx-auto">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="mx-auto h-20 w-20 bg-green-600 rounded-full flex items-center justify-center mb-4">
                <svg class="h-10 w-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                </svg>
            </div>
            <h2 class="text-3xl font-bold text-gray-900 mb-2">
                Daftar Akun Baru
            </h2>
            <p class="text-gray-600">
                Buat akun untuk mengakses layanan desa
            </p>
        </div>

        <!-- Register Form -->
        <div class="bg-white py-8 px-6 shadow-xl rounded-lg sm:px-10">
            <form class="space-y-6" action="{{ route('register.submit') }}" method="POST" id="registerForm">
                @csrf
                
                <!-- Full Name -->
                <div>
                    <label for="full_name" class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Lengkap *
                    </label>
                    <input 
                        id="full_name" 
                        name="full_name" 
                        type="text" 
                        required 
                        class="block w-full px-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 sm:text-sm"
                        placeholder="Masukkan nama lengkap"
                        value="{{ old('full_name') }}"
                    >
                    @error('full_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- NIK -->
                <div>
                    <label for="nik" class="block text-sm font-medium text-gray-700 mb-2">
                        NIK (Nomor Induk Kependudukan) *
                    </label>
                    <input 
                        id="nik" 
                        name="nik" 
                        type="text" 
                        required 
                        maxlength="16"
                        pattern="[0-9]{16}"
                        class="block w-full px-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 sm:text-sm"
                        placeholder="16 digit NIK"
                        value="{{ old('nik') }}"
                    >
                    <p class="mt-1 text-xs text-gray-500">NIK harus terdiri dari 16 digit angka</p>
                    @error('nik')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        Email *
                    </label>
                    <input 
                        id="email" 
                        name="email" 
                        type="email" 
                        required 
                        class="block w-full px-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 sm:text-sm"
                        placeholder="contoh@email.com"
                        value="{{ old('email') }}"
                    >
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Phone -->
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                        Nomor Telepon *
                    </label>
                    <input 
                        id="phone" 
                        name="phone" 
                        type="tel" 
                        required 
                        class="block w-full px-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 sm:text-sm"
                        placeholder="08XXXXXXXXXX"
                        value="{{ old('phone') }}"
                    >
                    @error('phone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Address -->
                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                        Alamat *
                    </label>
                    <textarea 
                        id="address" 
                        name="address" 
                        required 
                        rows="3"
                        class="block w-full px-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 sm:text-sm"
                        placeholder="Masukkan alamat lengkap"
                    >{{ old('address') }}</textarea>
                    @error('address')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        Password *
                    </label>
                    <div class="relative">
                        <input 
                            id="password" 
                            name="password" 
                            type="password" 
                            required 
                            minlength="8"
                            class="block w-full px-3 py-3 pr-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 sm:text-sm"
                            placeholder="Minimal 8 karakter"
                        >
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <button 
                                type="button" 
                                onclick="togglePasswordVisibility('password')"
                                class="text-gray-400 hover:text-gray-600 focus:outline-none"
                            >
                                <svg id="passwordEye" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <p class="mt-1 text-xs text-gray-500">Password harus minimal 8 karakter</p>
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                        Konfirmasi Password *
                    </label>
                    <div class="relative">
                        <input 
                            id="password_confirmation" 
                            name="password_confirmation" 
                            type="password" 
                            required 
                            class="block w-full px-3 py-3 pr-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 sm:text-sm"
                            placeholder="Ulangi password"
                        >
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <button 
                                type="button" 
                                onclick="togglePasswordVisibility('password_confirmation')"
                                class="text-gray-400 hover:text-gray-600 focus:outline-none"
                            >
                                <svg id="confirmPasswordEye" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    @error('password_confirmation')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Terms and Conditions -->
                <div class="flex items-start">
                    <input 
                        id="terms" 
                        name="terms" 
                        type="checkbox" 
                        required
                        class="h-4 w-4 mt-1 text-green-600 focus:ring-green-500 border-gray-300 rounded"
                    >
                    <label for="terms" class="ml-2 block text-sm text-gray-700">
                        Saya setuju dengan 
                        <a href="#" class="font-medium text-green-600 hover:text-green-500">Syarat dan Ketentuan</a> 
                        serta 
                        <a href="#" class="font-medium text-green-600 hover:text-green-500">Kebijakan Privasi</a>
                    </label>
                </div>
                @error('terms')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror

                <!-- Submit Button -->
                <div>
                    <button 
                        type="submit" 
                        class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-150 ease-in-out"
                    >
                        <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                            <svg class="h-5 w-5 text-green-500 group-hover:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                            </svg>
                        </span>
                        Daftar Akun
                    </button>
                </div>
            </form>

            <!-- Login Link -->
            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600">
                    Sudah punya akun?
                    <a href="{{ route('login') }}" class="font-medium text-green-600 hover:text-green-500">
                        Masuk di sini
                    </a>
                </p>
            </div>

            <!-- Info Box -->
            <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                <div class="flex">
                    <svg class="h-5 w-5 text-blue-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-900">Informasi Penting</h3>
                        <p class="mt-1 text-sm text-blue-700">
                            NIK yang didaftarkan harus valid dan terdaftar sebagai warga desa. 
                            Akun akan diverifikasi oleh perangkat desa sebelum dapat digunakan.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center mt-8">
            <p class="text-xs text-gray-500">
                © 2024 Sistem Informasi Desa. Data pribadi Anda dilindungi.
            </p>
        </div>
    </div>
</div>

<!-- Loading Overlay -->
<div id="loadingOverlay" class="fixed inset-0 bg-gray-900 bg-opacity-50 z-50 items-center justify-center" style="display: none;">
    <div class="bg-white p-6 rounded-lg shadow-xl">
        <div class="flex items-center">
            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span class="text-gray-700">Sedang mendaftarkan akun...</span>
        </div>
    </div>
</div>

<script>
// Toggle password visibility
function togglePasswordVisibility(fieldId) {
    const passwordInput = document.getElementById(fieldId);
    const eyeIcon = document.getElementById(fieldId + (fieldId === 'password' ? 'Eye' : 'Eye'));
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        eyeIcon.innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
        `;
    } else {
        passwordInput.type = 'password';
        eyeIcon.innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
        `;
    }
}

// NIK validation
document.getElementById('nik').addEventListener('input', function(e) {
    // Remove non-digits
    e.target.value = e.target.value.replace(/\D/g, '');
    
    // Limit to 16 digits
    if (e.target.value.length > 16) {
        e.target.value = e.target.value.slice(0, 16);
    }
});

// Phone validation
document.getElementById('phone').addEventListener('input', function(e) {
    // Remove non-digits and non-plus
    e.target.value = e.target.value.replace(/[^\d+]/g, '');
});

// Password confirmation validation
document.getElementById('password_confirmation').addEventListener('input', function() {
    const password = document.getElementById('password').value;
    const confirmation = this.value;
    
    if (confirmation && password !== confirmation) {
        this.setCustomValidity('Password tidak cocok');
    } else {
        this.setCustomValidity('');
    }
});

// Form submission
document.getElementById('registerForm').addEventListener('submit', function(e) {
    const password = document.getElementById('password').value;
    const confirmation = document.getElementById('password_confirmation').value;
    
    if (password !== confirmation) {
        e.preventDefault();
        alert('Password dan konfirmasi password tidak cocok!');
        return;
    }
    
    const loadingOverlay = document.getElementById('loadingOverlay');
    loadingOverlay.style.display = 'flex';
});

// Auto-focus first field
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('full_name').focus();
});
</script>
@endsection