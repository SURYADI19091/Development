<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Reset Password - Sistem Informasi Desa Ciuwlan</title>
    
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
            background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
        }
        
        .reset-card {
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
            background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
            transition: all 0.3s ease;
        }
        
        .btn-reset:hover {
            background: linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%);
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
    </div>
    
    <!-- Reset Password Container -->
    <div class="w-full max-w-md">
        <!-- Logo & Header -->
        <div class="text-center mb-8">
            <div class="logo-pulse inline-flex items-center justify-center w-20 h-20 bg-white rounded-full shadow-lg mb-4">
                <i class="fas fa-shield-alt text-purple-600 text-2xl"></i>
            </div>
            <h1 class="text-3xl font-bold text-white mb-2">Reset Password</h1>
            <p class="text-purple-100">Buat password baru untuk akun Anda</p>
            <p class="text-purple-200 text-sm">Password harus kuat dan aman</p>
        </div>
        
        <!-- Reset Password Card -->
        <div class="reset-card rounded-2xl p-8 shadow-2xl">
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
            
            <!-- Reset Form -->
            <form method="POST" action="{{ route('password.store') }}" class="space-y-6">
                @csrf
                
                <!-- Hidden Token -->
                <input type="hidden" name="token" value="{{ $request->route('token') }}">
                
                <!-- Email Field (readonly) -->
                <div class="space-y-2">
                    <label for="email" class="block text-white font-medium">
                        <i class="fas fa-envelope mr-2"></i>Email
                    </label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        value="{{ old('email', $request->email) }}"
                        class="input-field w-full px-4 py-3 rounded-lg text-white placeholder-purple-200 focus:outline-none focus:ring-2 focus:ring-purple-400 transition-all bg-opacity-20"
                        readonly
                    >
                </div>
                
                <!-- New Password Field -->
                <div class="space-y-2">
                    <label for="password" class="block text-white font-medium">
                        <i class="fas fa-lock mr-2"></i>Password Baru
                    </label>
                    <div class="relative">
                        <input 
                            type="password" 
                            id="password" 
                            name="password"
                            class="input-field w-full px-4 py-3 rounded-lg text-white placeholder-purple-200 focus:outline-none focus:ring-2 focus:ring-purple-400 transition-all pr-12"
                            placeholder="Minimal 8 karakter"
                            required
                            autocomplete="new-password"
                        >
                        <button 
                            type="button" 
                            class="absolute right-3 top-1/2 transform -translate-y-1/2 text-purple-200 hover:text-white transition-colors"
                            onclick="togglePassword('password')"
                        >
                            <i id="password-icon" class="fas fa-eye"></i>
                        </button>
                    </div>
                    <!-- Password Strength Indicator -->
                    <div class="mt-2">
                        <div class="password-strength bg-gray-600" id="password-strength"></div>
                        <p class="text-xs text-purple-200 mt-1" id="password-hint">
                            Password harus minimal 8 karakter dengan kombinasi huruf, angka, dan simbol
                        </p>
                    </div>
                    @error('password')
                        <p class="text-red-300 text-sm mt-1">
                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                        </p>
                    @enderror
                </div>
                
                <!-- Confirm Password Field -->
                <div class="space-y-2">
                    <label for="password_confirmation" class="block text-white font-medium">
                        <i class="fas fa-lock mr-2"></i>Konfirmasi Password
                    </label>
                    <div class="relative">
                        <input 
                            type="password" 
                            id="password_confirmation" 
                            name="password_confirmation"
                            class="input-field w-full px-4 py-3 rounded-lg text-white placeholder-purple-200 focus:outline-none focus:ring-2 focus:ring-purple-400 transition-all pr-12"
                            placeholder="Ulangi password baru"
                            required
                            autocomplete="new-password"
                        >
                        <button 
                            type="button" 
                            class="absolute right-3 top-1/2 transform -translate-y-1/2 text-purple-200 hover:text-white transition-colors"
                            onclick="togglePassword('password_confirmation')"
                        >
                            <i id="password_confirmation-icon" class="fas fa-eye"></i>
                        </button>
                    </div>
                    <p class="text-xs mt-1" id="password-match"></p>
                    @error('password_confirmation')
                        <p class="text-red-300 text-sm mt-1">
                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                        </p>
                    @enderror
                </div>
                
                <!-- Security Requirements -->
                <div class="bg-purple-500 bg-opacity-20 border border-purple-400 text-purple-100 px-4 py-3 rounded-lg">
                    <div class="flex items-center mb-2">
                        <i class="fas fa-shield-alt mr-2"></i>
                        <span class="font-medium">Persyaratan Password:</span>
                    </div>
                    <ul class="text-xs space-y-1" id="password-requirements">
                        <li id="length-req" class="flex items-center">
                            <i class="fas fa-times text-red-300 mr-2 w-3"></i>
                            Minimal 8 karakter
                        </li>
                        <li id="lower-req" class="flex items-center">
                            <i class="fas fa-times text-red-300 mr-2 w-3"></i>
                            Huruf kecil (a-z)
                        </li>
                        <li id="upper-req" class="flex items-center">
                            <i class="fas fa-times text-red-300 mr-2 w-3"></i>
                            Huruf besar (A-Z)
                        </li>
                        <li id="number-req" class="flex items-center">
                            <i class="fas fa-times text-red-300 mr-2 w-3"></i>
                            Angka (0-9)
                        </li>
                        <li id="special-req" class="flex items-center">
                            <i class="fas fa-times text-red-300 mr-2 w-3"></i>
                            Karakter khusus (!@#$%^&*)
                        </li>
                    </ul>
                </div>
                
                <!-- Reset Button -->
                <button 
                    type="submit" 
                    class="btn-reset w-full py-3 px-4 rounded-lg text-white font-semibold shadow-lg hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 focus:ring-offset-transparent"
                    id="reset-btn"
                >
                    <i class="fas fa-save mr-2"></i>
                    Reset Password
                </button>
                
                <!-- Back to Login -->
                <div class="text-center">
                    <a href="{{ route('login') }}" class="text-purple-200 hover:text-white transition-colors text-sm">
                        <i class="fas fa-arrow-left mr-1"></i>
                        Kembali ke Login
                    </a>
                </div>
            </form>
        </div>
        
        <!-- Footer -->
        <div class="text-center mt-6">
            <p class="text-purple-200 text-sm">
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
        
        // Check password requirements
        function checkPasswordRequirements(password) {
            const requirements = {
                length: password.length >= 8,
                lower: /[a-z]/.test(password),
                upper: /[A-Z]/.test(password),
                number: /[0-9]/.test(password),
                special: /[^A-Za-z0-9]/.test(password)
            };
            
            // Update UI for each requirement
            Object.keys(requirements).forEach(req => {
                const element = document.getElementById(req + '-req');
                const icon = element.querySelector('i');
                
                if (requirements[req]) {
                    icon.classList.remove('fa-times', 'text-red-300');
                    icon.classList.add('fa-check', 'text-green-300');
                } else {
                    icon.classList.remove('fa-check', 'text-green-300');
                    icon.classList.add('fa-times', 'text-red-300');
                }
            });
            
            return requirements;
        }
        
        // Calculate password strength
        function calculatePasswordStrength(password) {
            const requirements = checkPasswordRequirements(password);
            let strength = 0;
            
            Object.values(requirements).forEach(met => {
                if (met) strength++;
            });
            
            return strength;
        }
        
        // Update password strength indicator
        function updatePasswordStrength(password) {
            const strength = calculatePasswordStrength(password);
            const strengthBar = document.getElementById('password-strength');
            const strengthHint = document.getElementById('password-hint');
            
            const colors = ['bg-red-500', 'bg-orange-500', 'bg-yellow-500', 'bg-green-400', 'bg-green-500'];
            const messages = [
                'Password sangat lemah',
                'Password lemah', 
                'Password cukup',
                'Password kuat',
                'Password sangat kuat'
            ];
            
            // Remove all color classes
            colors.forEach(color => strengthBar.classList.remove(color));
            
            if (password.length === 0) {
                strengthBar.classList.add('bg-gray-600');
                strengthHint.textContent = 'Password harus minimal 8 karakter dengan kombinasi huruf, angka, dan simbol';
                strengthHint.className = 'text-xs text-purple-200 mt-1';
            } else {
                const colorIndex = Math.min(strength - 1, 4);
                if (colorIndex >= 0) {
                    strengthBar.classList.add(colors[colorIndex]);
                    strengthHint.textContent = messages[colorIndex];
                    
                    if (strength < 3) {
                        strengthHint.className = 'text-xs text-red-300 mt-1';
                    } else if (strength < 5) {
                        strengthHint.className = 'text-xs text-yellow-300 mt-1';
                    } else {
                        strengthHint.className = 'text-xs text-green-300 mt-1';
                    }
                } else {
                    strengthBar.classList.add('bg-red-500');
                    strengthHint.textContent = 'Password terlalu lemah';
                    strengthHint.className = 'text-xs text-red-300 mt-1';
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
                return false;
            }
            
            if (password === confirmation) {
                matchIndicator.textContent = '✓ Password cocok';
                matchIndicator.className = 'text-xs text-green-300 mt-1';
                return true;
            } else {
                matchIndicator.textContent = '✗ Password tidak cocok';
                matchIndicator.className = 'text-xs text-red-300 mt-1';
                return false;
            }
        }
        
        // Event listeners
        document.addEventListener('DOMContentLoaded', function() {
            const passwordInput = document.getElementById('password');
            const confirmationInput = document.getElementById('password_confirmation');
            const resetBtn = document.getElementById('reset-btn');
            
            // Password strength and requirements
            passwordInput.addEventListener('input', function() {
                updatePasswordStrength(this.value);
                checkPasswordMatch();
            });
            
            // Password confirmation
            confirmationInput.addEventListener('input', checkPasswordMatch);
            
            // Form submission
            document.querySelector('form').addEventListener('submit', function(e) {
                const password = passwordInput.value;
                const confirmation = confirmationInput.value;
                
                if (password !== confirmation) {
                    e.preventDefault();
                    alert('Password dan konfirmasi password tidak cocok!');
                    return;
                }
                
                if (calculatePasswordStrength(password) < 3) {
                    e.preventDefault();
                    alert('Password terlalu lemah! Gunakan kombinasi huruf besar, kecil, angka, dan simbol.');
                    return;
                }
                
                resetBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Memproses...';
                resetBtn.disabled = true;
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
                }, 8000);
            });
        });
    </script>
</body>
</html>