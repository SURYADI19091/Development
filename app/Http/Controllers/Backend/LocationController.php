<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LocationController extends Controller
{
    public function index(Request $request)
    {
        $query = Location::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by status
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        // Sorting
        $sortBy = $request->get('sort', 'created_at');
        $sortOrder = $request->get('order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $locations = $query->paginate($request->get('per_page', 15));

        // Get statistics
        $stats = [
            'total' => Location::count(),
            'active' => Location::where('is_active', true)->count(),
            'inactive' => Location::where('is_active', false)->count(),
            'on_map' => Location::where('show_on_map', true)->count(),
            'office' => Location::where('type', 'office')->count(),
            'school' => Location::where('type', 'school')->count(),
            'health' => Location::where('type', 'health')->count(),
            'tourism' => Location::where('type', 'tourism')->count(),
        ];

        // Filter options
        $types = Location::distinct()->whereNotNull('type')->pluck('type');

        return view('backend.locations.index', compact('locations', 'stats', 'types'));
    }

    public function create()
    {
        return view('backend.locations.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:office,school,health,religious,commercial,public,tourism,other',
            'address' => 'required|string|max:500',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'area_size' => 'nullable|numeric|min:0',
            'area_coordinates' => 'nullable|string',
            'description' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'operating_hours' => 'nullable|array',
            'icon' => 'nullable|string|max:100',
            'color' => 'nullable|string|max:7',
            'image' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            'is_active' => 'boolean',
            'show_on_map' => 'boolean',
            'sort_order' => 'nullable|integer'
        ]);

        // Process area coordinates if provided
        $areaCoordinates = null;
        if ($request->filled('area_coordinates')) {
            $coordinates = json_decode($request->area_coordinates, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $areaCoordinates = $coordinates;
            }
        }

        $locationData = [
            'name' => $request->name,
            'type' => $request->type,
            'address' => $request->address,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'area_size' => $request->area_size,
            'area_coordinates' => $areaCoordinates,
            'description' => $request->description,
            'phone' => $request->phone,
            'email' => $request->email,
            'operating_hours' => $request->operating_hours,
            'icon' => $request->icon ?? 'fas fa-map-marker-alt',
            'color' => $request->color ?? '#007bff',
            'is_active' => $request->boolean('is_active', true),
            'show_on_map' => $request->boolean('show_on_map', true),
            'sort_order' => $request->sort_order ?? 0,
            'created_by' => auth()->id()
        ];

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . Str::slug($request->name) . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/locations'), $imageName);
            $locationData['image_path'] = 'uploads/locations/' . $imageName;
        }

        Location::create($locationData);

        return redirect()->route('backend.locations.index')
                         ->with('success', 'Lokasi berhasil ditambahkan.');
    }

    public function show(Location $location)
    {
        $location->load('creator');
        return view('backend.locations.show', compact('location'));
    }

    public function edit(Location $location)
    {
        return view('backend.locations.edit', compact('location'));
    }

    public function update(Request $request, Location $location)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:office,school,health,religious,commercial,public,tourism,other',
            'address' => 'required|string|max:500',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'area_size' => 'nullable|numeric|min:0',
            'area_coordinates' => 'nullable|string',
            'description' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'operating_hours' => 'nullable|array',
            'icon' => 'nullable|string|max:100',
            'color' => 'nullable|string|max:7',
            'image' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            'is_active' => 'boolean',
            'show_on_map' => 'boolean',
            'sort_order' => 'nullable|integer'
        ]);

        // Process area coordinates if provided
        $areaCoordinates = $location->area_coordinates;
        if ($request->filled('area_coordinates')) {
            $coordinates = json_decode($request->area_coordinates, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $areaCoordinates = $coordinates;
            }
        }

        $locationData = [
            'name' => $request->name,
            'type' => $request->type,
            'address' => $request->address,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'area_size' => $request->area_size,
            'area_coordinates' => $areaCoordinates,
            'description' => $request->description,
            'phone' => $request->phone,
            'email' => $request->email,
            'operating_hours' => $request->operating_hours,
            'icon' => $request->icon ?? $location->icon,
            'color' => $request->color ?? $location->color,
            'is_active' => $request->boolean('is_active', true),
            'show_on_map' => $request->boolean('show_on_map', true),
            'sort_order' => $request->sort_order ?? $location->sort_order
        ];

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($location->image_path && file_exists(public_path($location->image_path))) {
                unlink(public_path($location->image_path));
            }

            $image = $request->file('image');
            $imageName = time() . '_' . Str::slug($request->name) . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/locations'), $imageName);
            $locationData['image_path'] = 'uploads/locations/' . $imageName;
        }

        $location->update($locationData);

        return redirect()->route('backend.locations.index')
                         ->with('success', 'Lokasi berhasil diperbarui.');
    }

    public function destroy(Location $location)
    {
        // Delete image if exists
        if ($location->image_path && file_exists(public_path($location->image_path))) {
            unlink(public_path($location->image_path));
        }

        $location->delete();

        return redirect()->route('backend.locations.index')
                         ->with('success', 'Lokasi berhasil dihapus.');
    }

    public function toggleStatus(Location $location)
    {
        $newStatus = !$location->is_active;
        
        $location->update(['is_active' => $newStatus]);
        
        $statusText = $newStatus ? 'diaktifkan' : 'dinonaktifkan';
        
        return response()->json([
            'success' => true,
            'message' => "Lokasi berhasil {$statusText}.",
            'is_active' => $newStatus
        ]);
    }

    public function export(Request $request)
    {
        // Get locations with applied filters
        $query = Location::query();
        
        // Apply filters from request
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }
        
        // For now, return a message indicating export functionality needs implementation
        // You can implement Excel export using maatwebsite/excel package
        return redirect()->back()->with('info', 'Export functionality untuk lokasi akan segera tersedia. Silakan gunakan fitur pencetakan browser untuk sementara.');
    }
}