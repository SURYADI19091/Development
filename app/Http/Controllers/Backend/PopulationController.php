<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\PopulationData;
use App\Models\Settlement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PopulationController extends Controller
{
    public function index(Request $request)
    {
        $query = PopulationData::with('settlement');

        // Search
        if ($request->has('search') && $request->search != '') {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('identity_card_number', 'like', '%' . $request->search . '%')
                  ->orWhere('family_card_number', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by gender
        if ($request->has('gender') && $request->gender != '') {
            $query->where('gender', $request->gender);
        }

        // Filter by settlement
        if ($request->has('settlement_id') && $request->settlement_id != '') {
            $query->where('settlement_id', $request->settlement_id);
        }

        // Filter by marital status
        if ($request->has('marital_status') && $request->marital_status != '') {
            $query->where('marital_status', $request->marital_status);
        }

        $populations = $query->orderBy('name', 'asc')->paginate(20);
        $settlements = Settlement::where('is_active', true)->get();

        // Statistics
        $stats = [
            'total' => PopulationData::count(),
            'male' => PopulationData::where('gender', 'M')->count(),
            'female' => PopulationData::where('gender', 'F')->count(),
            'married' => PopulationData::where('marital_status', 'Married')->count(),
        ];

        return view('backend.population.index', compact('populations', 'settlements', 'stats'));
    }

    public function create()
    {
        $settlements = Settlement::where('is_active', true)->get();
        return view('backend.population.create', compact('settlements'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'serial_number' => 'required|integer',
            'family_card_number' => 'required|string|max:16',
            'identity_card_number' => 'required|string|max:16|unique:population_data',
            'name' => 'required|string|max:255',
            'birth_place' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'age' => 'required|string',
            'address' => 'required|string',
            'settlement_id' => 'required|string',
            'gender' => 'required|in:M,F',
            'marital_status' => 'required|in:Single,Married,Divorced,Widowed',
            'family_relationship' => 'required|string',
            'head_of_family' => 'required|string',
            'religion' => 'required|string',
            'occupation' => 'required|string',
            'residence_type' => 'required|string',
            'independent_family_head' => 'required|string',
            'district' => 'required|string',
            'regency' => 'required|string',
            'province' => 'required|string',
        ]);

        PopulationData::create($request->all());

        return redirect()->route('backend.population.index')
                        ->with('success', 'Population data created successfully!');
    }

    public function show($id)
    {
        $population = PopulationData::with('settlement')->findOrFail($id);
        return view('backend.population.show', compact('population'));
    }

    public function edit($id)
    {
        $population = PopulationData::findOrFail($id);
        $settlements = Settlement::where('is_active', true)->get();
        return view('backend.population.edit', compact('population', 'settlements'));
    }

    public function update(Request $request, $id)
    {
        $population = PopulationData::findOrFail($id);

        $request->validate([
            'serial_number' => 'required|integer',
            'family_card_number' => 'required|string|max:16',
            'identity_card_number' => 'required|string|max:16|unique:population_data,identity_card_number,' . $population->id,
            'name' => 'required|string|max:255',
            'birth_place' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'age' => 'required|string',
            'address' => 'required|string',
            'settlement_id' => 'required|string',
            'gender' => 'required|in:M,F',
            'marital_status' => 'required|in:Single,Married,Divorced,Widowed',
            'family_relationship' => 'required|string',
            'head_of_family' => 'required|string',
            'religion' => 'required|string',
            'occupation' => 'required|string',
            'residence_type' => 'required|string',
            'independent_family_head' => 'required|string',
            'district' => 'required|string',
            'regency' => 'required|string',
            'province' => 'required|string',
        ]);

        $population->update($request->all());

        return redirect()->route('backend.population.index')
                        ->with('success', 'Population data updated successfully!');
    }

    public function destroy($id)
    {
        $population = PopulationData::findOrFail($id);
        $population->delete();

        return redirect()->route('backend.population.index')
                        ->with('success', 'Population data deleted successfully!');
    }

    public function import()
    {
        return view('backend.population.import');
    }

    public function export(Request $request)
    {
        // Implementation for exporting population data
        // You can use Laravel Excel or similar package
        return redirect()->back()->with('info', 'Export functionality to be implemented');
    }

    public function statistics()
    {
        // Detailed statistics for population data
        $stats = [
            'total_population' => PopulationData::count(),
            'gender_distribution' => PopulationData::selectRaw('gender, COUNT(*) as count')
                                                   ->groupBy('gender')
                                                   ->pluck('count', 'gender'),
            'age_distribution' => PopulationData::selectRaw('
                CASE 
                    WHEN CAST(age AS UNSIGNED) BETWEEN 0 AND 17 THEN "0-17"
                    WHEN CAST(age AS UNSIGNED) BETWEEN 18 AND 30 THEN "18-30"
                    WHEN CAST(age AS UNSIGNED) BETWEEN 31 AND 50 THEN "31-50"
                    WHEN CAST(age AS UNSIGNED) > 50 THEN "50+"
                    ELSE "Unknown"
                END as age_group,
                COUNT(*) as count
            ')
            ->groupBy('age_group')
            ->pluck('count', 'age_group'),
            'marital_status_distribution' => PopulationData::selectRaw('marital_status, COUNT(*) as count')
                                                          ->groupBy('marital_status')
                                                          ->pluck('count', 'marital_status'),
            'religion_distribution' => PopulationData::selectRaw('religion, COUNT(*) as count')
                                                    ->groupBy('religion')
                                                    ->pluck('count', 'religion'),
            'settlement_distribution' => PopulationData::with('settlement')
                                                      ->selectRaw('settlement_id, COUNT(*) as count')
                                                      ->groupBy('settlement_id')
                                                      ->get(),
        ];

        return view('backend.population.statistics', compact('stats'));
    }
}