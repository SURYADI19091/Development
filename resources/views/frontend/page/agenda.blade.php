@extends('frontend.main')

@section('title', 'Agenda Kegiatan - ' . strtoupper($villageProfile->village_name ?? 'Desa Krandegan'))
@section('page_title', 'AGENDA KEGIATAN')
@section('header_icon', 'fas fa-calendar-alt')
@section('header_bg_color', 'bg-indigo-600')

@section('content')
<div class="xl:col-span-3">
    <!-- Calendar View Toggle -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="flex items-center space-x-4">
                <h2 class="text-xl font-bold text-gray-900">Kalender Kegiatan</h2>
                <span class="bg-indigo-100 text-indigo-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                    September 2025
                </span>
            </div>
            <div class="flex items-center space-x-2">
                <button id="listView" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition duration-200">
                    <i class="fas fa-list mr-2"></i>List
                </button>
                <button id="calendarView" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition duration-200">
                    <i class="fas fa-calendar mr-2"></i>Kalender
                </button>
            </div>
        </div>
    </div>

    <!-- Statistics Section -->
    <div class="mb-8 grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Total Agenda</p>
                    <p class="text-3xl font-bold">{{ $totalAgendas }}</p>
                </div>
                <div class="bg-blue-400 bg-opacity-50 p-3 rounded-full">
                    <i class="fas fa-calendar text-2xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Agenda Aktif</p>
                    <p class="text-3xl font-bold">{{ $activeAgendas }}</p>
                </div>
                <div class="bg-green-400 bg-opacity-50 p-3 rounded-full">
                    <i class="fas fa-calendar-check text-2xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium">Bulan Ini</p>
                    <p class="text-3xl font-bold">{{ $thisMonthAgendas }}</p>
                </div>
                <div class="bg-purple-400 bg-opacity-50 p-3 rounded-full">
                    <i class="fas fa-calendar-alt text-2xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-orange-100 text-sm font-medium">Hari Ini</p>
                    <p class="text-3xl font-bold">{{ $todayEvents->count() }}</p>
                </div>
                <div class="bg-orange-400 bg-opacity-50 p-3 rounded-full">
                    <i class="fas fa-calendar-day text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Today's Events -->
    <div class="bg-gradient-to-r from-blue-500 to-purple-600 text-white rounded-lg shadow-lg p-6 mb-6">
        <div class="flex items-center mb-4">
            <i class="fas fa-calendar-day text-2xl mr-3"></i>
            <div>
                <h2 class="text-xl font-bold">Hari Ini</h2>
                <p class="opacity-90">{{ now()->locale('id')->translatedFormat('l, d F Y') }}</p>
            </div>
        </div>
        <div class="space-y-3">
            @forelse($todayEvents as $event)
            <div class="bg-white/10 rounded-lg p-4">
                <div class="flex items-start justify-between">
                    <div>
                        <h3 class="font-bold mb-1">{{ $event->title }}</h3>
                        <p class="text-sm opacity-90 mb-2">
                            {{ Str::limit($event->description, 80) }}
                        </p>
                        <div class="flex items-center text-sm">
                            <i class="fas fa-clock mr-2"></i>
                            <span>{{ $event->formatted_time }}</span>
                            <span class="mx-2">•</span>
                            <i class="fas fa-map-marker-alt mr-1"></i>
                            <span>{{ $event->location }}</span>
                        </div>
                    </div>
                    @if($event->is_ongoing)
                    <span class="bg-yellow-500 text-white text-xs px-2 py-1 rounded-full">Sedang Berlangsung</span>
                    @else
                    <span class="bg-green-500 text-white text-xs px-2 py-1 rounded-full">Hari Ini</span>
                    @endif
                </div>
            </div>
            @empty
            <div class="bg-white/10 rounded-lg p-4 text-center">
                <p class="text-sm opacity-90">Tidak ada kegiatan hari ini</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Upcoming Events -->
    <div class="space-y-4 mb-6" id="eventsList">
        @forelse($upcomingEvents as $event)
        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-{{ $event->category_color }}-500">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <div class="flex items-center mb-3">
                        <span class="bg-{{ $event->category_color }}-100 text-{{ $event->category_color }}-800 text-xs font-medium px-2.5 py-0.5 rounded-full mr-3">
                            {{ $event->category_label }}
                        </span>
                        <span class="text-sm text-gray-500">{{ $event->formatted_date }}</span>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">
                        {{ $event->title }}
                    </h3>
                    <p class="text-gray-700 mb-3">
                        {{ $event->description }}
                    </p>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 text-sm text-gray-600">
                        <div class="flex items-center">
                            <i class="fas fa-clock mr-2 text-{{ $event->category_color }}-500"></i>
                            <span>{{ $event->formatted_time }}</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-map-marker-alt mr-2 text-{{ $event->category_color }}-500"></i>
                            <span>{{ $event->location }}</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-users mr-2 text-{{ $event->category_color }}-500"></i>
                            <span>
                                @if($event->max_participants)
                                    Max {{ $event->max_participants }} peserta
                                @else
                                    {{ $event->requirements ?: 'Terbuka untuk umum' }}
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
                <div class="ml-4">
                    <button class="px-4 py-2 bg-{{ $event->category_color }}-100 text-{{ $event->category_color }}-700 rounded-lg hover:bg-{{ $event->category_color }}-200 transition duration-200 reminder-btn" data-event="{{ $event->title }}">
                        <i class="fas fa-bell mr-2"></i>Ingatkan
                    </button>
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-lg shadow-lg p-6 text-center">
            <p class="text-gray-500">Belum ada kegiatan yang akan datang</p>
        </div>
        @endforelse


    </div>

    <!-- Calendar View (Hidden by default) -->
    <div id="calendarContainer" class="bg-white rounded-lg shadow-lg p-6 hidden">
        <div class="calendar-header flex items-center justify-between mb-6">
            <button id="prevMonth" class="p-2 hover:bg-gray-100 rounded-lg">
                <i class="fas fa-chevron-left"></i>
            </button>
            <h3 id="currentMonth" class="text-xl font-bold text-gray-900">September 2025</h3>
            <button id="nextMonth" class="p-2 hover:bg-gray-100 rounded-lg">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>
        
        <div class="grid grid-cols-7 gap-1 mb-4">
            <div class="p-2 text-center font-medium text-gray-500">Min</div>
            <div class="p-2 text-center font-medium text-gray-500">Sen</div>
            <div class="p-2 text-center font-medium text-gray-500">Sel</div>
            <div class="p-2 text-center font-medium text-gray-500">Rab</div>
            <div class="p-2 text-center font-medium text-gray-500">Kam</div>
            <div class="p-2 text-center font-medium text-gray-500">Jum</div>
            <div class="p-2 text-center font-medium text-gray-500">Sab</div>
        </div>
        
        <div id="calendarGrid" class="grid grid-cols-7 gap-1">
            <!-- Calendar days will be generated by JavaScript -->
        </div>
    </div>

    <!-- Filters and Actions -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="flex flex-col md:flex-row gap-4">
                <!-- Category Filter -->
                <select id="categoryFilter" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">Semua Kategori</option>
                    <option value="rapat">Rapat</option>
                    <option value="pelayanan">Pelayanan</option>
                    <option value="olahraga">Olahraga</option>
                    <option value="gotong-royong">Gotong Royong</option>
                    <option value="keagamaan">Keagamaan</option>
                </select>
                
                <!-- Month Filter -->
                <select id="monthFilter" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">Bulan Ini</option>
                    <option value="09">September 2025</option>
                    <option value="10">Oktober 2025</option>
                    <option value="11">November 2025</option>
                    <option value="12">Desember 2025</option>
                </select>
            </div>
            
            <div class="flex items-center space-x-4">
                <button id="exportPdfBtn" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition duration-200">
                    <i class="fas fa-download mr-2"></i>
                    Export PDF
                </button>
                
                <button id="proposeEventBtn" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition duration-200">
                    <i class="fas fa-plus mr-2"></i>
                    Usul Kegiatan
                </button>
            </div>
        </div>
        
        <!-- Statistics -->
        <div class="mt-6 pt-6 border-t">
            <h3 class="font-bold text-gray-900 mb-4">Statistik Kegiatan</h3>
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                <div class="text-center p-3 bg-indigo-50 rounded-lg">
                    <div class="text-2xl font-bold text-indigo-600">12</div>
                    <div class="text-sm text-gray-600">Bulan Ini</div>
                </div>
                <div class="text-center p-3 bg-green-50 rounded-lg">
                    <div class="text-2xl font-bold text-green-600">8</div>
                    <div class="text-sm text-gray-600">Terlaksana</div>
                </div>
                <div class="text-center p-3 bg-yellow-50 rounded-lg">
                    <div class="text-2xl font-bold text-yellow-600">3</div>
                    <div class="text-sm text-gray-600">Mendatang</div>
                </div>
                <div class="text-center p-3 bg-red-50 rounded-lg">
                    <div class="text-2xl font-bold text-red-600">1</div>
                    <div class="text-sm text-gray-600">Dibatalkan</div>
                </div>
                <div class="text-center p-3 bg-purple-50 rounded-lg">
                    <div class="text-2xl font-bold text-purple-600">89%</div>
                    <div class="text-sm text-gray-600">Partisipasi</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Event Detail Modal -->
<div id="eventModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between p-4 border-b">
                <h3 class="text-lg font-semibold text-gray-900" id="modalTitle">Detail Event</h3>
                <button id="closeModal" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="p-4" id="modalContent">
                <!-- Content will be populated by JavaScript -->
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // View toggle functionality
    const listViewBtn = document.getElementById('listView');
    const calendarViewBtn = document.getElementById('calendarView');
    const eventsList = document.getElementById('eventsList');
    const calendarContainer = document.getElementById('calendarContainer');

    listViewBtn.addEventListener('click', function() {
        // Show list view
        eventsList.classList.remove('hidden');
        calendarContainer.classList.add('hidden');
        
        // Update button states
        listViewBtn.classList.add('bg-indigo-600', 'text-white');
        listViewBtn.classList.remove('bg-gray-200', 'text-gray-700');
        calendarViewBtn.classList.remove('bg-indigo-600', 'text-white');
        calendarViewBtn.classList.add('bg-gray-200', 'text-gray-700');
    });

    calendarViewBtn.addEventListener('click', function() {
        // Show calendar view
        eventsList.classList.add('hidden');
        calendarContainer.classList.remove('hidden');
        
        // Update button states
        calendarViewBtn.classList.add('bg-indigo-600', 'text-white');
        calendarViewBtn.classList.remove('bg-gray-200', 'text-gray-700');
        listViewBtn.classList.remove('bg-indigo-600', 'text-white');
        listViewBtn.classList.add('bg-gray-200', 'text-gray-700');
        
        // Generate calendar
        generateCalendar();
    });

    // Calendar state
    let currentYear = 2025;
    let currentMonth = 9; // October (0-indexed)

    // Calendar navigation
    document.getElementById('prevMonth').addEventListener('click', function() {
        currentMonth--;
        if (currentMonth < 0) {
            currentMonth = 11;
            currentYear--;
        }
        generateCalendar(currentYear, currentMonth);
    });

    document.getElementById('nextMonth').addEventListener('click', function() {
        currentMonth++;
        if (currentMonth > 11) {
            currentMonth = 0;
            currentYear++;
        }
        generateCalendar(currentYear, currentMonth);
    });

    // Calendar generation
    function generateCalendar(year = currentYear, month = currentMonth) {
        const calendarGrid = document.getElementById('calendarGrid');
        const currentMonthElement = document.getElementById('currentMonth');
        
        const months = [
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];
        
        currentMonthElement.textContent = `${months[month]} ${year}`;
        
        // Update global state
        currentYear = year;
        currentMonth = month;
        
        // Clear previous calendar
        calendarGrid.innerHTML = '';
        
        // Get first day of month and number of days
        const firstDay = new Date(year, month, 1).getDay();
        const daysInMonth = new Date(year, month + 1, 0).getDate();
        
        // Get events from database via API
        const events = {};
        
        // Load events for this month from server
        fetch(`/api/agenda/calendar/${year}/${month + 1}`)
            .then(response => response.json())
            .then(data => {
                Object.assign(events, data.events);
                renderCalendarDays();
            })
            .catch(error => {
                console.error('Error loading events:', error);
                // Fallback to demo data
                const demoEvents = {
                    1: [{ id: 1, type: 'rapat', title: 'Rapat Koordinasi', description: 'Rapat koordinasi bulanan perangkat desa' }],
                    2: [{ id: 2, type: 'rapat', title: 'Musdes RKP 2026', description: 'Musyawarah Desa Rencana Kerja Pemerintah 2026' }],
                    3: [{ id: 3, type: 'olahraga', title: 'Turnamen Badminton', description: 'Turnamen bulu tangkis antar RT' }],
                    10: [{ id: 4, type: 'keagamaan', title: 'Maulid Nabi', description: 'Peringatan Maulid Nabi Muhammad SAW' }]
                };
                Object.assign(events, demoEvents);
                renderCalendarDays();
            });
        
        function renderCalendarDays() {
        
        // Add empty cells for days before the first day of the month
        for (let i = 0; i < firstDay; i++) {
            const emptyDay = document.createElement('div');
            emptyDay.className = 'p-2 h-20';
            calendarGrid.appendChild(emptyDay);
        }
        
            // Add days of the month
            for (let day = 1; day <= daysInMonth; day++) {
                const dayElement = document.createElement('div');
                dayElement.className = 'p-2 h-20 border border-gray-200 hover:bg-gray-50 cursor-pointer relative';
                dayElement.dataset.date = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                
                const dayNumber = document.createElement('div');
                dayNumber.className = 'font-medium text-gray-900';
                dayNumber.textContent = day;
                dayElement.appendChild(dayNumber);
                
                // Add events for this day
                if (events[day]) {
                    events[day].forEach(event => {
                        const eventElement = document.createElement('div');
                        eventElement.className = `text-xs p-1 rounded mt-1 ${getEventColor(event.type)} cursor-pointer hover:opacity-80`;
                        eventElement.textContent = event.title;
                        eventElement.dataset.eventId = event.id;
                        dayElement.appendChild(eventElement);
                    });
                }
                
                // Highlight today
                const today = new Date();
                if (year === today.getFullYear() && month === today.getMonth() && day === today.getDate()) {
                    dayNumber.className += ' bg-indigo-600 text-white rounded-full w-6 h-6 flex items-center justify-center';
                }
                
                // Add click handler for date
                dayElement.addEventListener('click', function(e) {
                    const clickedDate = this.dataset.date;
                    const dayEvents = events[day] || [];
                    showEventModal(clickedDate, dayEvents);
                });
                
                calendarGrid.appendChild(dayElement);
            }
        }
    }

    }

    function getEventColor(type) {
        const colors = {
            'rapat': 'bg-green-100 text-green-800',
            'pelayanan': 'bg-blue-100 text-blue-800',
            'olahraga': 'bg-purple-100 text-purple-800',
            'gotong-royong': 'bg-yellow-100 text-yellow-800',
            'keagamaan': 'bg-red-100 text-red-800',
            'lainnya': 'bg-gray-100 text-gray-800'
        };
        return colors[type] || 'bg-gray-100 text-gray-800';
    }

    // Modal functionality
    function showEventModal(date, events) {
        const modal = document.getElementById('eventModal');
        const modalTitle = document.getElementById('modalTitle');
        const modalContent = document.getElementById('modalContent');
        
        // Format date
        const formattedDate = new Date(date).toLocaleDateString('id-ID', {
            weekday: 'long',
            year: 'numeric',
            month: 'long', 
            day: 'numeric'
        });
        
        modalTitle.textContent = `Event - ${formattedDate}`;
        
        if (events.length > 0) {
            modalContent.innerHTML = events.map(event => `
                <div class="mb-4 p-4 border rounded-lg">
                    <div class="flex items-center mb-2">
                        <span class="inline-block px-2 py-1 text-xs font-medium rounded ${getEventColor(event.type)}">
                            ${event.type.toUpperCase()}
                        </span>
                    </div>
                    <h4 class="font-bold text-gray-900 mb-1">${event.title}</h4>
                    <p class="text-gray-600 text-sm">${event.description || 'Tidak ada deskripsi'}</p>
                </div>
            `).join('');
        } else {
            modalContent.innerHTML = `
                <div class="text-center py-8">
                    <i class="fas fa-calendar-times text-4xl text-gray-400 mb-4"></i>
                    <p class="text-gray-600">Tidak ada event pada tanggal ini</p>
                    <button class="mt-4 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                        <i class="fas fa-plus mr-2"></i>Tambah Event
                    </button>
                </div>
            `;
        }
        
        modal.classList.remove('hidden');
    }

    // Close modal
    document.getElementById('closeModal').addEventListener('click', function() {
        document.getElementById('eventModal').classList.add('hidden');
    });

    // Close modal when clicking outside
    document.getElementById('eventModal').addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.add('hidden');
        }
    });

    // Reminder functionality - using event delegation
    document.addEventListener('click', function(e) {
        if (e.target.closest('.reminder-btn')) {
            const button = e.target.closest('.reminder-btn');
            const eventTitle = button.getAttribute('data-event');
            
            // Prevent multiple clicks
            if (button.disabled) return;
            
            // Update button state
            button.innerHTML = '<i class="fas fa-check mr-2"></i>Diingatkan';
            button.classList.add('opacity-50', 'cursor-not-allowed');
            button.disabled = true;
            
            // Show success message
            const toast = document.createElement('div');
            toast.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
            toast.textContent = `Pengingat untuk "${eventTitle}" berhasil diatur!`;
            document.body.appendChild(toast);
            
            setTimeout(() => {
                if (toast.parentNode) {
                    toast.remove();
                }
            }, 3000);
        }
    });

    // Export PDF functionality - using specific ID
    const exportPdfBtn = document.getElementById('exportPdfBtn');
    if (exportPdfBtn) {
        exportPdfBtn.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('Export PDF button clicked');
            
            // Show loading toast
            const toast = document.createElement('div');
            toast.className = 'fixed top-4 right-4 bg-blue-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
            toast.textContent = 'Memproses export PDF...';
            document.body.appendChild(toast);
            
            setTimeout(() => {
                if (toast.parentNode) {
                    toast.remove();
                }
                // In a real application, this would generate and download PDF
                alert('Mengunduh kalender kegiatan dalam format PDF...');
            }, 1500);
        });
    }

    // Propose event functionality - using specific ID
    const proposeEventBtn = document.getElementById('proposeEventBtn');
    if (proposeEventBtn) {
        proposeEventBtn.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('Propose Event button clicked');
            
            // In a real application, this would open a form to propose new event
            alert('Membuka formulir usulan kegiatan...');
        });
    }

    // Filter functionality with specific IDs
    const categoryFilter = document.getElementById('categoryFilter');
    const monthFilter = document.getElementById('monthFilter');
    
    if (categoryFilter) {
        categoryFilter.addEventListener('change', function() {
            console.log('Category filter changed to:', this.value);
            
            // Show loading indicator
            const toast = document.createElement('div');
            toast.className = 'fixed top-4 right-4 bg-blue-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
            toast.textContent = `Memfilter kategori: ${this.options[this.selectedIndex].text}`;
            document.body.appendChild(toast);
            
            setTimeout(() => {
                if (toast.parentNode) {
                    toast.remove();
                }
            }, 2000);
        });
    }
    
    if (monthFilter) {
        monthFilter.addEventListener('change', function() {
            console.log('Month filter changed to:', this.value);
            
            // Show loading indicator
            const toast = document.createElement('div');
            toast.className = 'fixed top-4 right-4 bg-blue-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
            toast.textContent = `Memfilter bulan: ${this.options[this.selectedIndex].text}`;
            document.body.appendChild(toast);
            
            setTimeout(() => {
                if (toast.parentNode) {
                    toast.remove();
                }
            }, 2000);
        });
    }

    // Initialize page when DOM is loaded
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Agenda page loaded successfully');
        
        // Test all buttons are clickable
        const buttons = document.querySelectorAll('button');
        console.log(`Found ${buttons.length} buttons on page`);
        
        // Check specific elements
        console.log('List View Button:', document.getElementById('listView'));
        console.log('Calendar View Button:', document.getElementById('calendarView'));
        console.log('Export PDF Button:', document.getElementById('exportPdfBtn'));
        console.log('Propose Event Button:', document.getElementById('proposeEventBtn'));
        console.log('Category Filter:', document.getElementById('categoryFilter'));
        console.log('Month Filter:', document.getElementById('monthFilter'));
        
        // Add click test for all buttons
        buttons.forEach((button, index) => {
            if (!button.hasAttribute('data-click-listener')) {
                button.setAttribute('data-click-listener', 'true');
                button.addEventListener('click', function(e) {
                    console.log(`Button ${index + 1} clicked:`, this.textContent.trim());
                    console.log('Button ID:', this.id);
                    console.log('Button classes:', this.className);
                });
            }
        });
        
        // Test reminder buttons specifically
        const reminderButtons = document.querySelectorAll('.reminder-btn');
        console.log(`Found ${reminderButtons.length} reminder buttons`);
        reminderButtons.forEach((btn, index) => {
            console.log(`Reminder button ${index + 1}:`, btn.getAttribute('data-event'));
        });
    });
</script>
@endsection