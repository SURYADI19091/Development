@extends('frontend.main')

@section('title', 'Lupa Password - Sistem Informasi Desa')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-orange-50 to-yellow-100 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md mx-auto">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="mx-auto h-20 w-20 bg-orange-600 rounded-full flex items-center justify-center mb-4">
                <svg class="h-10 w-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
            </div>
            <h2 class="text-3xl font-bold text-gray-900 mb-2">
                Lupa Password
            </h2>
            <p class="text-gray-600">
                Masukkan email Anda untuk reset password
            </p>
        </div>

        <!-- Reset Form -->
        <div class="bg-white py-8 px-6 shadow-xl rounded-lg sm:px-10">
            <div id="resetForm">
                <form class="space-y-6" action="{{ route('password.email') }}" method="POST">
                    @csrf
                    
                    <!-- Email Field -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                                </svg>
                            </div>
                            <input 
                                id="email" 
                                name="email" 
                                type="email" 
                                required 
                                class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 sm:text-sm"
                                placeholder="Masukkan email Anda"
                                value="{{ old('email') }}"
                            >
                        </div>
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-2 text-xs text-gray-500">
                            Kami akan mengirimkan link reset password ke email Anda
                        </p>
                    </div>

                    <!-- Submit Button -->
                    <div>
                        <button 
                            type="submit" 
                            class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition duration-150 ease-in-out"
                        >
                            <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                                <svg class="h-5 w-5 text-orange-500 group-hover:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                </svg>
                            </span>
                            Kirim Link Reset
                        </button>
                    </div>
                </form>
            </div>

            <!-- Success Message (Hidden by default) -->
            <div id="successMessage" class="hidden text-center">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-4">
                    <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Email Terkirim!</h3>
                <p class="text-gray-600 mb-6">
                    Kami telah mengirimkan link reset password ke email Anda. 
                    Silakan cek email Anda dan ikuti petunjuk yang diberikan.
                </p>
                <div class="space-y-3">
                    <button 
                        onclick="showResetForm()" 
                        class="w-full text-sm text-orange-600 hover:text-orange-500 font-medium"
                    >
                        Kirim ulang email
                    </button>
                </div>
            </div>

            <!-- Divider -->
            <div class="mt-6">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-white text-gray-500">Atau</span>
                    </div>
                </div>
            </div>

            <!-- Back to Login -->
            <div class="mt-6 text-center">
                <a href="{{ route('login') }}" class="flex items-center justify-center text-sm font-medium text-orange-600 hover:text-orange-500">
                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali ke Login
                </a>
            </div>

            <!-- Alternative Contact -->
            <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                <h3 class="text-sm font-medium text-gray-900 mb-2">Tidak Bisa Reset Password?</h3>
                <p class="text-xs text-gray-600 mb-3">
                    Jika Anda mengalami kesulitan, silakan hubungi perangkat desa:
                </p>
                <div class="space-y-2 text-xs">
                    <div class="flex items-center text-gray-600">
                        <svg class="h-3 w-3 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                        Telepon: (0xxx) xxxx-xxxx
                    </div>
                    <div class="flex items-center text-gray-600">
                        <svg class="h-3 w-3 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                        </svg>
                        Email: admin@desa.go.id
                    </div>
                    <div class="flex items-start text-gray-600">
                        <svg class="h-3 w-3 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        Kantor Desa: Jl. xxx No. xx
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center mt-8">
            <p class="text-xs text-gray-500">
                © 2024 Sistem Informasi Desa. Keamanan data adalah prioritas kami.
            </p>
        </div>
    </div>
</div>

<!-- Loading Overlay -->
<div id="loadingOverlay" class="fixed inset-0 bg-gray-900 bg-opacity-50 z-50 items-center justify-center" style="display: none;">
    <div class="bg-white p-6 rounded-lg shadow-xl">
        <div class="flex items-center">
            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-orange-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span class="text-gray-700">Mengirim email reset password...</span>
        </div>
    </div>
</div>

<script>
// Show success message
function showSuccessMessage() {
    document.getElementById('resetForm').classList.add('hidden');
    document.getElementById('successMessage').classList.remove('hidden');
}

// Show reset form
function showResetForm() {
    document.getElementById('successMessage').classList.add('hidden');
    document.getElementById('resetForm').classList.remove('hidden');
    document.getElementById('email').focus();
}

// Handle form submission
document.querySelector('form').addEventListener('submit', function(e) {
    e.preventDefault(); // For demo purposes
    
    const loadingOverlay = document.getElementById('loadingOverlay');
    loadingOverlay.style.display = 'flex';
    
    // Simulate email sending
    setTimeout(() => {
        loadingOverlay.style.display = 'none';
        showSuccessMessage();
    }, 2000);
    
    // In real implementation, remove e.preventDefault() and this setTimeout
});

// Auto-focus email field
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('email').focus();
});

// Email validation feedback
document.getElementById('email').addEventListener('input', function(e) {
    const email = e.target.value;
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    
    if (email && !emailRegex.test(email)) {
        e.target.setCustomValidity('Format email tidak valid');
    } else {
        e.target.setCustomValidity('');
    }
});
</script>
@endsection