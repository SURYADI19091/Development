<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'DESA CIWULAN')</title>
    
    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100 min-h-screen">
    <!-- Top Header -->
    <header class="bg-gradient-to-r from-teal-500 to-cyan-500 text-white py-3 px-4">
        <div class="flex justify-between items-center">
            <div class="flex items-center">
                <!-- Mobile Menu Toggle -->
                <button id="mobile-menu-toggle" class="lg:hidden mr-3 text-white hover:text-gray-200">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                <i class="fas fa-check-circle mr-2"></i>
                <span class="text-sm hidden sm:inline">@yield('header_date', 'Sabtu, 27 September 2025')</span>
                <span class="text-xs sm:hidden">@yield('header_date_short', '27 Sep 2025')</span>
            </div>
            <div class="flex items-center space-x-2 sm:space-x-4 text-sm">
                @php
                    $jwtToken = request()->cookie('jwt_token');
                    $currentUser = null;
                    if ($jwtToken) {
                        try {
                            $jwtService = app(\App\Services\JWTService::class);
                            $payload = $jwtService->validateToken($jwtToken);
                            if ($payload) {
                                $currentUser = \App\Models\User::find($payload['user_id']);
                            }
                        } catch (\Exception $e) {
                            // Token invalid
                        }
                    }
                @endphp
                @if($currentUser)
                    <!-- Authenticated User Menu -->
                    <span class="hidden sm:inline text-xs opacity-75">{{ $currentUser->name }}</span>
                    <div class="relative group">
                        <button class="hover:text-gray-200 flex items-center space-x-1">
                            <i class="fas fa-user-circle text-lg"></i>
                            <i class="fas fa-chevron-down text-xs"></i>
                        </button>
                        <!-- Dropdown Menu -->
                        <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                            <div class="py-2">
                                <div class="px-4 py-2 text-gray-800 border-b">
                                    <p class="font-medium text-sm">{{ $currentUser->name }}</p>
                                    <p class="text-xs text-gray-600">{{ $currentUser->email }}</p>
                                </div>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-user mr-2"></i>Profil
                                </a>
                                @if($currentUser && $currentUser->role === 'admin')
                                    {{-- <a href="{{ route('system.dashboard') }}" class="block px-4 py-2 text-sm text-purple-700 hover:bg-purple-50">
                                        <i class="fas fa-shield-alt mr-2"></i>System Admin
                                    </a> --}}
                                @endif
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-cog mr-2"></i>Pengaturan
                                </a>
                                {{-- <form method="POST" action="{{ route('jwt.logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left block px-4 py-2 text-sm text-red-700 hover:bg-red-50">
                                        <i class="fas fa-sign-out-alt mr-2"></i>Keluar
                                    </button>
                                </form> --}}
                            </div>
                        </div>
                    </div>
                @else
                    {{-- <!-- Guest Menu -->
                    <a href="{{ route('login') }}" class="hover:text-gray-200">
                        <i class="fas fa-sign-in-alt mr-1"></i>
                        <span class="hidden sm:inline">Masuk</span>
                    </a> --}}
                @endif
                
                <a href="#" class="hover:text-gray-200"><i class="fas fa-search"></i></a>
                <a href="#" class="hover:text-gray-200 hidden sm:inline"><i class="fas fa-chart-bar"></i></a>
                <a href="#" class="hover:text-gray-200"><i class="fas fa-cog"></i></a>
            </div>
        </div>
    </header>

    <div class="flex min-h-screen relative">
        <!-- Left Sidebar Component -->
        @include('frontend.layout.sidebar-left')

        <!-- Main Content Area -->
        <main class="flex-1 w-full lg:w-auto">
            <!-- Page Header -->
            <div class="@yield('header_bg_color', 'bg-teal-600') text-white p-3 sm:p-4">
                <div class="flex items-center">
                    <i class="@yield('header_icon', 'fas fa-chart-bar') mr-2 sm:mr-3"></i>
                    <h1 class="text-base sm:text-lg font-semibold">@yield('page_title', 'STATISTIK')</h1>
                </div>
            </div>

            <!-- Flash Messages -->
            @if(session('success') || session('error') || session('info'))
                <div class="p-3 sm:p-6 pb-0">
                    @if(session('success'))
                        <div class="bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg flex items-center mb-4 animate-pulse">
                            <i class="fas fa-check-circle mr-3"></i>
                            <span>{{ session('success') }}</span>
                            <button onclick="this.parentElement.style.display='none'" class="ml-auto text-white hover:text-gray-200">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg flex items-center mb-4">
                            <i class="fas fa-exclamation-circle mr-3"></i>
                            <span>{{ session('error') }}</span>
                            <button onclick="this.parentElement.style.display='none'" class="ml-auto text-white hover:text-gray-200">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    @endif
                    
                    @if(session('info'))
                        <div class="bg-blue-500 text-white px-6 py-3 rounded-lg shadow-lg flex items-center mb-4">
                            <i class="fas fa-info-circle mr-3"></i>
                            <span>{{ session('info') }}</span>
                            <button onclick="this.parentElement.style.display='none'" class="ml-auto text-white hover:text-gray-200">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    @endif
                </div>
            @endif

            <!-- Content Grid -->
            <div class="p-3 sm:p-6">
                <!-- Content Layout -->
                <div class="xl:grid xl:grid-cols-4 xl:gap-6 space-y-6 xl:space-y-0">
                    <!-- Main Content -->
                    @yield('content')

                    <!-- Right Sidebar Component -->
                    @include('frontend.layout.sidebar-right')
                </div>
            </div>
        </main>
    </div>

    <!-- Mobile Menu Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
            const sidebar = document.getElementById('sidebar');
            const mobileOverlay = document.getElementById('mobile-overlay');
            
            // Toggle mobile menu
            mobileMenuToggle.addEventListener('click', function() {
                sidebar.classList.toggle('-translate-x-full');
                mobileOverlay.classList.toggle('hidden');
            });
            
            // Close menu when clicking overlay
            mobileOverlay.addEventListener('click', function() {
                sidebar.classList.add('-translate-x-full');
                mobileOverlay.classList.add('hidden');
            });
            
            // Close menu when clicking outside on larger screens
            document.addEventListener('click', function(event) {
                if (window.innerWidth < 1024) {
                    const isClickInsideSidebar = sidebar.contains(event.target);
                    const isClickOnToggle = mobileMenuToggle.contains(event.target);
                    
                    if (!isClickInsideSidebar && !isClickOnToggle && !sidebar.classList.contains('-translate-x-full')) {
                        sidebar.classList.add('-translate-x-full');
                        mobileOverlay.classList.add('hidden');
                    }
                }
            });
            
            // Handle window resize
            window.addEventListener('resize', function() {
                if (window.innerWidth >= 1024) {
                    sidebar.classList.remove('-translate-x-full');
                    mobileOverlay.classList.add('hidden');
                }
            });

            // Auto-hide success flash messages after 5 seconds
            const successMessages = document.querySelectorAll('.bg-green-500');
            successMessages.forEach(function(message) {
                setTimeout(function() {
                    message.style.transition = 'opacity 0.5s ease-out';
                    message.style.opacity = '0';
                    setTimeout(function() {
                        if (message.parentElement) {
                            message.parentElement.removeChild(message);
                        }
                    }, 500);
                }, 5000);
            });
        });
    </script>
    
    @yield('scripts')
</body>
</html>
