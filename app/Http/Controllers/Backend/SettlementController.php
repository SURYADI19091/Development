<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Settlement;
use Illuminate\Http\Request;

class SettlementController extends Controller
{
    public function index(Request $request)
    {
        $query = Settlement::query();

        // Search
        if ($request->has('search') && $request->search != '') {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('hamlet_name', 'like', '%' . $request->search . '%')
                  ->orWhere('code', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by type
        if ($request->has('type') && $request->type != '') {
            $query->where('type', $request->type);
        }

        // Filter by status
        if ($request->has('status')) {
            if ($request->status == 'active') {
                $query->where('is_active', true);
            } elseif ($request->status == 'inactive') {
                $query->where('is_active', false);
            }
        }

        $settlements = $query->withCount('populationData')
                           ->orderBy('neighborhood_number', 'asc')
                           ->orderBy('community_number', 'asc')
                           ->paginate(15);

        $stats = [
            'total' => Settlement::count(),
            'active' => Settlement::where('is_active', true)->count(),
            'total_population' => Settlement::sum('population'),
        ];

        return view('backend.settlements.index', compact('settlements', 'stats'));
    }

    public function create()
    {
        return view('backend.settlements.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:100',
            'code' => 'nullable|string|max:50|unique:settlements',
            'description' => 'nullable|string',
            'hamlet_name' => 'required|string|max:255',
            'hamlet_leader' => 'required|string|max:255',
            'neighborhood_name' => 'required|string|max:255',
            'neighborhood_number' => 'required|string|max:10',
            'community_name' => 'required|string|max:255',
            'community_number' => 'required|string|max:10',
            'district' => 'required|string|max:255',
            'regency' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'area_size' => 'nullable|numeric|min:0',
            'population' => 'integer|min:0',
            'postal_code' => 'nullable|string|max:10',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'is_active' => 'boolean',
        ]);

        Settlement::create($request->all());

        return redirect()->route('backend.settlements.index')
                        ->with('success', 'Settlement created successfully!');
    }

    public function show($id)
    {
        $settlement = Settlement::withCount(['populationData', 'umkms', 'tourismObjects'])
                                ->findOrFail($id);
        return view('backend.settlements.show', compact('settlement'));
    }

    public function edit($id)
    {
        $settlement = Settlement::findOrFail($id);
        return view('backend.settlements.edit', compact('settlement'));
    }

    public function update(Request $request, $id)
    {
        $settlement = Settlement::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:100',
            'code' => 'nullable|string|max:50|unique:settlements,code,' . $settlement->id,
            'description' => 'nullable|string',
            'hamlet_name' => 'required|string|max:255',
            'hamlet_leader' => 'required|string|max:255',
            'neighborhood_name' => 'required|string|max:255',
            'neighborhood_number' => 'required|string|max:10',
            'community_name' => 'required|string|max:255',
            'community_number' => 'required|string|max:10',
            'district' => 'required|string|max:255',
            'regency' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'area_size' => 'nullable|numeric|min:0',
            'population' => 'integer|min:0',
            'postal_code' => 'nullable|string|max:10',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'is_active' => 'boolean',
        ]);

        $settlement->update($request->all());

        return redirect()->route('backend.settlements.index')
                        ->with('success', 'Settlement updated successfully!');
    }

    public function destroy($id)
    {
        $settlement = Settlement::findOrFail($id);
        
        // Check if settlement has related data
        if ($settlement->populationData()->count() > 0) {
            return redirect()->back()
                           ->with('error', 'Cannot delete settlement with existing population data!');
        }

        $settlement->delete();

        return redirect()->route('backend.settlements.index')
                        ->with('success', 'Settlement deleted successfully!');
    }

    public function toggleStatus(Request $request, $id)
    {
        $settlement = Settlement::findOrFail($id);
        $settlement->is_active = !$settlement->is_active;
        $settlement->save();

        $status = $settlement->is_active ? 'activated' : 'deactivated';
        return response()->json(['message' => "Settlement {$status} successfully!"]);
    }
}