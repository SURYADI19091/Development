<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        // Get all settings from config or database
        $settings = [
            'site_name' => config('app.name', 'Website Desa'),
            'site_description' => 'Sistem Informasi Desa Terpadu',
            'site_keywords' => 'desa, informasi, pelayanan, masyarakat',
            'contact_email' => 'admin@desa.id',
            'contact_phone' => '(0267) 123-456',
            'contact_address' => 'Jl. Raya Desa No. 1',
            'social_facebook' => '',
            'social_instagram' => '',
            'social_twitter' => '',
            'social_youtube' => '',
            'google_analytics' => '',
            'site_logo' => '',
            'site_favicon' => '',
            'maintenance_mode' => false,
            'registration_enabled' => true,
            'comment_moderation' => true,
            'max_upload_size' => 5120, // KB
            'allowed_file_types' => 'jpg,jpeg,png,gif,pdf,doc,docx',
            'timezone' => 'Asia/Jakarta',
            'date_format' => 'd/m/Y',
            'time_format' => 'H:i'
        ];
        
        return view('backend.settings.index', compact('settings'));
    }
    
    public function update(Request $request)
    {
        $request->validate([
            'site_name' => 'required|string|max:255',
            'site_description' => 'nullable|string|max:500',
            'site_keywords' => 'nullable|string|max:500',
            'contact_email' => 'required|email|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'contact_address' => 'nullable|string|max:500',
            'social_facebook' => 'nullable|url|max:255',
            'social_instagram' => 'nullable|url|max:255',
            'social_twitter' => 'nullable|url|max:255',
            'social_youtube' => 'nullable|url|max:255',
            'google_analytics' => 'nullable|string',
            'site_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'site_favicon' => 'nullable|image|mimes:jpeg,png,jpg,gif,ico|max:512',
            'maintenance_mode' => 'boolean',
            'registration_enabled' => 'boolean',
            'comment_moderation' => 'boolean',
            'max_upload_size' => 'required|integer|min:1024|max:51200',
            'allowed_file_types' => 'required|string',
            'timezone' => 'required|string',
            'date_format' => 'required|string',
            'time_format' => 'required|string'
        ]);
        
        $settings = $request->except(['site_logo', 'site_favicon']);
        
        // Handle logo upload
        if ($request->hasFile('site_logo')) {
            $logoPath = $request->file('site_logo')->store('settings', 'public');
            $settings['site_logo'] = $logoPath;
        }
        
        // Handle favicon upload
        if ($request->hasFile('site_favicon')) {
            $faviconPath = $request->file('site_favicon')->store('settings', 'public');
            $settings['site_favicon'] = $faviconPath;
        }
        
        // In a real application, you would save these to database or config files
        // For now, we'll just flash success message
        
        return redirect()->route('backend.legacy-settings.index')
                         ->with('success', 'Pengaturan berhasil disimpan.');
    }
}