<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\VillageProfile;
use App\Models\VillageOfficial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VillageController extends Controller
{
    public function profile()
    {
        $profile = VillageProfile::first();
        return view('backend.village.profile', compact('profile'));
    }
    
    public function updateProfile(Request $request)
    {
        $request->validate([
            'village_name' => 'required|string|max:255',
            'village_code' => 'required|string|max:50',
            'district' => 'required|string|max:255',
            'regency' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'postal_code' => 'required|string|max:10',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'area' => 'nullable|numeric|min:0',
            'population' => 'nullable|integer|min:0',
            'family_count' => 'nullable|integer|min:0',
            'vision' => 'nullable|string',
            'mission' => 'nullable|string',
            'history' => 'nullable|string',
            'geographical_description' => 'nullable|string',
            'north_boundary' => 'nullable|string|max:255',
            'south_boundary' => 'nullable|string|max:255',
            'east_boundary' => 'nullable|string|max:255',
            'west_boundary' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'altitude' => 'nullable|numeric',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
        
        $profile = VillageProfile::first();
        if (!$profile) {
            $profile = new VillageProfile();
        }
        
        $logoPath = $profile->logo_path;
        if ($request->hasFile('logo')) {
            // Delete old logo
            if ($logoPath && Storage::disk('public')->exists($logoPath)) {
                Storage::disk('public')->delete($logoPath);
            }
            $logoPath = $request->file('logo')->store('village', 'public');
        }
        
        $profile->fill($request->except('logo'));
        $profile->logo_path = $logoPath;
        $profile->save();
        
        return redirect()->route('admin.village.profile')
                         ->with('success', 'Profil desa berhasil diperbarui.');
    }
    
    public function officials()
    {
        $officials = VillageOfficial::orderBy('order')->get();
        return view('backend.village.officials', compact('officials'));
    }
    
    public function storeOfficial(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'nip' => 'nullable|string|max:50',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'boolean'
        ]);
        
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('officials', 'public');
        }
        
        $order = $request->order ?? (VillageOfficial::max('order') + 1);
        
        VillageOfficial::create([
            'name' => $request->name,
            'position' => $request->position,
            'nip' => $request->nip,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'photo_path' => $photoPath,
            'order' => $order,
            'is_active' => $request->boolean('is_active', true)
        ]);
        
        return redirect()->route('admin.village.officials')
                         ->with('success', 'Perangkat desa berhasil ditambahkan.');
    }
    
    public function updateOfficial(Request $request, VillageOfficial $official)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'nip' => 'nullable|string|max:50',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'boolean'
        ]);
        
        $photoPath = $official->photo_path;
        if ($request->hasFile('photo')) {
            // Delete old photo
            if ($photoPath && Storage::disk('public')->exists($photoPath)) {
                Storage::disk('public')->delete($photoPath);
            }
            $photoPath = $request->file('photo')->store('officials', 'public');
        }
        
        $official->update([
            'name' => $request->name,
            'position' => $request->position,
            'nip' => $request->nip,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'photo_path' => $photoPath,
            'order' => $request->order ?? $official->order,
            'is_active' => $request->boolean('is_active', true)
        ]);
        
        return redirect()->route('admin.village.officials')
                         ->with('success', 'Perangkat desa berhasil diperbarui.');
    }
    
    public function deleteOfficial(VillageOfficial $official)
    {
        if ($official->photo_path && Storage::disk('public')->exists($official->photo_path)) {
            Storage::disk('public')->delete($official->photo_path);
        }
        
        $official->delete();
        
        return redirect()->route('admin.village.officials')
                         ->with('success', 'Perangkat desa berhasil dihapus.');
    }
}