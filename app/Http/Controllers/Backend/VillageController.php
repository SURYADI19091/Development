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
            'name' => 'required|string|max:255',
            'district' => 'required|string|max:255',
            'regency' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
            'postal_code' => 'nullable|string|max:10',
            'area' => 'nullable|string|max:100',
            'total_rw' => 'nullable|integer|min:0',
            'total_rt' => 'nullable|integer|min:0',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'vision' => 'nullable|string',
            'mission' => 'nullable|string',
            'description' => 'nullable|string',
            'history' => 'nullable|string',
        ]);
        
        $profile = VillageProfile::first();
        if (!$profile) {
            $profile = new VillageProfile();
        }
        
        // Map form fields to database columns
        $profile->village_name = $request->name;
        $profile->district = $request->district;
        $profile->regency = $request->regency;
        $profile->province = $request->province;
        $profile->village_code = $request->code;
        $profile->postal_code = $request->postal_code;
        $profile->area_size = $request->area;
        $profile->latitude = $request->latitude;
        $profile->longitude = $request->longitude;
        $profile->vision = $request->vision;
        $profile->mission = $request->mission;
        $profile->description = $request->description;
        $profile->address = $request->address;
        $profile->phone = $request->phone;
        $profile->email = $request->email;
        $profile->website = $request->website;
        $profile->total_rw = $request->total_rw ?? 0;
        $profile->total_rt = $request->total_rt ?? 0;
        $profile->history = $request->history;
        
        $profile->save();
        
        return redirect()->route('backend.village.profile')
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
        
        return redirect()->route('backend.village.officials')
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
        
        return redirect()->route('backend.village.officials')
                         ->with('success', 'Perangkat desa berhasil diperbarui.');
    }
    
    public function deleteOfficial(VillageOfficial $official)
    {
        if ($official->photo_path && Storage::disk('public')->exists($official->photo_path)) {
            Storage::disk('public')->delete($official->photo_path);
        }
        
        $official->delete();
        
        return redirect()->route('backend.village.officials')
                         ->with('success', 'Perangkat desa berhasil dihapus');
    }
}