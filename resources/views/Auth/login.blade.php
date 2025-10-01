<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - Sistem Informasi Desa Ciuwlan</title>
    
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .login-card {
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
        
        .btn-login {
            background: linear-gradient(135deg, #3B82F6 0%, #1E40AF 100%);
            transition: all 0.3s ease;
        }
        
        .btn-login:hover {
            background: linear-gradient(135deg, #2563EB 0%, #1E3A8A 100%);
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
        
        .shape:nth-child(4) {
            width: 90px;
            height: 90px;
            bottom: 20%;
            right: 15%;
            animation-delay: 1s;
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
        <div class="shape"></div>
    </div>
    
    <!-- Login Container -->
    <div class="w-full max-w-md">
        <!-- Logo & Header -->
        <div class="text-center mb-8">
            <div class="logo-pulse inline-flex items-center justify-center w-20 h-20 bg-white rounded-full shadow-lg mb-4">
                <i class="fas fa-landmark text-blue-600 text-2xl"></i>
            </div>
            <h1 class="text-3xl font-bold text-white mb-2">Selamat Datang</h1>
            <p class="text-blue-100">Sistem Informasi Desa Ciuwlan</p>
            <p class="text-blue-200 text-sm">Kecamatan Telagsari, Kabupaten Banyumas</p>
        </div>
        
        <!-- Login Card -->
        <div class="login-card rounded-2xl p-8 shadow-2xl">
            <!-- Alert Messages -->
            @if(session('error'))
                <div class="bg-red-500 bg-opacity-20 border border-red-400 text-red-100 px-4 py-3 rounded-lg mb-6 flex items-center">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif
            
            @if(session('success'))
                <div class="bg-green-500 bg-opacity-20 border border-green-400 text-green-100 px-4 py-3 rounded-lg mb-6 flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    <span>{{ session('success') }}</span>
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
            
            <!-- Login Form -->
            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf
                
                <!-- Email Field -->
                <div class="space-y-2">
                    <label for="email" class="block text-white font-medium">
                        <i class="fas fa-envelope mr-2"></i>Email / Username
                    </label>
                    <input 
                        type="text" 
                        id="email" 
                        name="email" 
                        value="{{ old('email') }}"
                        class="input-field w-full px-4 py-3 rounded-lg text-white placeholder-blue-200 focus:outline-none focus:ring-2 focus:ring-blue-400 transition-all"
                        placeholder="Masukkan email atau username"
                        required
                        autocomplete="email"
                        autofocus
                    >
                    @error('email')
                        <p class="text-red-300 text-sm mt-1">
                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                        </p>
                    @enderror
                </div>
                
                <!-- Password Field -->
                <div class="space-y-2">
                    <label for="password" class="block text-white font-medium">
                        <i class="fas fa-lock mr-2"></i>Password
                    </label>
                    <div class="relative">
                        <input 
                            type="password" 
                            id="password" 
                            name="password"
                            class="input-field w-full px-4 py-3 rounded-lg text-white placeholder-blue-200 focus:outline-none focus:ring-2 focus:ring-blue-400 transition-all pr-12"
                            placeholder="Masukkan password"
                            required
                            autocomplete="current-password"
                        >
                        <button 
                            type="button" 
                            class="absolute right-3 top-1/2 transform -translate-y-1/2 text-blue-200 hover:text-white transition-colors"
                            onclick="togglePassword()"
                        >
                            <i id="password-icon" class="fas fa-eye"></i>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-red-300 text-sm mt-1">
                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                        </p>
                    @enderror
                </div>
                
                <!-- Remember Me & Forgot Password -->
                <div class="flex items-center justify-between">
                    <label class="flex items-center text-white">
                        <input 
                            type="checkbox" 
                            name="remember" 
                            class="rounded border-blue-300 text-blue-600 shadow-sm focus:ring-blue-500 mr-2"
                            {{ old('remember') ? 'checked' : '' }}
                        >
                        <span class="text-sm">Ingat saya</span>
                    </label>
                    
                    <a href="{{ route('password.request') }}" class="text-sm text-blue-200 hover:text-white transition-colors">
                        Lupa password?
                    </a>
                </div>
                
                <!-- Login Button -->
                <button 
                    type="submit" 
                    class="btn-login w-full py-3 px-4 rounded-lg text-white font-semibold shadow-lg hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-transparent"
                >
                    <i class="fas fa-sign-in-alt mr-2"></i>
                    Masuk
                </button>
                
                <!-- Divider -->
                <div class="relative my-6">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-blue-300"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-transparent text-blue-200">atau</span>
                    </div>
                </div>
                
                <!-- Register Link -->
                <div class="text-center">
                    <p class="text-blue-200">
                        Belum punya akun? 
                        <a href="{{ route('register') }}" class="text-white hover:text-blue-100 font-semibold transition-colors">
                            Daftar disini
                        </a>
                    </p>
                </div>
            </form>
        </div>
        
        <!-- Footer -->
        <div class="text-center mt-8">
            <p class="text-blue-200 text-sm">
                © {{ date('Y') }} Desa Ciuwlan. Semua hak dilindungi.
            </p>
            <div class="flex justify-center space-x-4 mt-2">
                <a href="{{ route('home') }}" class="text-blue-200 hover:text-white text-sm transition-colors">
                    <i class="fas fa-home mr-1"></i>Beranda
                </a>
                <a href="{{ route('contact') }}" class="text-blue-200 hover:text-white text-sm transition-colors">
                    <i class="fas fa-phone mr-1"></i>Kontak
                </a>
                <a href="#" class="text-blue-200 hover:text-white text-sm transition-colors">
                    <i class="fas fa-info-circle mr-1"></i>Bantuan
                </a>
            </div>
        </div>
    </div>
    
    <!-- JavaScript -->
    <script>
        // Toggle password visibility
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const passwordIcon = document.getElementById('password-icon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                passwordIcon.classList.remove('fa-eye');
                passwordIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                passwordIcon.classList.remove('fa-eye-slash');
                passwordIcon.classList.add('fa-eye');
            }
        }
        
        // Auto-hide alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.bg-red-500, .bg-green-500');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.transition = 'opacity 0.5s ease-out';
                    alert.style.opacity = '0';
                    setTimeout(() => {
                        alert.remove();
                    }, 500);
                }, 5000);
            });
        });
        
        // Form submission loading state
        document.querySelector('form').addEventListener('submit', function(e) {
            const submitBtn = document.querySelector('button[type="submit"]');
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Memproses...';
            submitBtn.disabled = true;
        });
        
        // Real-time validation feedback
        const emailInput = document.getElementById('email');
        const passwordInput = document.getElementById('password');
        
        emailInput.addEventListener('input', function() {
            const email = this.value;
            if (email.length > 0) {
                if (validateEmail(email) || email.length >= 3) {
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
        
        passwordInput.addEventListener('input', function() {
            const password = this.value;
            if (password.length > 0) {
                if (password.length >= 6) {
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
        
        function validateEmail(email) {
            const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return re.test(email);
        }
    </script>
</body>
</html>