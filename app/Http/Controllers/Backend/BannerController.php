<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::orderBy('order')->get();
        return view('backend.banners.index', compact('banners'));
    }
    
    public function create()
    {
        return view('backend.banners.create');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'button_text' => 'nullable|string|max:50',
            'button_link' => 'nullable|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:10240',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'boolean'
        ]);
        
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('banners', 'public');
        }
        
        $order = $request->order ?? (Banner::max('order') + 1);
        
        Banner::create([
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'description' => $request->description,
            'button_text' => $request->button_text,
            'button_link' => $request->button_link,
            'image_path' => $imagePath,
            'order' => $order,
            'is_active' => $request->boolean('is_active', true),
            'user_id' => auth()->id()
        ]);
        
        return redirect()->route('admin.banners.index')
                         ->with('success', 'Banner berhasil ditambahkan.');
    }
    
    public function show(Banner $banner)
    {
        return view('backend.banners.show', compact('banner'));
    }
    
    public function edit(Banner $banner)
    {
        return view('backend.banners.edit', compact('banner'));
    }
    
    public function update(Request $request, Banner $banner)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'button_text' => 'nullable|string|max:50',
            'button_link' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'boolean'
        ]);
        
        $imagePath = $banner->image_path;
        if ($request->hasFile('image')) {
            // Delete old image
            if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }
            $imagePath = $request->file('image')->store('banners', 'public');
        }
        
        $banner->update([
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'description' => $request->description,
            'button_text' => $request->button_text,
            'button_link' => $request->button_link,
            'image_path' => $imagePath,
            'order' => $request->order ?? $banner->order,
            'is_active' => $request->boolean('is_active', true)
        ]);
        
        return redirect()->route('admin.banners.index')
                         ->with('success', 'Banner berhasil diperbarui.');
    }
    
    public function destroy(Banner $banner)
    {
        if ($banner->image_path && Storage::disk('public')->exists($banner->image_path)) {
            Storage::disk('public')->delete($banner->image_path);
        }
        
        $banner->delete();
        
        return redirect()->route('admin.banners.index')
                         ->with('success', 'Banner berhasil dihapus.');
    }
    
    public function toggleStatus(Banner $banner)
    {
        $banner->update([
            'is_active' => !$banner->is_active
        ]);
        
        $status = $banner->is_active ? 'diaktifkan' : 'dinonaktifkan';
        
        return response()->json([
            'success' => true,
            'message' => "Banner berhasil {$status}.",
            'is_active' => $banner->is_active
        ]);
    }
    
    public function reorder(Request $request)
    {
        $request->validate([
            'banners' => 'required|array',
            'banners.*.id' => 'required|exists:banners,id',
            'banners.*.order' => 'required|integer|min:0'
        ]);
        
        foreach ($request->banners as $bannerData) {
            Banner::where('id', $bannerData['id'])->update(['order' => $bannerData['order']]);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Urutan banner berhasil diperbarui.'
        ]);
    }
}