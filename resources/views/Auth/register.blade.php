<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Daftar - Sistem Informasi Desa Ciuwlan</title>
    
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
            background: linear-gradient(135deg, #4ade80 0%, #059669 100%);
        }
        
        .register-card {
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
        
        .btn-register {
            background: linear-gradient(135deg, #10b981 0%, #047857 100%);
            transition: all 0.3s ease;
        }
        
        .btn-register:hover {
            background: linear-gradient(135deg, #059669 0%, #064e3b 100%);
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
            top: 5%;
            left: 10%;
            animation-delay: 0s;
        }
        
        .shape:nth-child(2) {
            width: 120px;
            height: 120px;
            top: 15%;
            right: 10%;
            animation-delay: 2s;
        }
        
        .shape:nth-child(3) {
            width: 60px;
            height: 60px;
            bottom: 5%;
            left: 15%;
            animation-delay: 4s;
        }
        
        .shape:nth-child(4) {
            width: 90px;
            height: 90px;
            bottom: 15%;
            right: 15%;
            animation-delay: 1s;
        }
        
        .shape:nth-child(5) {
            width: 70px;
            height: 70px;
            top: 50%;
            left: 5%;
            animation-delay: 3s;
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
        
        .password-strength {
            height: 4px;
            border-radius: 2px;
            transition: all 0.3s ease;
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
        <div class="shape"></div>
    </div>
    
    <!-- Register Container -->
    <div class="w-full max-w-lg">
        <!-- Logo & Header -->
        <div class="text-center mb-6">
            <div class="logo-pulse inline-flex items-center justify-center w-16 h-16 bg-white rounded-full shadow-lg mb-3">
                <i class="fas fa-user-plus text-green-600 text-xl"></i>
            </div>
            <h1 class="text-2xl font-bold text-white mb-1">Daftar Akun</h1>
            <p class="text-green-100 text-sm">Sistem Informasi Desa Ciuwlan</p>
        </div>
        
        <!-- Register Card -->
        <div class="register-card rounded-2xl p-6 shadow-2xl">
            <!-- Alert Messages -->
            @if(session('error'))
                <div class="bg-red-500 bg-opacity-20 border border-red-400 text-red-100 px-4 py-3 rounded-lg mb-4 flex items-center">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif
            
            @if(session('success'))
                <div class="bg-green-500 bg-opacity-20 border border-green-400 text-green-100 px-4 py-3 rounded-lg mb-4 flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            
            <!-- Register Form -->
            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf
                
                <!-- Name Field -->
                <div class="space-y-1">
                    <label for="name" class="block text-white font-medium text-sm">
                        <i class="fas fa-user mr-2"></i>Nama Lengkap
                    </label>
                    <input 
                        type="text" 
                        id="name" 
                        name="name" 
                        value="{{ old('name') }}"
                        class="input-field w-full px-3 py-2.5 rounded-lg text-white placeholder-green-200 focus:outline-none focus:ring-2 focus:ring-green-400 transition-all"
                        placeholder="Masukkan nama lengkap"
                        required
                        autocomplete="name"
                        autofocus
                    >
                    @error('name')
                        <p class="text-red-300 text-xs mt-1">
                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                        </p>
                    @enderror
                </div>
                
                <!-- Email Field -->
                <div class="space-y-1">
                    <label for="email" class="block text-white font-medium text-sm">
                        <i class="fas fa-envelope mr-2"></i>Email
                    </label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        value="{{ old('email') }}"
                        class="input-field w-full px-3 py-2.5 rounded-lg text-white placeholder-green-200 focus:outline-none focus:ring-2 focus:ring-green-400 transition-all"
                        placeholder="contoh@email.com"
                        required
                        autocomplete="email"
                    >
                    @error('email')
                        <p class="text-red-300 text-xs mt-1">
                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                        </p>
                    @enderror
                </div>
                
                <!-- Phone Field -->
                <div class="space-y-1">
                    <label for="phone" class="block text-white font-medium text-sm">
                        <i class="fas fa-phone mr-2"></i>No. Telepon
                    </label>
                    <input 
                        type="tel" 
                        id="phone" 
                        name="phone" 
                        value="{{ old('phone') }}"
                        class="input-field w-full px-3 py-2.5 rounded-lg text-white placeholder-green-200 focus:outline-none focus:ring-2 focus:ring-green-400 transition-all"
                        placeholder="08xxxxxxxxxx"
                        required
                    >
                    @error('phone')
                        <p class="text-red-300 text-xs mt-1">
                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                        </p>
                    @enderror
                </div>
                
                <!-- Password Field -->
                <div class="space-y-1">
                    <label for="password" class="block text-white font-medium text-sm">
                        <i class="fas fa-lock mr-2"></i>Password
                    </label>
                    <div class="relative">
                        <input 
                            type="password" 
                            id="password" 
                            name="password"
                            class="input-field w-full px-3 py-2.5 rounded-lg text-white placeholder-green-200 focus:outline-none focus:ring-2 focus:ring-green-400 transition-all pr-12"
                            placeholder="Minimal 8 karakter"
                            required
                            autocomplete="new-password"
                        >
                        <button 
                            type="button" 
                            class="absolute right-3 top-1/2 transform -translate-y-1/2 text-green-200 hover:text-white transition-colors"
                            onclick="togglePassword('password')"
                        >
                            <i id="password-icon" class="fas fa-eye text-sm"></i>
                        </button>
                    </div>
                    <!-- Password Strength Indicator -->
                    <div class="mt-1">
                        <div class="password-strength bg-gray-600" id="password-strength"></div>
                        <p class="text-xs text-green-200 mt-1" id="password-hint">
                            Password harus minimal 8 karakter
                        </p>
                    </div>
                    @error('password')
                        <p class="text-red-300 text-xs mt-1">
                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                        </p>
                    @enderror
                </div>
                
                <!-- Confirm Password Field -->
                <div class="space-y-1">
                    <label for="password_confirmation" class="block text-white font-medium text-sm">
                        <i class="fas fa-lock mr-2"></i>Konfirmasi Password
                    </label>
                    <div class="relative">
                        <input 
                            type="password" 
                            id="password_confirmation" 
                            name="password_confirmation"
                            class="input-field w-full px-3 py-2.5 rounded-lg text-white placeholder-green-200 focus:outline-none focus:ring-2 focus:ring-green-400 transition-all pr-12"
                            placeholder="Ulangi password"
                            required
                            autocomplete="new-password"
                        >
                        <button 
                            type="button" 
                            class="absolute right-3 top-1/2 transform -translate-y-1/2 text-green-200 hover:text-white transition-colors"
                            onclick="togglePassword('password_confirmation')"
                        >
                            <i id="password_confirmation-icon" class="fas fa-eye text-sm"></i>
                        </button>
                    </div>
                    <p class="text-xs mt-1" id="password-match"></p>
                    @error('password_confirmation')
                        <p class="text-red-300 text-xs mt-1">
                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                        </p>
                    @enderror
                </div>
                
                <!-- Terms Agreement -->
                <div class="flex items-start space-x-2">
                    <input 
                        type="checkbox" 
                        id="terms" 
                        name="terms" 
                        class="mt-1 rounded border-green-300 text-green-600 shadow-sm focus:ring-green-500"
                        required
                    >
                    <label for="terms" class="text-xs text-white leading-relaxed">
                        Saya setuju dengan 
                        <a href="#" class="text-green-200 hover:text-white underline">syarat dan ketentuan</a> 
                        serta 
                        <a href="#" class="text-green-200 hover:text-white underline">kebijakan privasi</a> 
                        yang berlaku.
                    </label>
                </div>
                
                <!-- Register Button -->
                <button 
                    type="submit" 
                    class="btn-register w-full py-2.5 px-4 rounded-lg text-white font-semibold shadow-lg hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 focus:ring-offset-transparent"
                    id="register-btn"
                >
                    <i class="fas fa-user-plus mr-2"></i>
                    Daftar Sekarang
                </button>
                
                <!-- Divider -->
                <div class="relative my-4">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-green-300"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-transparent text-green-200">atau</span>
                    </div>
                </div>
                
                <!-- Login Link -->
                <div class="text-center">
                    <p class="text-green-200 text-sm">
                        Sudah punya akun? 
                        <a href="{{ route('login') }}" class="text-white hover:text-green-100 font-semibold transition-colors">
                            Masuk disini
                        </a>
                    </p>
                </div>
            </form>
        </div>
        
        <!-- Footer -->
        <div class="text-center mt-6">
            <p class="text-green-200 text-xs">
                © {{ date('Y') }} Desa Ciuwlan. Semua hak dilindungi.
            </p>
        </div>
    </div>
    
    <!-- JavaScript -->
    <script>
        // Toggle password visibility
        function togglePassword(fieldId) {
            const passwordInput = document.getElementById(fieldId);
            const passwordIcon = document.getElementById(fieldId + '-icon');
            
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
        
        // Password strength checker
        function checkPasswordStrength(password) {
            let strength = 0;
            const checks = [
                password.length >= 8,
                /[a-z]/.test(password),
                /[A-Z]/.test(password),
                /[0-9]/.test(password),
                /[^A-Za-z0-9]/.test(password)
            ];
            
            checks.forEach(check => {
                if (check) strength++;
            });
            
            return strength;
        }
        
        // Update password strength indicator
        function updatePasswordStrength(password) {
            const strength = checkPasswordStrength(password);
            const strengthBar = document.getElementById('password-strength');
            const strengthHint = document.getElementById('password-hint');
            
            const colors = ['bg-red-500', 'bg-orange-500', 'bg-yellow-500', 'bg-green-400', 'bg-green-500'];
            const messages = [
                'Password terlalu lemah',
                'Password lemah', 
                'Password cukup',
                'Password kuat',
                'Password sangat kuat'
            ];
            
            // Remove all color classes
            colors.forEach(color => strengthBar.classList.remove(color));
            
            if (password.length === 0) {
                strengthBar.classList.add('bg-gray-600');
                strengthHint.textContent = 'Password harus minimal 8 karakter';
                strengthHint.className = 'text-xs text-green-200 mt-1';
            } else {
                const colorIndex = Math.min(strength - 1, 4);
                strengthBar.classList.add(colors[colorIndex]);
                strengthHint.textContent = messages[colorIndex];
                
                if (strength < 2) {
                    strengthHint.className = 'text-xs text-red-300 mt-1';
                } else if (strength < 4) {
                    strengthHint.className = 'text-xs text-yellow-300 mt-1';
                } else {
                    strengthHint.className = 'text-xs text-green-300 mt-1';
                }
            }
        }
        
        // Check password match
        function checkPasswordMatch() {
            const password = document.getElementById('password').value;
            const confirmation = document.getElementById('password_confirmation').value;
            const matchIndicator = document.getElementById('password-match');
            
            if (confirmation.length === 0) {
                matchIndicator.textContent = '';
                return;
            }
            
            if (password === confirmation) {
                matchIndicator.textContent = '✓ Password cocok';
                matchIndicator.className = 'text-xs text-green-300 mt-1';
            } else {
                matchIndicator.textContent = '✗ Password tidak cocok';
                matchIndicator.className = 'text-xs text-red-300 mt-1';
            }
        }
        
        // Event listeners
        document.addEventListener('DOMContentLoaded', function() {
            const passwordInput = document.getElementById('password');
            const confirmationInput = document.getElementById('password_confirmation');
            const phoneInput = document.getElementById('phone');
            
            // Password strength
            passwordInput.addEventListener('input', function() {
                updatePasswordStrength(this.value);
                checkPasswordMatch();
            });
            
            // Password confirmation
            confirmationInput.addEventListener('input', checkPasswordMatch);
            
            // Phone number formatting
            phoneInput.addEventListener('input', function() {
                let value = this.value.replace(/\D/g, '');
                if (value.startsWith('62')) {
                    value = value.substring(2);
                }
                if (value.startsWith('0')) {
                    value = value.substring(1);
                }
                this.value = '08' + value;
            });
            
            // Form submission
            document.querySelector('form').addEventListener('submit', function(e) {
                const password = document.getElementById('password').value;
                const confirmation = document.getElementById('password_confirmation').value;
                const registerBtn = document.getElementById('register-btn');
                
                if (password !== confirmation) {
                    e.preventDefault();
                    alert('Password dan konfirmasi password tidak cocok!');
                    return;
                }
                
                registerBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Memproses...';
                registerBtn.disabled = true;
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
                }, 5000);
            });
        });
    </script>
</body>
</html>