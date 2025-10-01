<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use App\Models\News;
use App\Models\Agenda;
use App\Models\PopulationData;
use App\Models\Umkm;
use App\Models\TourismObject;

class SidebarComposer
{
    /**
     * Bind data to the view.
     */
    public function compose(View $view): void
    {
        // Population Statistics
        $populationStats = [
            'total_population' => PopulationData::where('status', 'Hidup')->count(),
            'male_population' => PopulationData::where('gender', 'M')->where('status', 'Hidup')->count(),
            'female_population' => PopulationData::where('gender', 'F')->where('status', 'Hidup')->count(),
            'total_families' => PopulationData::where('status', 'Hidup')->distinct('family_card_number')->count('family_card_number'),
        ];

        // Family Statistics
        $familyStats = [
            'total_families' => $populationStats['total_families'],
            'family_heads' => PopulationData::where('status', 'Hidup')->where('family_relationship', 'Kepala Keluarga')->count(),
            'avg_family_size' => $populationStats['total_families'] > 0 ? round($populationStats['total_population'] / $populationStats['total_families'], 1) : 0,
        ];

        // Social Aid Statistics (simulated based on occupation and age)
        $aidStats = [
            'elderly_aid' => PopulationData::where('status', 'Hidup')->whereRaw('TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) >= 65')->count(),
            'poor_families' => floor($familyStats['total_families'] * 0.15), // 15% poor families
            'child_aid' => PopulationData::where('status', 'Hidup')->whereRaw('TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) < 18')->count(),
            'total_aid_recipients' => 0
        ];
        $aidStats['total_aid_recipients'] = $aidStats['elderly_aid'] + $aidStats['poor_families'] + floor($aidStats['child_aid'] * 0.3);

        // Other Statistics
        $otherStats = [
            'umkm_count' => Umkm::where('is_active', true)->count(),
            'tourism_objects' => TourismObject::where('is_active', true)->count(),
            'news_count' => News::where('is_published', true)->count(),
            'agenda_count' => Agenda::where('event_date', '>=', now())->where('is_completed', false)->count(),
        ];

        // Popular and Latest Articles
        $popularArticle = News::where('is_published', true)->orderBy('views_count', 'desc')->first();
        $latestArticle = News::where('is_published', true)->orderBy('created_at', 'desc')->first();

        // Upcoming Agenda
        $upcomingAgenda = Agenda::where('event_date', '>=', now())
                               ->where('is_public', true)
                               ->where('is_completed', false)
                               ->orderBy('event_date', 'asc')
                               ->limit(3)
                               ->get();

        $sidebarData = [
            'population_stats' => $populationStats,
            'family_stats' => $familyStats,
            'aid_stats' => $aidStats,
            'other_stats' => $otherStats,
            'popular_article' => $popularArticle,
            'latest_article' => $latestArticle,
            'upcoming_agenda' => $upcomingAgenda
        ];

        $view->with('sidebarData', $sidebarData);
    }
}