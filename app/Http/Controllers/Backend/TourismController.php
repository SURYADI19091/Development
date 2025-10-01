<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\TourismObject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TourismController extends Controller
{
    public function index(Request $request)
    {
        $query = TourismObject::query();
        
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%')
                  ->orWhere('location', 'like', '%' . $request->search . '%');
        }
        
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }
        
        $tourism = $query->orderBy('created_at', 'desc')->paginate(20);
        
        return view('backend.tourism.index', compact('tourism'));
    }
    
    public function create()
    {
        return view('backend.tourism.create');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|in:alam,budaya,kuliner,edukasi,religi',
            'location' => 'required|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'facilities' => 'nullable|string',
            'ticket_price' => 'nullable|numeric|min:0',
            'operating_hours' => 'nullable|string',
            'contact_person' => 'nullable|string|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:5120',
            'is_featured' => 'boolean',
            'is_active' => 'boolean'
        ]);
        
        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imagePaths[] = $image->store('tourism', 'public');
            }
        }
        
        TourismObject::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'category' => $request->category,
            'location' => $request->location,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'facilities' => $request->facilities,
            'ticket_price' => $request->ticket_price,
            'operating_hours' => $request->operating_hours,
            'contact_person' => $request->contact_person,
            'contact_phone' => $request->contact_phone,
            'images' => json_encode($imagePaths),
            'is_featured' => $request->boolean('is_featured'),
            'is_active' => $request->boolean('is_active', true),
            'user_id' => auth()->id()
        ]);
        
        return redirect()->route('admin.tourism.index')
                         ->with('success', 'Objek wisata berhasil ditambahkan.');
    }
    
    public function show(TourismObject $tourism)
    {
        return view('backend.tourism.show', compact('tourism'));
    }
    
    public function edit(TourismObject $tourism)
    {
        return view('backend.tourism.edit', compact('tourism'));
    }
    
    public function update(Request $request, TourismObject $tourism)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|in:alam,budaya,kuliner,edukasi,religi',
            'location' => 'required|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'facilities' => 'nullable|string',
            'ticket_price' => 'nullable|numeric|min:0',
            'operating_hours' => 'nullable|string',
            'contact_person' => 'nullable|string|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:5120',
            'is_featured' => 'boolean',
            'is_active' => 'boolean'
        ]);
        
        $imagePaths = json_decode($tourism->images, true) ?? [];
        
        if ($request->hasFile('images')) {
            // Delete old images
            foreach ($imagePaths as $imagePath) {
                if (Storage::disk('public')->exists($imagePath)) {
                    Storage::disk('public')->delete($imagePath);
                }
            }
            
            // Upload new images
            $imagePaths = [];
            foreach ($request->file('images') as $image) {
                $imagePaths[] = $image->store('tourism', 'public');
            }
        }
        
        $tourism->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'category' => $request->category,
            'location' => $request->location,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'facilities' => $request->facilities,
            'ticket_price' => $request->ticket_price,
            'operating_hours' => $request->operating_hours,
            'contact_person' => $request->contact_person,
            'contact_phone' => $request->contact_phone,
            'images' => json_encode($imagePaths),
            'is_featured' => $request->boolean('is_featured'),
            'is_active' => $request->boolean('is_active', true)
        ]);
        
        return redirect()->route('admin.tourism.index')
                         ->with('success', 'Objek wisata berhasil diperbarui.');
    }
    
    public function destroy(TourismObject $tourism)
    {
        // Delete images
        $imagePaths = json_decode($tourism->images, true) ?? [];
        foreach ($imagePaths as $imagePath) {
            if (Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }
        }
        
        $tourism->delete();
        
        return redirect()->route('admin.tourism.index')
                         ->with('success', 'Objek wisata berhasil dihapus.');
    }
    
    public function toggleStatus(TourismObject $tourism)
    {
        $tourism->update([
            'is_active' => !$tourism->is_active
        ]);
        
        $status = $tourism->is_active ? 'diaktifkan' : 'dinonaktifkan';
        
        return response()->json([
            'success' => true,
            'message' => "Objek wisata berhasil {$status}.",
            'is_active' => $tourism->is_active
        ]);
    }
}