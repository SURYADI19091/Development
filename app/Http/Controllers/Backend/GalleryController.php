<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GalleryController extends Controller
{
    public function index(Request $request)
    {
        $query = Gallery::with('user');
        
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
        }
        
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }
        
        $galleries = $query->orderBy('created_at', 'desc')->paginate(20);
        
        return view('backend.gallery.index', compact('galleries'));
    }
    
    public function create()
    {
        return view('backend.gallery.create');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|in:kegiatan,infrastruktur,wisata,umkm,budaya',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
            'is_featured' => 'boolean',
            'is_active' => 'boolean'
        ]);
        
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('gallery', 'public');
        }
        
        Gallery::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'description' => $request->description,
            'category' => $request->category,
            'image_path' => $imagePath,
            'is_featured' => $request->boolean('is_featured'),
            'is_active' => $request->boolean('is_active', true),
            'user_id' => auth()->id()
        ]);
        
        return redirect()->route('admin.gallery.index')
                         ->with('success', 'Foto berhasil ditambahkan ke galeri.');
    }
    
    public function show(Gallery $gallery)
    {
        return view('backend.gallery.show', compact('gallery'));
    }
    
    public function edit(Gallery $gallery)
    {
        return view('backend.gallery.edit', compact('gallery'));
    }
    
    public function update(Request $request, Gallery $gallery)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|in:kegiatan,infrastruktur,wisata,umkm,budaya',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'is_featured' => 'boolean',
            'is_active' => 'boolean'
        ]);
        
        $imagePath = $gallery->image_path;
        if ($request->hasFile('image')) {
            // Delete old image
            if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }
            $imagePath = $request->file('image')->store('gallery', 'public');
        }
        
        $gallery->update([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'description' => $request->description,
            'category' => $request->category,
            'image_path' => $imagePath,
            'is_featured' => $request->boolean('is_featured'),
            'is_active' => $request->boolean('is_active', true)
        ]);
        
        return redirect()->route('admin.gallery.index')
                         ->with('success', 'Foto galeri berhasil diperbarui.');
    }
    
    public function destroy(Gallery $gallery)
    {
        if ($gallery->image_path && Storage::disk('public')->exists($gallery->image_path)) {
            Storage::disk('public')->delete($gallery->image_path);
        }
        
        $gallery->delete();
        
        return redirect()->route('admin.gallery.index')
                         ->with('success', 'Foto galeri berhasil dihapus.');
    }
    
    public function toggleStatus(Gallery $gallery)
    {
        $gallery->update([
            'is_active' => !$gallery->is_active
        ]);
        
        $status = $gallery->is_active ? 'diaktifkan' : 'dinonaktifkan';
        
        return response()->json([
            'success' => true,
            'message' => "Foto galeri berhasil {$status}.",
            'is_active' => $gallery->is_active
        ]);
    }
    
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:galleries,id'
        ]);
        
        $galleries = Gallery::whereIn('id', $request->ids)->get();
        
        foreach ($galleries as $gallery) {
            if ($gallery->image_path && Storage::disk('public')->exists($gallery->image_path)) {
                Storage::disk('public')->delete($gallery->image_path);
            }
            $gallery->delete();
        }
        
        return response()->json([
            'success' => true,
            'message' => count($request->ids) . ' foto berhasil dihapus.'
        ]);
    }
}