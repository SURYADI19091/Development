<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\VillageProfile;
use App\Models\VillageOfficial;
use App\Models\VillageStatistic;
use App\Models\PopulationData;

class VillageController extends Controller
{
    public function profile()
    {
        $profile = VillageProfile::where('is_active', true)->first();
        
        if (!$profile) {
            abort(404, 'Village profile not found');
        }

        return view('frontend.village.profile', compact('profile'));
    }

    public function officials()
    {
        $officials = VillageOfficial::where('is_active', true)
                                  ->orderBy('sort_order')
                                  ->get()
                                  ->groupBy('department');

        return view('frontend.village.officials', compact('officials'));
    }

    public function statistics()
    {
        $currentYear = date('Y');
        
        // Population statistics
        $populationStats = [
            'total' => PopulationData::count(),
            'male' => PopulationData::where('gender', 'M')->count(),
            'female' => PopulationData::where('gender', 'F')->count(),
            'married' => PopulationData::where('marital_status', 'Married')->count(),
            'single' => PopulationData::where('marital_status', 'Single')->count(),
        ];

        // Age distribution
        $ageDistribution = PopulationData::selectRaw('
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
        ->pluck('count', 'age_group');

        // Religion distribution
        $religionStats = PopulationData::groupBy('religion')
                                      ->selectRaw('religion, count(*) as count')
                                      ->pluck('count', 'religion');

        // Occupation distribution
        $occupationStats = PopulationData::groupBy('occupation')
                                        ->selectRaw('occupation, count(*) as count')
                                        ->orderBy('count', 'desc')
                                        ->limit(10)
                                        ->pluck('count', 'occupation');

        // Village statistics from statistics table
        $villageStats = VillageStatistic::where('year', $currentYear)
                                       ->get()
                                       ->groupBy('category');

        return view('frontend.village.statistics', compact(
            'populationStats', 
            'ageDistribution', 
            'religionStats', 
            'occupationStats', 
            'villageStats'
        ));
    }

    public function map()
    {
        // You can add map functionality here
        return view('frontend.village.map');
    }
}