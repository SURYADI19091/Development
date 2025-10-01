<!-- Right Sidebar (1/4 width on desktop, below content on mobile) -->
<div class="xl:col-span-1 space-y-4 sm:space-y-6">
    <!-- Statistics Cards -->
    <a href="{{ route('population.stats') }}" class="block bg-blue-500 hover:bg-blue-600 text-white rounded-lg p-3 sm:p-4 transition-colors">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <i class="fas fa-users mr-2 sm:mr-3 text-sm sm:text-base"></i>
                <span class="font-semibold text-sm sm:text-base">Statistik Penduduk</span>
            </div>
            <div class="text-right">
                <div class="text-lg font-bold">{{ number_format($sidebarData['population_stats']['total_population']) }}</div>
                <div class="text-xs opacity-80">Jiwa</div>
            </div>
        </div>
    </a>
    
    <a href="{{ route('population.data') }}" class="block bg-teal-500 hover:bg-teal-600 text-white rounded-lg p-3 sm:p-4 transition-colors">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <i class="fas fa-home mr-2 sm:mr-3 text-sm sm:text-base"></i>
                <span class="font-semibold text-sm sm:text-base">Statistik Keluarga</span>
            </div>
            <div class="text-right">
                <div class="text-lg font-bold">{{ number_format($sidebarData['family_stats']['total_families']) }}</div>
                <div class="text-xs opacity-80">KK</div>
            </div>
        </div>
    </a>
    
    <div class="bg-purple-500 text-white rounded-lg p-3 sm:p-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <i class="fas fa-hand-holding-heart mr-2 sm:mr-3 text-sm sm:text-base"></i>
                <span class="font-semibold text-sm sm:text-base">Statistik Bantuan</span>
            </div>
            <div class="text-right">
                <div class="text-lg font-bold">{{ number_format($sidebarData['aid_stats']['total_aid_recipients']) }}</div>
                <div class="text-xs opacity-80">Penerima</div>
            </div>
        </div>
    </div>
    
    <div class="bg-green-500 text-white rounded-lg p-3 sm:p-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <i class="fas fa-chart-bar mr-2 sm:mr-3 text-sm sm:text-base"></i>
                <span class="font-semibold text-sm sm:text-base">Statistik Lainnya</span>
            </div>
            <div class="text-right">
                <div class="text-lg font-bold">{{ number_format($sidebarData['other_stats']['umkm_count'] + $sidebarData['other_stats']['tourism_objects']) }}</div>
                <div class="text-xs opacity-80">UMKM & Wisata</div>
            </div>
        </div>
    </div>

    <!-- Village Working Hours -->
    <div class="bg-indigo-600 text-white rounded-lg p-4">
        <h3 class="font-semibold mb-3 flex items-center">
            <i class="fas fa-clock mr-2"></i>
            JAM KERJA DESA
        </h3>
        <div class="space-y-2 text-sm">
            <div class="flex justify-between items-center">
                <span>Senin - Kamis</span>
                <span class="font-medium">08:00 - 15:30</span>
            </div>
            <div class="flex justify-between items-center">
                <span>Jumat</span>
                <span class="font-medium">08:00 - 15:00</span>
            </div>
            <div class="flex justify-between items-center border-t border-indigo-400 pt-2">
                <span>Istirahat</span>
                <span class="font-medium">12:00 - 13:00</span>
            </div>
        </div>
        <div class="mt-3 pt-3 border-t border-indigo-400">
            <div class="flex items-center justify-between">
                <span class="text-xs">Status Pelayanan:</span>
                <div id="service-status" class="flex items-center">
                    <div class="w-2 h-2 bg-green-400 rounded-full mr-1"></div>
                    <span class="text-xs font-medium">Buka</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Weather Widget -->
    <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-lg p-4">
        <h3 class="font-semibold mb-3 flex items-center">
            <i class="fas fa-cloud-sun mr-2"></i>
            CUACA HARI INI
        </h3>
        <div id="weather-widget" class="space-y-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div id="weather-icon" class="text-3xl mr-3">
                        <i class="fas fa-sun text-yellow-300"></i>
                    </div>
                    <div>
                        <div id="weather-temp" class="text-2xl font-bold">28°C</div>
                        <div id="weather-desc" class="text-sm opacity-90">Cerah</div>
                    </div>
                </div>
                <div class="text-right text-sm">
                    <div class="flex items-center mb-1">
                        <i class="fas fa-eye mr-1"></i>
                        <span id="weather-humidity">65%</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-wind mr-1"></i>
                        <span id="weather-wind">12 km/h</span>
                    </div>
                </div>
            </div>
            <div class="border-t border-blue-400 pt-2">
                <div class="flex justify-between text-xs">
                    <span id="weather-location">Desa Krandegan</span>
                    <span id="weather-time">{{ date('H:i') }} WIB</span>
                </div>
                <div class="text-xs mt-1 opacity-80" id="weather-greeting">
                    @php
                        $hour = date('H');
                        if ($hour < 11) {
                            echo 'Selamat pagi! Cuaca cerah untuk beraktivitas.';
                        } elseif ($hour < 15) {
                            echo 'Selamat siang! Jangan lupa minum air yang cukup.';
                        } elseif ($hour < 18) {
                            echo 'Selamat sore! Waktu yang tepat untuk jalan-jalan.';
                        } else {
                            echo 'Selamat malam! Istirahat yang cukup ya.';
                        }
                    @endphp
                </div>
            </div>
        </div>
    </div>

    <!-- Articles Section -->
    <div class="bg-teal-600 text-white rounded-lg p-4">
        <h3 class="font-semibold mb-3">ARSIP ARTIKEL</h3>
        <div class="space-y-3">
            <div class="text-sm">
                <p class="font-medium">Populer</p>
                @if($sidebarData['popular_article'])
                    <a href="{{ route('news.show', $sidebarData['popular_article']->slug) }}" class="text-xs text-teal-100 hover:text-white transition-colors">
                        {{ Str::limit($sidebarData['popular_article']->title, 40) }}
                        <br><small>{{ number_format($sidebarData['popular_article']->views_count ?? 0) }} kali dibaca</small>
                    </a>
                @else
                    <p class="text-xs text-teal-100">Belum ada artikel</p>
                @endif
            </div>
            <div class="text-sm">
                <p class="font-medium">Terbaru</p>
                @if($sidebarData['latest_article'])
                    <a href="{{ route('news.show', $sidebarData['latest_article']->slug) }}" class="text-xs text-teal-100 hover:text-white transition-colors">
                        {{ Str::limit($sidebarData['latest_article']->title, 40) }}
                        <br><small>{{ $sidebarData['latest_article']->created_at->format('d M Y') }}</small>
                    </a>
                @else
                    <p class="text-xs text-teal-100">Belum ada artikel</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Agenda Section -->
    <div class="bg-teal-600 text-white rounded-lg p-4">
        <h3 class="font-semibold mb-3">AGENDA</h3>
        <div class="space-y-2">
            @if($sidebarData['upcoming_agenda']->count() > 0)
                @foreach($sidebarData['upcoming_agenda'] as $agenda)
                <div class="bg-teal-700 rounded p-2">
                    <div class="text-sm font-medium">{{ Str::limit($agenda->title, 30) }}</div>
                    <div class="text-xs text-teal-200 flex items-center mt-1">
                        <i class="fas fa-calendar-alt mr-1"></i>
                        {{ \Carbon\Carbon::parse($agenda->event_date)->format('d M Y') }}
                    </div>
                    <div class="text-xs text-teal-200 flex items-center">
                        <i class="fas fa-map-marker-alt mr-1"></i>
                        {{ Str::limit($agenda->location ?? 'Balai Desa', 20) }}
                    </div>
                </div>
                @endforeach
                @if($sidebarData['other_stats']['agenda_count'] > 3)
                <div class="text-center pt-2">
                    <a href="{{ route('agenda.index') }}" class="text-xs text-teal-200 hover:text-white">
                        Lihat {{ $sidebarData['other_stats']['agenda_count'] - 3 }} agenda lainnya
                    </a>
                </div>
                @endif
            @else
                <div class="text-center">
                    <i class="fas fa-calendar-times text-4xl text-teal-200 mb-2"></i>
                    <p class="text-sm">Belum ada agenda terdaftar</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Programs Section -->
    <div class="bg-teal-600 text-white rounded-lg p-4">
        <h3 class="font-semibold mb-3">SINERGI PROGRAM</h3>
        <div class="flex justify-center space-x-4">
            <a href="#" class="text-2xl hover:text-blue-300 transition-colors">
                <i class="fab fa-facebook"></i>
            </a>
            <a href="#" class="text-2xl hover:text-blue-400 transition-colors">
                <i class="fab fa-twitter"></i>
            </a>
            <a href="#" class="text-2xl hover:text-pink-300 transition-colors">
                <i class="fab fa-instagram"></i>
            </a>
            <a href="#" class="text-2xl hover:text-green-300 transition-colors">
                <i class="fab fa-whatsapp"></i>
            </a>
        </div>
    </div>
</div>

<!-- Working Hours Status Script -->
<script>
    function updateServiceStatus() {
        const now = new Date();
        const day = now.getDay(); // 0 = Sunday, 1 = Monday, ..., 6 = Saturday
        const hour = now.getHours();
        const minute = now.getMinutes();
        const currentTime = hour * 100 + minute; // Convert to HHMM format for easy comparison
        
        const statusElement = document.getElementById('service-status');
        
        if (!statusElement) return; // Exit if element not found
        
        let isOpen = false;
        
        // Monday to Thursday (1-4): 08:00 - 15:30
        if (day >= 1 && day <= 4) {
            if (currentTime >= 800 && currentTime <= 1530) {
                // Check if not lunch break (12:00 - 13:00)
                if (!(currentTime >= 1200 && currentTime < 1300)) {
                    isOpen = true;
                }
            }
        }
        // Friday (5): 08:00 - 15:00
        else if (day === 5) {
            if (currentTime >= 800 && currentTime <= 1500) {
                // Check if not lunch break (12:00 - 13:00)
                if (!(currentTime >= 1200 && currentTime < 1300)) {
                    isOpen = true;
                }
            }
        }
        
        // Update status display
        if (isOpen) {
            statusElement.innerHTML = '<div class="w-2 h-2 bg-green-400 rounded-full mr-1"></div><span class="text-xs font-medium">Buka</span>';
        } else if (day >= 1 && day <= 5 && currentTime >= 1200 && currentTime < 1300) {
            statusElement.innerHTML = '<div class="w-2 h-2 bg-yellow-400 rounded-full mr-1"></div><span class="text-xs font-medium">Istirahat</span>';
        } else {
            statusElement.innerHTML = '<div class="w-2 h-2 bg-red-400 rounded-full mr-1"></div><span class="text-xs font-medium">Tutup</span>';
        }
    }
    
    // Update status when page loads
    document.addEventListener('DOMContentLoaded', function() {
        updateServiceStatus();
        updateWeatherWidget();
        
        // Update status every minute
        setInterval(updateServiceStatus, 60000);
        // Update weather every 10 minutes
        setInterval(updateWeatherWidget, 600000);
    });
    
    // Weather Widget Function
    function updateWeatherWidget() {
        const weatherConditions = [
            {
                condition: 'cerah',
                icon: 'fas fa-sun',
                iconColor: 'text-yellow-300',
                temp: Math.floor(Math.random() * 8) + 26, // 26-34°C
                humidity: Math.floor(Math.random() * 20) + 50, // 50-70%
                wind: Math.floor(Math.random() * 10) + 8, // 8-18 km/h
                description: 'Cerah'
            },
            {
                condition: 'berawan',
                icon: 'fas fa-cloud',
                iconColor: 'text-gray-200',
                temp: Math.floor(Math.random() * 6) + 24, // 24-30°C
                humidity: Math.floor(Math.random() * 15) + 60, // 60-75%
                wind: Math.floor(Math.random() * 8) + 10, // 10-18 km/h
                description: 'Berawan'
            },
            {
                condition: 'hujan_ringan',
                icon: 'fas fa-cloud-rain',
                iconColor: 'text-blue-200',
                temp: Math.floor(Math.random() * 4) + 22, // 22-26°C
                humidity: Math.floor(Math.random() * 15) + 70, // 70-85%
                wind: Math.floor(Math.random() * 10) + 12, // 12-22 km/h
                description: 'Hujan Ringan'
            }
        ];
        
        // Get current hour to determine likely weather
        const hour = new Date().getHours();
        let weatherIndex = 0;
        
        if (hour >= 6 && hour <= 17) {
            // Daytime - more likely to be sunny
            weatherIndex = Math.random() > 0.3 ? 0 : (Math.random() > 0.7 ? 1 : 2);
        } else {
            // Evening/night - more likely to be cloudy or rainy
            weatherIndex = Math.random() > 0.5 ? 1 : (Math.random() > 0.8 ? 0 : 2);
        }
        
        const weather = weatherConditions[weatherIndex];
        
        // Update weather display
        const iconElement = document.getElementById('weather-icon');
        const tempElement = document.getElementById('weather-temp');
        const descElement = document.getElementById('weather-desc');
        const humidityElement = document.getElementById('weather-humidity');
        const windElement = document.getElementById('weather-wind');
        const timeElement = document.getElementById('weather-time');
        
        if (iconElement) iconElement.innerHTML = `<i class="${weather.icon} ${weather.iconColor}"></i>`;
        if (tempElement) tempElement.textContent = `${weather.temp}°C`;
        if (descElement) descElement.textContent = weather.description;
        if (humidityElement) humidityElement.textContent = `${weather.humidity}%`;
        if (windElement) windElement.textContent = `${weather.wind} km/h`;
        if (timeElement) {
            const now = new Date();
            const timeString = now.toLocaleTimeString('id-ID', { 
                hour: '2-digit', 
                minute: '2-digit',
                timeZone: 'Asia/Jakarta'
            });
            timeElement.textContent = `${timeString} WIB`;
        }
        
        // Update greeting based on weather
        const greetingElement = document.getElementById('weather-greeting');
        if (greetingElement) {
            const hour = new Date().getHours();
            let greeting = '';
            
            if (weather.condition === 'hujan_ringan') {
                greeting = 'Sedang hujan ringan, jangan lupa bawa payung!';
            } else if (weather.condition === 'berawan') {
                greeting = 'Cuaca berawan, cocok untuk aktivitas outdoor.';
            } else {
                if (hour < 11) {
                    greeting = 'Selamat pagi! Cuaca cerah untuk beraktivitas.';
                } else if (hour < 15) {
                    greeting = 'Selamat siang! Jangan lupa minum air yang cukup.';
                } else if (hour < 18) {
                    greeting = 'Selamat sore! Waktu yang tepat untuk jalan-jalan.';
                } else {
                    greeting = 'Selamat malam! Istirahat yang cukup ya.';
                }
            }
            
            greetingElement.textContent = greeting;
        }
    }
</script>
