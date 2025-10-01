<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\NewsController;
use App\Http\Controllers\Frontend\UmkmController;
use App\Http\Controllers\Frontend\AgendaController;
use App\Http\Controllers\Frontend\GalleryController;
use App\Http\Controllers\Frontend\ServiceController;
use App\Http\Controllers\Frontend\ProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Backend\UserController as BackendUserController;
use App\Http\Controllers\Backend\PopulationController;
use App\Http\Controllers\Backend\NewsController as BackendNewsController;
use App\Http\Controllers\Backend\UmkmController as BackendUmkmController;
use App\Http\Controllers\Backend\BudgetController;
use App\Http\Controllers\Backend\ContactController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\NewsController as AdminNewsController;
use App\Http\Controllers\Admin\SettingsController;

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

// Login Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate'])->name('login.authenticate');

// Registration Routes
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'store'])->name('register.store');

// Password Reset Routes
Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'updatePassword'])->name('password.store');

// Logout Route
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Profile Routes (Authenticated Users)
Route::middleware(['auth', 'gate:account-active'])->group(function () {
    Route::get('/my-profile', [AuthController::class, 'profile'])->name('user.profile');
    Route::put('/my-profile', [AuthController::class, 'updateProfile'])->name('user.profile.update');
    Route::put('/my-profile/password', [AuthController::class, 'changePassword'])->name('user.password.change');
});

/*
|--------------------------------------------------------------------------
| Frontend Routes (Public)
|--------------------------------------------------------------------------
*/

// Home & Main Pages
Route::get('/', [HomeController::class, 'index'])->name('home');
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

// Legacy authentication routes (redirect to new auth system)
Route::get('/masuk', function() { return redirect()->route('login'); });
Route::get('/daftar', function() { return redirect()->route('register'); });
Route::get('/lupa-password', function() { return redirect()->route('password.request'); });

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

Route::prefix('admin')->name('backend.')->middleware(['auth', 'role:admin,super_admin', 'gate:access-admin-panel'])->group(function () {
    
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    
    // Permission Management
    Route::get('/permissions', [\App\Http\Controllers\Backend\PermissionController::class, 'index'])->name('permissions.index');
    Route::post('/permissions/role', [\App\Http\Controllers\Backend\PermissionController::class, 'updateRolePermissions'])->name('permissions.update-role');
    Route::post('/permissions/user', [\App\Http\Controllers\Backend\PermissionController::class, 'updateUserPermissions'])->name('permissions.update-user');
    Route::get('/permissions/test', [\App\Http\Controllers\Backend\PermissionController::class, 'testPermissions'])->name('permissions.test');
    
    // Legacy Permissions Test Route
    Route::get('/test-permissions', [\App\Http\Controllers\Backend\PermissionsTestController::class, 'testPermissions'])->name('test-permissions');
    Route::get('/system-info', [DashboardController::class, 'getSystemInfo'])->name('system-info');
    
    // User Management (New Admin System)
    Route::resource('users', AdminUserController::class);
    Route::patch('users/{user}/status', [AdminUserController::class, 'updateStatus'])->name('users.update-status');
    Route::post('users/bulk-action', [AdminUserController::class, 'bulkAction'])->name('users.bulk-action');
    Route::get('users-export', [AdminUserController::class, 'export'])->name('users.export');
    Route::get('role-permissions', [AdminUserController::class, 'getRolePermissions'])->name('users.role-permissions');
    
    // Legacy User Management (Keep for compatibility)
    Route::post('users/{user}/toggle-status', [BackendUserController::class, 'toggleStatus'])->name('users.toggle-status-legacy');
    Route::delete('users/{user}/force-delete', [BackendUserController::class, 'forceDelete'])->name('users.force-delete');
    
    // Population Data Management
    Route::resource('population', PopulationController::class);
    Route::get('population/statistics', [PopulationController::class, 'statistics'])->name('population.statistics');
    Route::get('population/import/template', [PopulationController::class, 'downloadTemplate'])->name('population.template');
    Route::post('population/import', [PopulationController::class, 'import'])->name('population.import');
    Route::get('population/export', [PopulationController::class, 'export'])->name('population.export');
    Route::post('population/bulk-delete', [PopulationController::class, 'bulkDelete'])->name('population.bulk-delete');
    
    // News Management (New Admin System)
    Route::resource('news', AdminNewsController::class);
    Route::patch('news/{news}/status', [AdminNewsController::class, 'updateStatus'])->name('news.update-status');
    Route::post('news/bulk-action', [AdminNewsController::class, 'bulkAction'])->name('news.bulk-action');
    Route::get('news-export', [AdminNewsController::class, 'export'])->name('news.export');
    
    // Legacy News Management (Keep for compatibility)
    Route::post('news/{news}/toggle-status', [BackendNewsController::class, 'toggleStatus'])->name('news.toggle-status-legacy');
    Route::post('news/{news}/feature', [BackendNewsController::class, 'toggleFeatured'])->name('news.toggle-featured');
    
    // Announcements Management
    Route::resource('announcements', \App\Http\Controllers\Backend\AnnouncementController::class);
    Route::post('announcements/{announcement}/toggle-status', [\App\Http\Controllers\Backend\AnnouncementController::class, 'toggleStatus'])->name('announcements.toggle-status');
    Route::post('announcements/bulk-action', [\App\Http\Controllers\Backend\AnnouncementController::class, 'bulkAction'])->name('announcements.bulk-action');
    Route::get('announcements/active', [\App\Http\Controllers\Backend\AnnouncementController::class, 'getActiveAnnouncements'])->name('announcements.active');
    
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
    
    // Contact Message Management
    Route::resource('contact', \App\Http\Controllers\Backend\ContactController::class)->only(['index', 'show', 'destroy']);
    Route::get('contact/{contact}/reply', [\App\Http\Controllers\Backend\ContactController::class, 'reply'])->name('contact.reply');
    Route::post('contact/{contact}/reply', [\App\Http\Controllers\Backend\ContactController::class, 'storeReply'])->name('contact.store-reply');
    Route::patch('contact/{contact}/status', [\App\Http\Controllers\Backend\ContactController::class, 'updateStatus'])->name('contact.update-status');
    Route::post('contact/bulk-action', [\App\Http\Controllers\Backend\ContactController::class, 'bulkAction'])->name('contact.bulk-action');
    Route::get('contact-stats', [\App\Http\Controllers\Backend\ContactController::class, 'getStats'])->name('contact.stats');
    
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
    
    // Settings Management (New Admin System)
    Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::put('settings', [SettingsController::class, 'update'])->name('settings.update');
    Route::post('settings/test-email', [SettingsController::class, 'testEmail'])->name('settings.test-email');
    
    // Legacy Settings (Keep for compatibility)
    Route::get('legacy-settings', [\App\Http\Controllers\Backend\SettingController::class, 'index'])->name('legacy-settings.index');
    Route::put('legacy-settings', [\App\Http\Controllers\Backend\SettingController::class, 'update'])->name('legacy-settings.update');
    
    // File Management
    Route::post('upload-image', [\App\Http\Controllers\Backend\FileController::class, 'uploadImage'])->name('upload.image');
    Route::post('upload-document', [\App\Http\Controllers\Backend\FileController::class, 'uploadDocument'])->name('upload.document');
    Route::delete('delete-file/{path}', [\App\Http\Controllers\Backend\FileController::class, 'deleteFile'])->name('delete.file');
    
    // Backup Management (New Admin System)
    Route::get('backup', [SettingsController::class, 'backupIndex'])->name('backup.index');
    Route::post('backup/create', [SettingsController::class, 'createBackup'])->name('backup.create');
    Route::delete('backup/{file}', [SettingsController::class, 'deleteBackup'])->name('backup.delete');
    Route::get('backup/{file}/download', [SettingsController::class, 'downloadBackup'])->name('backup.download');
    
    // Activity Logs
    Route::get('activity-logs', [DashboardController::class, 'activityLogs'])->name('activity-logs');
    Route::get('logs', [DashboardController::class, 'logs'])->name('logs');
    Route::post('logs/clear', [DashboardController::class, 'clearLogs'])->name('logs.clear');
    
    // Legacy Backup & Maintenance (Keep for compatibility)
    Route::get('legacy-backup', [\App\Http\Controllers\Backend\MaintenanceController::class, 'backup'])->name('legacy-backup.index');
    Route::post('legacy-backup/create', [\App\Http\Controllers\Backend\MaintenanceController::class, 'createBackup'])->name('legacy-backup.create');
});
