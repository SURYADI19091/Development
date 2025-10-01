<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\News;
use App\Models\Agenda;
use App\Models\Gallery;
use App\Models\Umkm;
use App\Models\PopulationData;
use App\Models\ActivityLog;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistics
        $stats = [
            'total_users' => User::count(),
            'total_news' => News::count(),
            'published_news' => News::where('is_published', true)->count(),
            'total_agendas' => Agenda::count(),
            'upcoming_agendas' => Agenda::where('event_date', '>=', now())->count(),
            'total_gallery' => Gallery::count(),
            'total_umkm' => Umkm::count(),
            'verified_umkm' => Umkm::where('is_verified', true)->count(),
            'total_population' => PopulationData::count(),
        ];

        // Recent activities
        $recentActivities = ActivityLog::with('user')
                                     ->orderBy('created_at', 'desc')
                                     ->limit(10)
                                     ->get();

        // Recent news
        $recentNews = News::with('author')
                         ->orderBy('created_at', 'desc')
                         ->limit(5)
                         ->get();

        // Upcoming agendas
        $upcomingAgendas = Agenda::where('event_date', '>=', now())
                                ->orderBy('event_date', 'asc')
                                ->limit(5)
                                ->get();

        // Monthly statistics for charts
        $monthlyNews = News::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
                          ->whereYear('created_at', date('Y'))
                          ->groupBy('month')
                          ->pluck('count', 'month');

        $monthlyPopulation = PopulationData::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
                                          ->whereYear('created_at', date('Y'))
                                          ->groupBy('month')
                                          ->pluck('count', 'month');

        return view('backend.dashboard.index', compact(
            'stats',
            'recentActivities',
            'recentNews',
            'upcomingAgendas',
            'monthlyNews',
            'monthlyPopulation'
        ));
    }
}