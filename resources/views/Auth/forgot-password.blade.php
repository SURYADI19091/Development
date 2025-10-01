<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Lupa Password - Sistem Informasi Desa Ciuwlan</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Custom Styles -->
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        }
        
        .forgot-card {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .input-field {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(10px);
        }
        
        .input-field:focus {
            background: rgba(255, 255, 255, 0.2);
            border-color: rgba(255, 255, 255, 0.5);
        }
        
        .btn-reset {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            transition: all 0.3s ease;
        }
        
        .btn-reset:hover {
            background: linear-gradient(135deg, #d97706 0%, #b45309 100%);
            transform: translateY(-2px);
        }
        
        .floating-shapes {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: -1;
        }
        
        .shape {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            animation: float 6s ease-in-out infinite;
        }
        
        .shape:nth-child(1) {
            width: 80px;
            height: 80px;
            top: 10%;
            left: 10%;
            animation-delay: 0s;
        }
        
        .shape:nth-child(2) {
            width: 120px;
            height: 120px;
            top: 20%;
            right: 10%;
            animation-delay: 2s;
        }
        
        .shape:nth-child(3) {
            width: 60px;
            height: 60px;
            bottom: 10%;
            left: 15%;
            animation-delay: 4s;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            33% { transform: translateY(-20px) rotate(120deg); }
            66% { transform: translateY(20px) rotate(240deg); }
        }
        
        .logo-pulse {
            animation: pulse 2s ease-in-out infinite;
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4 relative">
    
    <!-- Floating Background Shapes -->
    <div class="floating-shapes">
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
    </div>
    
    <!-- Forgot Password Container -->
    <div class="w-full max-w-md">
        <!-- Logo & Header -->
        <div class="text-center mb-8">
            <div class="logo-pulse inline-flex items-center justify-center w-20 h-20 bg-white rounded-full shadow-lg mb-4">
                <i class="fas fa-key text-orange-600 text-2xl"></i>
            </div>
            <h1 class="text-3xl font-bold text-white mb-2">Lupa Password?</h1>
            <p class="text-orange-100">Jangan khawatir, kami akan mengirimkan link reset</p>
            <p class="text-orange-200 text-sm">ke email Anda</p>
        </div>
        
        <!-- Forgot Password Card -->
        <div class="forgot-card rounded-2xl p-8 shadow-2xl">
            <!-- Alert Messages -->
            @if(session('status'))
                <div class="bg-green-500 bg-opacity-20 border border-green-400 text-green-100 px-4 py-3 rounded-lg mb-6 flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    <span>{{ session('status') }}</span>
                </div>
            @endif
            
            @if(session('error'))
                <div class="bg-red-500 bg-opacity-20 border border-red-400 text-red-100 px-4 py-3 rounded-lg mb-6 flex items-center">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif
            
            @if($errors->any())
                <div class="bg-red-500 bg-opacity-20 border border-red-400 text-red-100 px-4 py-3 rounded-lg mb-6">
                    <div class="flex items-center mb-2">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        <span class="font-medium">Terdapat kesalahan:</span>
                    </div>
                    <ul class="list-disc list-inside text-sm">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <!-- Info Text -->
            <div class="bg-orange-500 bg-opacity-20 border border-orange-400 text-orange-100 px-4 py-3 rounded-lg mb-6 flex items-start">
                <i class="fas fa-info-circle mr-2 mt-0.5"></i>
                <div class="text-sm">
                    <p class="font-medium mb-1">Cara reset password:</p>
                    <ol class="list-decimal list-inside text-xs space-y-1">
                        <li>Masukkan email yang terdaftar</li>
                        <li>Cek email untuk link reset</li>
                        <li>Klik link dan buat password baru</li>
                        <li>Login dengan password baru</li>
                    </ol>
                </div>
            </div>
            
            <!-- Reset Form -->
            <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                @csrf
                
                <!-- Email Field -->
                <div class="space-y-2">
                    <label for="email" class="block text-white font-medium">
                        <i class="fas fa-envelope mr-2"></i>Email Terdaftar
                    </label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        value="{{ old('email') }}"
                        class="input-field w-full px-4 py-3 rounded-lg text-white placeholder-orange-200 focus:outline-none focus:ring-2 focus:ring-orange-400 transition-all"
                        placeholder="contoh@email.com"
                        required
                        autocomplete="email"
                        autofocus
                    >
                    @error('email')
                        <p class="text-red-300 text-sm mt-1">
                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                        </p>
                    @enderror
                    <p class="text-orange-200 text-xs">
                        Pastikan email yang dimasukkan benar dan masih aktif
                    </p>
                </div>
                
                <!-- Reset Button -->
                <button 
                    type="submit" 
                    class="btn-reset w-full py-3 px-4 rounded-lg text-white font-semibold shadow-lg hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 focus:ring-offset-transparent"
                    id="reset-btn"
                >
                    <i class="fas fa-paper-plane mr-2"></i>
                    Kirim Link Reset
                </button>
                
                <!-- Divider -->
                <div class="relative my-6">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-orange-300"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-transparent text-orange-200">atau</span>
                    </div>
                </div>
                
                <!-- Action Links -->
                <div class="grid grid-cols-2 gap-4 text-center">
                    <a href="{{ route('login') }}" class="text-orange-200 hover:text-white transition-colors text-sm">
                        <i class="fas fa-arrow-left mr-1"></i>
                        Kembali Login
                    </a>
                    <a href="{{ route('register') }}" class="text-orange-200 hover:text-white transition-colors text-sm">
                        <i class="fas fa-user-plus mr-1"></i>
                        Daftar Akun
                    </a>
                </div>
            </form>
        </div>
        
        <!-- Help Section -->
        <div class="mt-6 p-4 bg-white bg-opacity-10 rounded-lg backdrop-filter backdrop-blur-sm">
            <h3 class="text-white font-semibold mb-2 flex items-center">
                <i class="fas fa-question-circle mr-2"></i>
                Butuh Bantuan?
            </h3>
            <div class="text-orange-200 text-sm space-y-1">
                <p>• Pastikan email yang digunakan benar dan masih aktif</p>
                <p>• Periksa folder spam/junk jika tidak menerima email</p>
                <p>• Link reset berlaku selama 60 menit</p>
                <p>• Hubungi admin jika masih bermasalah</p>
            </div>
            <div class="mt-3 pt-3 border-t border-orange-300">
                <a href="{{ route('contact') }}" class="text-white hover:text-orange-100 text-sm font-medium">
                    <i class="fas fa-headset mr-1"></i>
                    Hubungi Support
                </a>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="text-center mt-6">
            <p class="text-orange-200 text-sm">
                © {{ date('Y') }} Desa Ciuwlan. Semua hak dilindungi.
            </p>
        </div>
    </div>
    
    <!-- JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const emailInput = document.getElementById('email');
            const resetBtn = document.getElementById('reset-btn');
            
            // Email validation
            emailInput.addEventListener('input', function() {
                const email = this.value;
                if (email.length > 0) {
                    if (validateEmail(email)) {
                        this.classList.remove('border-red-400');
                        this.classList.add('border-green-400');
                    } else {
                        this.classList.remove('border-green-400');
                        this.classList.add('border-red-400');
                    }
                } else {
                    this.classList.remove('border-red-400', 'border-green-400');
                }
            });
            
            // Form submission
            document.querySelector('form').addEventListener('submit', function(e) {
                const email = emailInput.value;
                
                if (!validateEmail(email)) {
                    e.preventDefault();
                    alert('Mohon masukkan email yang valid!');
                    return;
                }
                
                resetBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Mengirim...';
                resetBtn.disabled = true;
                
                // Re-enable button after 30 seconds
                setTimeout(() => {
                    resetBtn.innerHTML = '<i class="fas fa-paper-plane mr-2"></i>Kirim Link Reset';
                    resetBtn.disabled = false;
                }, 30000);
            });
            
            // Auto-hide alerts
            const alerts = document.querySelectorAll('.bg-red-500, .bg-green-500');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.transition = 'opacity 0.5s ease-out';
                    alert.style.opacity = '0';
                    setTimeout(() => {
                        alert.remove();
                    }, 500);
                }, 10000); // Hide after 10 seconds for forgot password
            });
        });
        
        function validateEmail(email) {
            const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return re.test(email);
        }
    </script>
</body>
</html>