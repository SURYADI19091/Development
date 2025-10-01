<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\PopulationData;
use App\Models\Settlement;
use App\Traits\HasPagination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PopulationController extends Controller
{
    use HasPagination;

    public function index(Request $request)
    {
        $query = PopulationData::with('settlement');

        // Apply search
        $searchableFields = ['name', 'identity_card_number', 'family_card_number'];
        $query = $this->applySearch($query, $request, $searchableFields);

        // Apply filters
        $filters = [
            'gender' => 'gender',
            'status' => 'status',
            'settlement_id' => 'settlement_id',
            'marital_status' => 'marital_status',
            'religion' => 'religion'
        ];
        $query = $this->applyFilters($query, $request, $filters);

        // Apply sorting
        $query = $this->applySorting($query, $request, 'created_at', 'desc');

        // Paginate results  
        $population = $this->paginateQuery($query, $request);

        // Get statistics
        $stats = [
            'total' => PopulationData::count(),
            'male' => PopulationData::where('gender', 'M')->count(),
            'female' => PopulationData::where('gender', 'F')->count(),
            'alive' => PopulationData::where('status', 'Hidup')->count(),
            'dead' => PopulationData::where('status', 'Mati')->count(),
            'married' => PopulationData::whereIn('marital_status', ['Kawin', 'Married'])->count(),
            'single' => PopulationData::whereIn('marital_status', ['Belum Kawin', 'Single'])->count()
        ];

        // Add households count (count distinct family_card_number)
        $stats['households'] = PopulationData::distinct('family_card_number')->count();

        // Get filter options
        $settlements = Settlement::orderBy('name')->get();

        // Prepare pagination info
        $paginationInfo = $this->getPaginationInfo($population);

        return view('backend.population.index', compact('population', 'settlements', 'stats', 'paginationInfo'));
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
            'status' => 'required|in:Hidup,Mati',
            'death_date' => 'nullable|date|required_if:status,Mati',
            'death_cause' => 'nullable|string|max:255|required_if:status,Mati',
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
            'status' => 'required|in:Hidup,Mati',
            'death_date' => 'nullable|date|required_if:status,Mati',
            'death_cause' => 'nullable|string|max:255|required_if:status,Mati',
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

    public function downloadTemplate()
    {
        // Create a simple CSV template
        $headers = [
            'serial_number',
            'family_card_number', 
            'identity_card_number',
            'name',
            'birth_place',
            'birth_date',
            'age',
            'address',
            'settlement_id',
            'gender',
            'marital_status',
            'family_relationship',
            'head_of_family',
            'religion',
            'occupation',
            'residence_type',
            'independent_family_head',
            'district',
            'regency',
            'province'
        ];
        
        $filename = 'population_template.csv';
        
        $handle = fopen('php://output', 'w');
        
        return response()->stream(function() use ($handle, $headers) {
            fputcsv($handle, $headers);
            fclose($handle);
        }, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"'
        ]);
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('population', []);
        
        if (empty($ids)) {
            return redirect()->back()->with('error', 'No population data selected.');
        }
        
        $count = PopulationData::whereIn('id', $ids)->delete();
        
        return redirect()->route('backend.population.index')
                        ->with('success', "Successfully deleted {$count} population data.");
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