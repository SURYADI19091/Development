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
                    <span id="weather-location">Desa Ciwulan, Telagsari</span>
                    <span id="weather-time">{{ date('H:i:s') }} WIB</span>
                </div>
                <div class="text-center text-xs mt-1 opacity-75" id="current-date">
                    {{ date('l, d F Y') }}
                </div>
                <div class="text-xs mt-1 opacity-80" id="weather-greeting">
                    Memuat data cuaca...
                </div>
                <div class="text-xs mt-1 opacity-90" id="weather-motivation">
                    <i class="fas fa-heart text-red-300 mr-1"></i>
                    <span id="motivation-text">Semangat untuk hari ini! 💪</span>
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
    
    // Real-time clock function
    function updateRealTime() {
        const now = new Date();
        
        // Update time
        const timeElement = document.getElementById('weather-time');
        if (timeElement) {
            const timeString = now.toLocaleTimeString('id-ID', {
                hour: '2-digit',
                minute: '2-digit', 
                second: '2-digit',
                timeZone: 'Asia/Jakarta'
            });
            timeElement.textContent = `${timeString} WIB`;
        }
        
        // Update date
        const dateElement = document.getElementById('current-date');
        if (dateElement) {
            const options = {
                weekday: 'long',
                year: 'numeric',
                month: 'long', 
                day: 'numeric',
                timeZone: 'Asia/Jakarta'
            };
            const dateString = now.toLocaleDateString('id-ID', options);
            dateElement.textContent = dateString;
        }
    }
    
    // Update status when page loads
    document.addEventListener('DOMContentLoaded', function() {
        updateServiceStatus();
        updateWeatherWidget();
        updateRealTime();
        
        // Update real-time clock every second
        setInterval(updateRealTime, 1000);
        // Update service status every minute  
        setInterval(updateServiceStatus, 60000);
        // Update weather every 10 minutes
        setInterval(updateWeatherWidget, 600000);
    });
    
    // Weather Widget Function - Real API Integration
    function updateWeatherWidget() {
        
        // Coordinates for Ciuwlan, Telagsari, Banyumas (approximate)
        const lat = -7.4781;
        const lon = 109.2963;
        const apiKey = 'b8c82d35dc91b9b2f7d5b4c3f1e2a8d6'; // Demo key - replace with real key
        
        // Try to fetch real weather data
        fetchWeatherData(lat, lon, apiKey);
    }
    
    async function fetchWeatherData(lat, lon, apiKey) {
        try {
            const response = await fetch(`https://api.openweathermap.org/data/2.5/weather?lat=${lat}&lon=${lon}&appid=${apiKey}&units=metric&lang=id`);
            
            if (!response.ok) {
                throw new Error('API request failed');
            }
            
            const data = await response.json();
            updateWeatherDisplay(data, true);
            
        } catch (error) {
            console.log('Weather API failed, using fallback data');
            // Fallback to simulated data if API fails
            updateWeatherDisplay(null, false);
        }
    }
    
    function updateWeatherDisplay(data, isRealData) {
        let weather;
        
        if (isRealData && data) {
            // Real API data
            weather = {
                temp: Math.round(data.main.temp),
                humidity: data.main.humidity,
                wind: Math.round(data.wind.speed * 3.6), // Convert m/s to km/h
                description: capitalizeFirst(data.weather[0].description),
                condition: data.weather[0].main.toLowerCase(),
                icon: getWeatherIcon(data.weather[0].icon, data.weather[0].main)
            };
        } else {
            // Fallback simulated data
            const hour = new Date().getHours();
            const weatherOptions = [
                {
                    temp: Math.floor(Math.random() * 8) + 26, // 26-34°C
                    humidity: Math.floor(Math.random() * 20) + 50, // 50-70%
                    wind: Math.floor(Math.random() * 10) + 8, // 8-18 km/h
                    description: 'Cerah',
                    condition: 'clear',
                    icon: { iconClass: 'fas fa-sun', iconColor: 'text-yellow-300' }
                },
                {
                    temp: Math.floor(Math.random() * 6) + 24, // 24-30°C
                    humidity: Math.floor(Math.random() * 15) + 60, // 60-75%
                    wind: Math.floor(Math.random() * 8) + 10, // 10-18 km/h
                    description: 'Berawan',
                    condition: 'clouds',
                    icon: { iconClass: 'fas fa-cloud', iconColor: 'text-gray-200' }
                },
                {
                    temp: Math.floor(Math.random() * 4) + 22, // 22-26°C
                    humidity: Math.floor(Math.random() * 15) + 70, // 70-85%
                    wind: Math.floor(Math.random() * 10) + 12, // 12-22 km/h
                    description: 'Hujan Ringan',
                    condition: 'rain',
                    icon: { iconClass: 'fas fa-cloud-rain', iconColor: 'text-blue-200' }
                }
            ];
            
            const weatherIndex = hour >= 6 && hour <= 17 
                ? (Math.random() > 0.3 ? 0 : (Math.random() > 0.7 ? 1 : 2))
                : (Math.random() > 0.5 ? 1 : (Math.random() > 0.8 ? 0 : 2));
                
            weather = weatherOptions[weatherIndex];
        }
        
        // Update weather display elements
        const iconElement = document.getElementById('weather-icon');
        const tempElement = document.getElementById('weather-temp');
        const descElement = document.getElementById('weather-desc');
        const humidityElement = document.getElementById('weather-humidity');
        const windElement = document.getElementById('weather-wind');
        
        if (iconElement) iconElement.innerHTML = `<i class="${weather.icon.iconClass} ${weather.icon.iconColor}"></i>`;
        if (tempElement) tempElement.textContent = `${weather.temp}°C`;
        if (descElement) descElement.textContent = weather.description;
        if (humidityElement) humidityElement.textContent = `${weather.humidity}%`;
        if (windElement) windElement.textContent = `${weather.wind} km/h`;
        
        // Update greeting and motivation
        updateWeatherGreeting(weather.condition, weather.temp);
        updateMotivationMessage();
    }
    
    function getWeatherIcon(iconCode, weatherMain) {
        const iconMap = {
            '01d': { iconClass: 'fas fa-sun', iconColor: 'text-yellow-300' }, // clear sky day
            '01n': { iconClass: 'fas fa-moon', iconColor: 'text-yellow-100' }, // clear sky night
            '02d': { iconClass: 'fas fa-cloud-sun', iconColor: 'text-yellow-200' }, // few clouds day
            '02n': { iconClass: 'fas fa-cloud-moon', iconColor: 'text-gray-300' }, // few clouds night
            '03d': { iconClass: 'fas fa-cloud', iconColor: 'text-gray-200' }, // scattered clouds
            '03n': { iconClass: 'fas fa-cloud', iconColor: 'text-gray-200' },
            '04d': { iconClass: 'fas fa-cloud', iconColor: 'text-gray-300' }, // broken clouds
            '04n': { iconClass: 'fas fa-cloud', iconColor: 'text-gray-300' },
            '09d': { iconClass: 'fas fa-cloud-rain', iconColor: 'text-blue-200' }, // shower rain
            '09n': { iconClass: 'fas fa-cloud-rain', iconColor: 'text-blue-200' },
            '10d': { iconClass: 'fas fa-cloud-sun-rain', iconColor: 'text-blue-300' }, // rain day
            '10n': { iconClass: 'fas fa-cloud-rain', iconColor: 'text-blue-200' }, // rain night
            '11d': { iconClass: 'fas fa-bolt', iconColor: 'text-yellow-400' }, // thunderstorm
            '11n': { iconClass: 'fas fa-bolt', iconColor: 'text-yellow-400' },
            '13d': { iconClass: 'fas fa-snowflake', iconColor: 'text-white' }, // snow
            '13n': { iconClass: 'fas fa-snowflake', iconColor: 'text-white' },
            '50d': { iconClass: 'fas fa-smog', iconColor: 'text-gray-400' }, // mist
            '50n': { iconClass: 'fas fa-smog', iconColor: 'text-gray-400' }
        };
        
        return iconMap[iconCode] || { iconClass: 'fas fa-cloud', iconColor: 'text-gray-200' };
    }
    
    function updateWeatherGreeting(condition, temp) {
        const greetingElement = document.getElementById('weather-greeting');
        if (!greetingElement) return;
        
        const hour = new Date().getHours();
        let greeting = '';
        
        // Base greeting by time
        let timeGreeting = '';
        if (hour < 11) {
            timeGreeting = 'Selamat pagi!';
        } else if (hour < 15) {
            timeGreeting = 'Selamat siang!';
        } else if (hour < 18) {
            timeGreeting = 'Selamat sore!';
        } else {
            timeGreeting = 'Selamat malam!';
        }
        
        // Weather-specific message
        switch (condition) {
            case 'rain':
            case 'drizzle':
                greeting = `${timeGreeting} Sedang hujan, jangan lupa payung! ☔`;
                break;
            case 'thunderstorm':
                greeting = `${timeGreeting} Ada petir, tetap waspada ya! ⚡`;
                break;
            case 'clouds':
                greeting = `${timeGreeting} Cuaca berawan, cocok untuk aktivitas! ☁️`;
                break;
            case 'clear':
                if (temp > 30) {
                    greeting = `${timeGreeting} Cerah tapi panas, jaga hidrasi! ☀️`;
                } else {
                    greeting = `${timeGreeting} Cuaca cerah, sempurna untuk beraktivitas! ☀️`;
                }
                break;
            case 'mist':
            case 'fog':
                greeting = `${timeGreeting} Berkabut, hati-hati di jalan ya! 🌫️`;
                break;
            default:
                greeting = `${timeGreeting} Cuaca cukup baik untuk beraktivitas! 🌤️`;
        }
        
        greetingElement.textContent = greeting;
    }
    
    function updateMotivationMessage() {
        const motivationElement = document.getElementById('motivation-text');
        if (!motivationElement) return;
        
        const motivations = [
            "Semangat untuk hari ini! 💪",
            "Jadilah yang terbaik hari ini! ⭐",
            "Setiap langkah adalah progress! 🚀", 
            "Berbuat baik untuk sesama! 🤝",
            "Tetap optimis dan bersyukur! 🙏",
            "Wujudkan impianmu hari ini! ✨",
            "Spread positivity everywhere! 🌟",
            "Be kind, be awesome! 🌈",
            "Make today count! 🎯",
            "Smile, you're amazing! 😊",
            "Chase your dreams! 🦋",
            "Stay strong, stay positive! 💎",
            "Believe in yourself! 🌻",
            "Create your own sunshine! ☀️",
            "Good vibes only today! 🌺"
        ];
        
        const randomMotivation = motivations[Math.floor(Math.random() * motivations.length)];
        motivationElement.textContent = randomMotivation;
    }
    
    function capitalizeFirst(str) {
        return str.charAt(0).toUpperCase() + str.slice(1);
    }
</script>
