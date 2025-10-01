<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\NewsController;
use App\Http\Controllers\Frontend\UmkmController;
use App\Http\Controllers\Frontend\AgendaController;
use App\Http\Controllers\Frontend\GalleryController;
use App\Http\Controllers\Frontend\ServiceController;
use App\Http\Controllers\Frontend\ProfileController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\UserController as BackendUserController;
use App\Http\Controllers\Backend\PopulationController;
use App\Http\Controllers\Backend\NewsController as BackendNewsController;
use App\Http\Controllers\Backend\UmkmController as BackendUmkmController;
use App\Http\Controllers\Backend\BudgetController;

/*
|--------------------------------------------------------------------------
| Frontend Routes (Public)
|--------------------------------------------------------------------------
*/

// Home & Main Pages
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
Route::get('/profil-desa', [ProfileController::class, 'index'])->name('profile');
Route::get('/sejarah', [ProfileController::class, 'history'])->name('history');
Route::get('/visi-misi', [ProfileController::class, 'visionMission'])->name('vision-mission');
Route::get('/struktur-pemerintahan', [ProfileController::class, 'government'])->name('government');
Route::get('/perangkat-desa', [ProfileController::class, 'officials'])->name('officials');

// News & Information
Route::get('/berita', [NewsController::class, 'index'])->name('news.index');
Route::get('/berita/{slug}', [NewsController::class, 'show'])->name('news.show');
Route::get('/berita/kategori/{category}', [NewsController::class, 'category'])->name('news.category');
Route::get('/api/news/latest', [NewsController::class, 'getLatest'])->name('news.latest');

// Announcements
Route::get('/pengumuman', [NewsController::class, 'announcements'])->name('announcements.index');
Route::get('/pengumuman/{id}', [NewsController::class, 'announcementShow'])->name('announcements.show');

// Population & Statistics
Route::get('/data-penduduk', [HomeController::class, 'populationData'])->name('population.data');
Route::get('/statistik-penduduk', [HomeController::class, 'populationStats'])->name('population.stats');

// UMKM
Route::get('/umkm', [UmkmController::class, 'index'])->name('umkm.index');
Route::get('/umkm/{slug}', [UmkmController::class, 'show'])->name('umkm.show');
Route::get('/umkm/kategori/{category}', [UmkmController::class, 'category'])->name('umkm.category');

// Services
Route::get('/layanan-surat', [ServiceController::class, 'letters'])->name('services.letters');
Route::get('/pengajuan-surat', [ServiceController::class, 'letterRequest'])->name('services.letter-request');
Route::post('/pengajuan-surat', [ServiceController::class, 'submitLetterRequest'])->name('services.submit-letter');

// Gallery
Route::get('/galeri', [GalleryController::class, 'index'])->name('gallery.index');
Route::get('/galeri/unggulan', [GalleryController::class, 'featured'])->name('gallery.featured');
Route::get('/galeri/{id}', [GalleryController::class, 'show'])->name('gallery.show');
Route::post('/galeri/{id}/like', [GalleryController::class, 'like'])->name('gallery.like');
Route::post('/galeri/upload', [GalleryController::class, 'upload'])->name('gallery.upload');

// Agenda & Events
Route::get('/agenda', [AgendaController::class, 'index'])->name('agenda.index');
Route::get('/agenda/{id}', [AgendaController::class, 'show'])->name('agenda.show');
Route::get('/agenda/kalender/{year}/{month}', [AgendaController::class, 'calendar'])->name('agenda.calendar');

// Tourism
Route::get('/wisata', [ProfileController::class, 'tourism'])->name('tourism.index');
Route::get('/wisata/{slug}', [ProfileController::class, 'tourismShow'])->name('tourism.show');

// Budget & Finance (Public)
Route::get('/apbdes', [HomeController::class, 'budget'])->name('budget.index');
Route::get('/apbdes/anggaran', [HomeController::class, 'budgetPlan'])->name('budget.plan');
Route::get('/apbdes/realisasi', [HomeController::class, 'budgetRealization'])->name('budget.realization');
Route::get('/apbdes/laporan', [HomeController::class, 'budgetReport'])->name('budget.report');

// Contact & Support
Route::get('/kontak', [ProfileController::class, 'contact'])->name('contact');
Route::post('/kontak', [ProfileController::class, 'submitContact'])->name('contact.submit');

// Authentication Routes (if needed for frontend)
Route::get('/masuk', [HomeController::class, 'login'])->name('login');
Route::post('/masuk', [HomeController::class, 'authenticate'])->name('authenticate');
Route::get('/daftar', [HomeController::class, 'register'])->name('register');
Route::post('/daftar', [HomeController::class, 'store'])->name('register.store');
Route::get('/lupa-password', [HomeController::class, 'forgotPassword'])->name('forgot-password');
Route::post('/lupa-password', [HomeController::class, 'sendResetLink'])->name('password.email');
Route::post('/keluar', [HomeController::class, 'logout'])->name('logout');

// API Routes for AJAX calls
Route::prefix('api')->group(function () {
    Route::get('/statistics', [HomeController::class, 'getStatistics']);
    Route::get('/recent-news', [NewsController::class, 'getRecentNews']);
    Route::get('/recent-gallery', [GalleryController::class, 'getRecentGallery']);
    Route::get('/upcoming-agenda', [AgendaController::class, 'getUpcomingAgenda']);
    Route::get('/agenda/calendar/{year}/{month}', [AgendaController::class, 'getCalendar']);
    Route::get('/agenda/{id}', [AgendaController::class, 'getAgendaDetail']);
    Route::get('/announcements', [NewsController::class, 'getAnnouncements']);
    Route::get('/umkm-search', [UmkmController::class, 'search']);
    Route::get('/umkm-filter', [UmkmController::class, 'filterUmkm']);
    Route::get('/news-search', [NewsController::class, 'search']);
    Route::get('/locations', [HomeController::class, 'getLocations']);
});

/*
|--------------------------------------------------------------------------
| Backend Routes (Admin Panel)
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->middleware(['auth', 'verified'])->group(function () {
    
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    
    // User Management
    Route::resource('users', BackendUserController::class);
    Route::post('users/{user}/toggle-status', [BackendUserController::class, 'toggleStatus'])->name('users.toggle-status');
    Route::delete('users/{user}/force-delete', [BackendUserController::class, 'forceDelete'])->name('users.force-delete');
    
    // Population Data Management
    Route::resource('population', PopulationController::class);
    Route::get('population/import/template', [PopulationController::class, 'downloadTemplate'])->name('population.template');
    Route::post('population/import', [PopulationController::class, 'import'])->name('population.import');
    Route::get('population/export', [PopulationController::class, 'export'])->name('population.export');
    Route::post('population/bulk-delete', [PopulationController::class, 'bulkDelete'])->name('population.bulk-delete');
    
    // News Management
    Route::resource('news', BackendNewsController::class);
    Route::post('news/{news}/toggle-status', [BackendNewsController::class, 'toggleStatus'])->name('news.toggle-status');
    Route::post('news/{news}/feature', [BackendNewsController::class, 'toggleFeatured'])->name('news.toggle-featured');
    
    // Announcements Management
    Route::resource('announcements', BackendNewsController::class, ['as' => 'announcements']);
    Route::post('announcements/{announcement}/toggle-status', [BackendNewsController::class, 'toggleAnnouncementStatus'])->name('announcements.toggle-status');
    
    // UMKM Management
    Route::resource('umkm', BackendUmkmController::class);
    Route::post('umkm/{umkm}/toggle-status', [BackendUmkmController::class, 'toggleStatus'])->name('umkm.toggle-status');
    Route::post('umkm/{umkm}/verify', [BackendUmkmController::class, 'verify'])->name('umkm.verify');
    
    // Gallery Management
    Route::resource('gallery', \App\Http\Controllers\Backend\GalleryController::class);
    Route::post('gallery/{gallery}/toggle-status', [\App\Http\Controllers\Backend\GalleryController::class, 'toggleStatus'])->name('gallery.toggle-status');
    Route::post('gallery/bulk-delete', [\App\Http\Controllers\Backend\GalleryController::class, 'bulkDelete'])->name('gallery.bulk-delete');
    
    // Agenda Management
    Route::resource('agenda', \App\Http\Controllers\Backend\AgendaController::class);
    Route::post('agenda/{agenda}/toggle-status', [\App\Http\Controllers\Backend\AgendaController::class, 'toggleStatus'])->name('agenda.toggle-status');
    
    // Budget Management
    Route::resource('budget', BudgetController::class);
    Route::get('budget/transactions/{budget}', [BudgetController::class, 'transactions'])->name('budget.transactions');
    Route::post('budget/{budget}/add-transaction', [BudgetController::class, 'addTransaction'])->name('budget.add-transaction');
    Route::delete('budget/transactions/{transaction}', [BudgetController::class, 'deleteTransaction'])->name('budget.delete-transaction');
    Route::get('budget/reports/summary', [BudgetController::class, 'reportSummary'])->name('budget.report-summary');
    Route::get('budget/export/{budget}', [BudgetController::class, 'export'])->name('budget.export');
    
    // Letter Request Management
    Route::get('letter-requests', [\App\Http\Controllers\Backend\ServiceController::class, 'letterRequests'])->name('letter-requests.index');
    Route::get('letter-requests/{letterRequest}', [\App\Http\Controllers\Backend\ServiceController::class, 'showLetterRequest'])->name('letter-requests.show');
    Route::post('letter-requests/{letterRequest}/process', [\App\Http\Controllers\Backend\ServiceController::class, 'processLetterRequest'])->name('letter-requests.process');
    Route::post('letter-requests/{letterRequest}/complete', [\App\Http\Controllers\Backend\ServiceController::class, 'completeLetterRequest'])->name('letter-requests.complete');
    Route::post('letter-requests/{letterRequest}/reject', [\App\Http\Controllers\Backend\ServiceController::class, 'rejectLetterRequest'])->name('letter-requests.reject');
    
    // Tourism Management
    Route::resource('tourism', \App\Http\Controllers\Backend\TourismController::class);
    Route::post('tourism/{tourism}/toggle-status', [\App\Http\Controllers\Backend\TourismController::class, 'toggleStatus'])->name('tourism.toggle-status');
    
    // Banner Management
    Route::resource('banners', \App\Http\Controllers\Backend\BannerController::class);
    Route::post('banners/{banner}/toggle-status', [\App\Http\Controllers\Backend\BannerController::class, 'toggleStatus'])->name('banners.toggle-status');
    Route::post('banners/reorder', [\App\Http\Controllers\Backend\BannerController::class, 'reorder'])->name('banners.reorder');
    
    // Village Profile Management
    Route::get('village-profile', [\App\Http\Controllers\Backend\VillageController::class, 'profile'])->name('village.profile');
    Route::put('village-profile', [\App\Http\Controllers\Backend\VillageController::class, 'updateProfile'])->name('village.update-profile');
    Route::get('village-officials', [\App\Http\Controllers\Backend\VillageController::class, 'officials'])->name('village.officials');
    Route::post('village-officials', [\App\Http\Controllers\Backend\VillageController::class, 'storeOfficial'])->name('village.store-official');
    Route::put('village-officials/{official}', [\App\Http\Controllers\Backend\VillageController::class, 'updateOfficial'])->name('village.update-official');
    Route::delete('village-officials/{official}', [\App\Http\Controllers\Backend\VillageController::class, 'deleteOfficial'])->name('village.delete-official');
    
    // Statistics & Reports
    Route::get('statistics', [DashboardController::class, 'statistics'])->name('statistics');
    Route::get('reports', [DashboardController::class, 'reports'])->name('reports');
    Route::get('reports/export', [DashboardController::class, 'exportReports'])->name('reports.export');
    
    // Settings
    Route::get('settings', [\App\Http\Controllers\Backend\SettingController::class, 'index'])->name('settings.index');
    Route::put('settings', [\App\Http\Controllers\Backend\SettingController::class, 'update'])->name('settings.update');
    
    // File Management
    Route::post('upload-image', [\App\Http\Controllers\Backend\FileController::class, 'uploadImage'])->name('upload.image');
    Route::post('upload-document', [\App\Http\Controllers\Backend\FileController::class, 'uploadDocument'])->name('upload.document');
    Route::delete('delete-file/{path}', [\App\Http\Controllers\Backend\FileController::class, 'deleteFile'])->name('delete.file');
    
    // Backup & Maintenance
    Route::get('backup', [\App\Http\Controllers\Backend\MaintenanceController::class, 'backup'])->name('backup.index');
    Route::post('backup/create', [\App\Http\Controllers\Backend\MaintenanceController::class, 'createBackup'])->name('backup.create');
    Route::get('backup/download/{file}', [\App\Http\Controllers\Backend\MaintenanceController::class, 'downloadBackup'])->name('backup.download');
    Route::delete('backup/{file}', [\App\Http\Controllers\Backend\MaintenanceController::class, 'deleteBackup'])->name('backup.delete');
});
