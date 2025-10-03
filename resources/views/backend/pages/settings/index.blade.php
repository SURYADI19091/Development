@extends('backend.layout.main')

@section('title', 'Pengaturan Sistem')
@section('header', 'Pengaturan Sistem')
@section('description', 'Konfigurasi dan pengaturan website desa')

@section('content')
<div class="space-y-6">
    <!-- Settings Navigation -->
    <div class="bg-white shadow sm:rounded-lg">
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-8 px-6" aria-label="Tabs">
                <button onclick="showTab('general')" 
                        class="settings-tab active border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
                        data-tab="general">
                    <i class="fas fa-cog mr-2"></i>
                    Umum
                </button>
                <button onclick="showTab('appearance')" 
                        class="settings-tab border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
                        data-tab="appearance">
                    <i class="fas fa-palette mr-2"></i>
                    Tampilan
                </button>
                <button onclick="showTab('email')" 
                        class="settings-tab border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
                        data-tab="email">
                    <i class="fas fa-envelope mr-2"></i>
                    Email
                </button>
                <button onclick="showTab('security')" 
                        class="settings-tab border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
                        data-tab="security">
                    <i class="fas fa-shield-alt mr-2"></i>
                    Keamanan
                </button>
                <button onclick="showTab('backup')" 
                        class="settings-tab border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
                        data-tab="backup">
                    <i class="fas fa-database mr-2"></i>
                    Backup
                </button>
            </nav>
        </div>

        <form id="settings-form" action="{{ route('backend.settings.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- General Settings Tab -->
            <div id="tab-general" class="settings-content">
                <div class="px-6 py-6 space-y-6">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Website</h3>
                        
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <!-- Site Name -->
                            <div class="sm:col-span-2">
                                <label for="site_name" class="block text-sm font-medium text-gray-700">
                                    Nama Website <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="settings[site_name]" id="site_name" 
                                       value="{{ old('settings.site_name', $settings['site_name'] ?? 'Website Desa Ciuwlan') }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                <p class="mt-1 text-sm text-gray-500">Nama website yang akan ditampilkan di header</p>
                            </div>

                            <!-- Site Description -->
                            <div class="sm:col-span-2">
                                <label for="site_description" class="block text-sm font-medium text-gray-700">Deskripsi Website</label>
                                <textarea name="settings[site_description]" id="site_description" rows="3"
                                          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">{{ old('settings.site_description', $settings['site_description'] ?? 'Website resmi Desa Ciuwlan, Kecamatan Telagsari') }}</textarea>
                                <p class="mt-1 text-sm text-gray-500">Deskripsi singkat untuk SEO dan meta description</p>
                            </div>

                            <!-- Site Logo -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Logo Website</label>
                                <div class="flex items-center space-x-4">
                                    <div class="flex-shrink-0">
                                        <img id="logo-preview" class="h-16 w-16 rounded-lg object-cover border-2 border-gray-300" 
                                             src="{{ $settings['site_logo'] ? Storage::url($settings['site_logo']) : 'https://via.placeholder.com/64x64/e5e7eb/6b7280?text=LOGO' }}" 
                                             alt="Logo Preview">
                                    </div>
                                    <div>
                                        <input type="file" id="site_logo" name="site_logo" accept="image/*" class="hidden">
                                        <label for="site_logo" 
                                               class="cursor-pointer bg-white py-2 px-3 border border-gray-300 rounded-md shadow-sm text-sm leading-4 font-medium text-gray-700 hover:bg-gray-50">
                                            Pilih Logo
                                        </label>
                                        <p class="mt-1 text-xs text-gray-500">PNG, JPG, SVG up to 2MB</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Site Favicon -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Favicon</label>
                                <div class="flex items-center space-x-4">
                                    <div class="flex-shrink-0">
                                        <img id="favicon-preview" class="h-8 w-8 rounded object-cover border-2 border-gray-300" 
                                             src="{{ $settings['site_favicon'] ? Storage::url($settings['site_favicon']) : 'https://via.placeholder.com/32x32/e5e7eb/6b7280?text=F' }}" 
                                             alt="Favicon Preview">
                                    </div>
                                    <div>
                                        <input type="file" id="site_favicon" name="site_favicon" accept="image/*" class="hidden">
                                        <label for="site_favicon" 
                                               class="cursor-pointer bg-white py-2 px-3 border border-gray-300 rounded-md shadow-sm text-sm leading-4 font-medium text-gray-700 hover:bg-gray-50">
                                            Pilih Favicon
                                        </label>
                                        <p class="mt-1 text-xs text-gray-500">ICO, PNG 32x32px</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Contact Info -->
                            <div>
                                <label for="contact_email" class="block text-sm font-medium text-gray-700">Email Kontak</label>
                                <input type="email" name="settings[contact_email]" id="contact_email" 
                                       value="{{ old('settings.contact_email', $settings['contact_email'] ?? 'info@desaciuwlan.com') }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            </div>

                            <div>
                                <label for="contact_phone" class="block text-sm font-medium text-gray-700">Telepon Kontak</label>
                                <input type="tel" name="settings[contact_phone]" id="contact_phone" 
                                       value="{{ old('settings.contact_phone', $settings['contact_phone'] ?? '+62 xxx xxxx xxxx') }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            </div>

                            <!-- Address -->
                            <div class="sm:col-span-2">
                                <label for="contact_address" class="block text-sm font-medium text-gray-700">Alamat Lengkap</label>
                                <textarea name="settings[contact_address]" id="contact_address" rows="2"
                                          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">{{ old('settings.contact_address', $settings['contact_address'] ?? 'Desa Ciuwlan, Kecamatan Telagsari, Kabupaten Cirebon, Jawa Barat') }}</textarea>
                            </div>

                            <!-- Social Media -->
                            <div>
                                <label for="social_facebook" class="block text-sm font-medium text-gray-700">Facebook URL</label>
                                <input type="url" name="settings[social_facebook]" id="social_facebook" 
                                       value="{{ old('settings.social_facebook', $settings['social_facebook'] ?? '') }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            </div>

                            <div>
                                <label for="social_instagram" class="block text-sm font-medium text-gray-700">Instagram URL</label>
                                <input type="url" name="settings[social_instagram]" id="social_instagram" 
                                       value="{{ old('settings.social_instagram', $settings['social_instagram'] ?? '') }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Appearance Settings Tab -->
            <div id="tab-appearance" class="settings-content hidden">
                <div class="px-6 py-6 space-y-6">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Pengaturan Tampilan</h3>
                        
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <!-- Theme -->
                            <div>
                                <label for="theme" class="block text-sm font-medium text-gray-700">Tema Website</label>
                                <select name="settings[theme]" id="theme"
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                    <option value="default" {{ ($settings['theme'] ?? 'default') === 'default' ? 'selected' : '' }}>Default</option>
                                    <option value="blue" {{ ($settings['theme'] ?? '') === 'blue' ? 'selected' : '' }}>Blue</option>
                                    <option value="green" {{ ($settings['theme'] ?? '') === 'green' ? 'selected' : '' }}>Green</option>
                                </select>
                            </div>

                            <!-- Maintenance Mode -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Mode Maintenance</label>
                                <div class="mt-2">
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" name="settings[maintenance_mode]" value="1" 
                                               {{ ($settings['maintenance_mode'] ?? false) ? 'checked' : '' }}
                                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                        <span class="ml-2 text-sm text-gray-600">Aktifkan mode maintenance</span>
                                    </label>
                                </div>
                                <p class="mt-1 text-xs text-gray-500">Website tidak dapat diakses publik saat mode maintenance aktif</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Email Settings Tab -->
            <div id="tab-email" class="settings-content hidden">
                <div class="px-6 py-6 space-y-6">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Konfigurasi Email</h3>
                        
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <!-- Mail Driver -->
                            <div>
                                <label for="mail_driver" class="block text-sm font-medium text-gray-700">Mail Driver</label>
                                <select name="settings[mail_driver]" id="mail_driver"
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                    <option value="smtp" {{ ($settings['mail_driver'] ?? 'smtp') === 'smtp' ? 'selected' : '' }}>SMTP</option>
                                    <option value="sendmail" {{ ($settings['mail_driver'] ?? '') === 'sendmail' ? 'selected' : '' }}>Sendmail</option>
                                    <option value="mailgun" {{ ($settings['mail_driver'] ?? '') === 'mailgun' ? 'selected' : '' }}>Mailgun</option>
                                </select>
                            </div>

                            <!-- SMTP Host -->
                            <div>
                                <label for="mail_host" class="block text-sm font-medium text-gray-700">SMTP Host</label>
                                <input type="text" name="settings[mail_host]" id="mail_host" 
                                       value="{{ old('settings.mail_host', $settings['mail_host'] ?? 'smtp.gmail.com') }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            </div>

                            <!-- SMTP Port -->
                            <div>
                                <label for="mail_port" class="block text-sm font-medium text-gray-700">SMTP Port</label>
                                <input type="number" name="settings[mail_port]" id="mail_port" 
                                       value="{{ old('settings.mail_port', $settings['mail_port'] ?? '587') }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            </div>

                            <!-- SMTP Username -->
                            <div>
                                <label for="mail_username" class="block text-sm font-medium text-gray-700">SMTP Username</label>
                                <input type="email" name="settings[mail_username]" id="mail_username" 
                                       value="{{ old('settings.mail_username', $settings['mail_username'] ?? '') }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            </div>

                            <!-- SMTP Password -->
                            <div class="sm:col-span-2">
                                <label for="mail_password" class="block text-sm font-medium text-gray-700">SMTP Password</label>
                                <input type="password" name="settings[mail_password]" id="mail_password" 
                                       value="{{ old('settings.mail_password', $settings['mail_password'] ?? '') }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                <p class="mt-1 text-xs text-gray-500">Kosongkan jika tidak ingin mengubah password</p>
                            </div>

                            <!-- From Address -->
                            <div>
                                <label for="mail_from_address" class="block text-sm font-medium text-gray-700">From Address</label>
                                <input type="email" name="settings[mail_from_address]" id="mail_from_address" 
                                       value="{{ old('settings.mail_from_address', $settings['mail_from_address'] ?? 'noreply@desaciuwlan.com') }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            </div>

                            <div>
                                <label for="mail_from_name" class="block text-sm font-medium text-gray-700">From Name</label>
                                <input type="text" name="settings[mail_from_name]" id="mail_from_name" 
                                       value="{{ old('settings.mail_from_name', $settings['mail_from_name'] ?? 'Website Desa Ciuwlan') }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            </div>
                        </div>

                        <!-- Test Email -->
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <h4 class="text-base font-medium text-gray-900 mb-4">Test Email</h4>
                            <div class="flex items-center space-x-4">
                                <div class="flex-1">
                                    <input type="email" id="test_email" placeholder="Masukkan email untuk test"
                                           class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                </div>
                                <button type="button" onclick="sendTestEmail()" 
                                        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                    <i class="fas fa-paper-plane mr-2"></i>
                                    Kirim Test Email
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Security Settings Tab -->
            <div id="tab-security" class="settings-content hidden">
                <div class="px-6 py-6 space-y-6">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Pengaturan Keamanan</h3>
                        
                        <div class="space-y-6">
                            <!-- Session Settings -->
                            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                                <div>
                                    <label for="session_lifetime" class="block text-sm font-medium text-gray-700">Session Lifetime (menit)</label>
                                    <input type="number" name="settings[session_lifetime]" id="session_lifetime" 
                                           value="{{ old('settings.session_lifetime', $settings['session_lifetime'] ?? '120') }}"
                                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                </div>

                                <div>
                                    <label for="max_login_attempts" class="block text-sm font-medium text-gray-700">Max Login Attempts</label>
                                    <input type="number" name="settings[max_login_attempts]" id="max_login_attempts" 
                                           value="{{ old('settings.max_login_attempts', $settings['max_login_attempts'] ?? '5') }}"
                                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                </div>
                            </div>

                            <!-- Password Policy -->
                            <div>
                                <h4 class="text-base font-medium text-gray-900 mb-3">Password Policy</h4>
                                <div class="space-y-3">
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" name="settings[password_require_uppercase]" value="1" 
                                               {{ ($settings['password_require_uppercase'] ?? false) ? 'checked' : '' }}
                                               class="rounded border-gray-300 text-blue-600">
                                        <span class="ml-2 text-sm text-gray-600">Wajib menggunakan huruf besar</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" name="settings[password_require_numbers]" value="1" 
                                               {{ ($settings['password_require_numbers'] ?? false) ? 'checked' : '' }}
                                               class="rounded border-gray-300 text-blue-600">
                                        <span class="ml-2 text-sm text-gray-600">Wajib menggunakan angka</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" name="settings[password_require_symbols]" value="1" 
                                               {{ ($settings['password_require_symbols'] ?? false) ? 'checked' : '' }}
                                               class="rounded border-gray-300 text-blue-600">
                                        <span class="ml-2 text-sm text-gray-600">Wajib menggunakan simbol</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Two Factor Authentication -->
                            <div>
                                <h4 class="text-base font-medium text-gray-900 mb-3">Two Factor Authentication</h4>
                                <label class="inline-flex items-center">
                                    <input type="checkbox" name="settings[enable_2fa]" value="1" 
                                           {{ ($settings['enable_2fa'] ?? false) ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-blue-600">
                                    <span class="ml-2 text-sm text-gray-600">Aktifkan 2FA untuk semua pengguna admin</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Backup Settings Tab -->
            <div id="tab-backup" class="settings-content hidden">
                <div class="px-6 py-6 space-y-6">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Pengaturan Backup</h3>
                        
                        <div class="space-y-6">
                            <!-- Auto Backup -->
                            <div>
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h4 class="text-base font-medium text-gray-900">Auto Backup</h4>
                                        <p class="text-sm text-gray-500">Backup otomatis database dan file</p>
                                    </div>
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" name="settings[auto_backup]" value="1" 
                                               {{ ($settings['auto_backup'] ?? false) ? 'checked' : '' }}
                                               class="rounded border-gray-300 text-blue-600">
                                    </label>
                                </div>
                            </div>

                            <!-- Backup Frequency -->
                            <div>
                                <label for="backup_frequency" class="block text-sm font-medium text-gray-700">Frekuensi Backup</label>
                                <select name="settings[backup_frequency]" id="backup_frequency"
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                    <option value="daily" {{ ($settings['backup_frequency'] ?? 'daily') === 'daily' ? 'selected' : '' }}>Harian</option>
                                    <option value="weekly" {{ ($settings['backup_frequency'] ?? '') === 'weekly' ? 'selected' : '' }}>Mingguan</option>
                                    <option value="monthly" {{ ($settings['backup_frequency'] ?? '') === 'monthly' ? 'selected' : '' }}>Bulanan</option>
                                </select>
                            </div>

                            <!-- Backup Retention -->
                            <div>
                                <label for="backup_retention" class="block text-sm font-medium text-gray-700">Simpan Backup (hari)</label>
                                <input type="number" name="settings[backup_retention]" id="backup_retention" 
                                       value="{{ old('settings.backup_retention', $settings['backup_retention'] ?? '30') }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                <p class="mt-1 text-sm text-gray-500">Backup lama akan dihapus otomatis</p>
                            </div>

                            <!-- Manual Backup -->
                            <div class="pt-6 border-t border-gray-200">
                                <h4 class="text-base font-medium text-gray-900 mb-4">Manual Backup</h4>
                                <div class="flex space-x-4">
                                    <button type="button" onclick="createBackup('database')" 
                                            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                        <i class="fas fa-database mr-2"></i>
                                        Backup Database
                                    </button>
                                    <button type="button" onclick="createBackup('files')" 
                                            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                        <i class="fas fa-folder mr-2"></i>
                                        Backup Files
                                    </button>
                                    <button type="button" onclick="createBackup('full')" 
                                            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                        <i class="fas fa-archive mr-2"></i>
                                        Full Backup
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Save Button -->
            <div class="px-6 py-4 border-t border-gray-200 flex items-center justify-end space-x-3">
                <button type="button" onclick="resetSettings()" 
                        class="bg-gray-200 py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-300">
                    Reset
                </button>
                <button type="submit" id="save-settings"
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                    <i class="fas fa-save mr-2"></i>
                    <span id="save-text">Simpan Pengaturan</span>
                    <div id="save-loading" class="hidden ml-2">
                        <i class="fas fa-spinner fa-spin"></i>
                    </div>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Tab functionality
        const tabs = document.querySelectorAll('.settings-tab');
        tabs.forEach(tab => {
            tab.addEventListener('click', function() {
                const targetTab = this.getAttribute('data-tab');
                showTab(targetTab);
            });
        });

        // Image preview functionality
        setupImagePreview('site_logo', 'logo-preview');
        setupImagePreview('site_favicon', 'favicon-preview');

        // Form submission
        const form = document.getElementById('settings-form');
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            saveSettings();
        });
    });

    function showTab(tabName) {
        // Hide all content
        document.querySelectorAll('.settings-content').forEach(content => {
            content.classList.add('hidden');
        });

        // Remove active class from all tabs
        document.querySelectorAll('.settings-tab').forEach(tab => {
            tab.classList.remove('active');
            tab.classList.add('border-transparent', 'text-gray-500');
            tab.classList.remove('border-blue-500', 'text-blue-600');
        });

        // Show target content
        document.getElementById(`tab-${tabName}`).classList.remove('hidden');

        // Add active class to target tab
        const targetTab = document.querySelector(`[data-tab="${tabName}"]`);
        targetTab.classList.add('active');
        targetTab.classList.remove('border-transparent', 'text-gray-500');
        targetTab.classList.add('border-blue-500', 'text-blue-600');
    }

    function setupImagePreview(inputId, previewId) {
        const input = document.getElementById(inputId);
        const preview = document.getElementById(previewId);

        if (input && preview) {
            input.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.src = e.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            });
        }
    }

    function saveSettings() {
        const saveBtn = document.getElementById('save-settings');
        const saveText = document.getElementById('save-text');
        const saveLoading = document.getElementById('save-loading');

        // Show loading state
        saveBtn.disabled = true;
        saveText.textContent = 'Menyimpan...';
        saveLoading.classList.remove('hidden');

        // Submit form
        const form = document.getElementById('settings-form');
        const formData = new FormData(form);

        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification(data.message || 'Pengaturan berhasil disimpan', 'success');
                
                // Update form with new values if needed
                if (data.settings) {
                    // Update form values
                }
            } else {
                showNotification(data.message || 'Terjadi kesalahan saat menyimpan pengaturan', 'error');
                
                // Show validation errors
                if (data.errors) {
                    Object.keys(data.errors).forEach(field => {
                        // Show field errors
                    });
                }
            }
        })
        .catch(error => {
            console.error('Save error:', error);
            showNotification('Terjadi kesalahan saat menyimpan pengaturan', 'error');
        })
        .finally(() => {
            // Reset button state
            saveBtn.disabled = false;
            saveText.textContent = 'Simpan Pengaturan';
            saveLoading.classList.add('hidden');
        });
    }

    function resetSettings() {
        if (confirm('Apakah Anda yakin ingin mereset pengaturan ke nilai default?')) {
            // Reset form to default values
            document.getElementById('settings-form').reset();
            showNotification('Pengaturan direset ke nilai default', 'info');
        }
    }

    function sendTestEmail() {
        const emailInput = document.getElementById('test_email');
        const email = emailInput.value;

        if (!email) {
            showNotification('Masukkan alamat email untuk test', 'error');
            return;
        }

        // Show loading
        emailInput.disabled = true;

        fetch(`{{ route('backend.settings.test-email') }}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ email: email })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Test email berhasil dikirim', 'success');
            } else {
                showNotification(data.message || 'Gagal mengirim test email', 'error');
            }
        })
        .catch(error => {
            console.error('Test email error:', error);
            showNotification('Terjadi kesalahan saat mengirim test email', 'error');
        })
        .finally(() => {
            emailInput.disabled = false;
        });
    }

    function createBackup(type) {
        if (confirm(`Apakah Anda yakin ingin membuat backup ${type}?`)) {
            fetch(`{{ route('backend.backup.create') }}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ type: type })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('Backup berhasil dibuat', 'success');
                } else {
                    showNotification(data.message || 'Gagal membuat backup', 'error');
                }
            })
            .catch(error => {
                console.error('Backup error:', error);
                showNotification('Terjadi kesalahan saat membuat backup', 'error');
            });
        }
    }
</script>

<style>
.settings-tab.active {
    @apply border-blue-500 text-blue-600;
}
</style>
@endpush