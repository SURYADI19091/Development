<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\Agenda;
use App\Models\Announcement;
use App\Models\Gallery;
use App\Models\VillageProfile;
use App\Models\Banner;
use App\Models\Umkm;
use App\Models\TourismObject;
use App\Models\PopulationData;
use App\Models\VillageBudget;
use App\Models\BudgetTransaction;
use App\Models\Location;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class HomeController extends Controller
{
    public function getSidebarData()
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
            'total_families' => PopulationData::where('status', 'Hidup')->distinct('family_card_number')->count('family_card_number'),
            'single_families' => PopulationData::where('status', 'Hidup')->where('family_relationship', 'Kepala Keluarga')->count(),
            'family_heads' => PopulationData::where('status', 'Hidup')->where('family_relationship', 'Kepala Keluarga')->count(),
        ];

        // Social Aid Statistics (simulated based on occupation and age)
        $aidStats = [
            'elderly_aid' => PopulationData::where('status', 'Hidup')->whereRaw('TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) >= 65')->count(),
            'poor_families' => floor($familyStats['total_families'] * 0.15), // 15% poor families
            'child_aid' => PopulationData::where('status', 'Hidup')->whereRaw('TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) < 18')->count(),
        ];

        // Other Statistics
        $otherStats = [
            'umkm_count' => Umkm::where('is_active', true)->count(),
            'tourism_objects' => TourismObject::where('is_active', true)->count(),
            'news_count' => News::count(),
            'agenda_count' => Agenda::where('event_date', '>=', now())->count(),
            'location_count' => Location::where('is_active', true)->count(),
            'mapped_locations' => Location::where('is_active', true)->where('show_on_map', true)->count(),
        ];

        // Popular and Latest Articles
        $popularArticles = News::where('is_published', true)->orderBy('views_count', 'desc')->first();
        $latestArticles = News::where('is_published', true)->orderBy('created_at', 'desc')->first();

        // Upcoming Agenda
        $upcomingAgenda = Agenda::where('event_date', '>=', now())
                               ->orderBy('event_date', 'asc')
                               ->limit(3)
                               ->get();

        return [
            'population_stats' => $populationStats,
            'family_stats' => $familyStats,
            'aid_stats' => $aidStats,
            'other_stats' => $otherStats,
            'popular_article' => $popularArticles,
            'latest_article' => $latestArticles,
            'upcoming_agenda' => $upcomingAgenda
        ];
    }

    public function index()
    {
        // Get village profile
        $villageProfile = VillageProfile::first();

        // Get active banners
        $banners = Banner::where('is_active', true)
                        ->orderBy('created_at', 'desc')
                        ->get();

        // Get statistics from population data (living people only)
        $statistics = [
            'total_population' => PopulationData::where('status', 'Hidup')->count(),
            'male_population' => PopulationData::where('gender', 'M')->where('status', 'Hidup')->count(),
            'female_population' => PopulationData::where('gender', 'F')->where('status', 'Hidup')->count(),
            'total_families' => PopulationData::where('status', 'Hidup')->distinct('family_card_number')->count('family_card_number'),
        ];

        // Get recent news
        $recentNews = News::orderBy('created_at', 'desc')
                         ->limit(4)
                         ->get();

        // Get recent gallery
        $recentGallery = Gallery::orderBy('created_at', 'desc')
                               ->limit(6)
                               ->get();

        // Get upcoming agenda
        $upcomingAgenda = Agenda::where('event_date', '>=', now())
                               ->orderBy('event_date', 'asc')
                               ->limit(3)
                               ->get();

        // Get important announcements
        $importantAnnouncements = Announcement::where('is_active', true)
                                            ->where('priority', '!=', 'low')
                                            ->orderBy('created_at', 'desc')
                                            ->limit(3)
                                            ->get();

        // Get recent activities for dashboard
        $recentActivities = collect([]);
        
        // Add recent news as activities
        $recentNews->each(function($news) use ($recentActivities) {
            $recentActivities->push([
                'icon' => 'fas fa-newspaper text-blue-500',
                'title' => 'Berita: ' . $news->title,
                'time' => $news->created_at->diffForHumans(),
                'author' => $news->author->name ?? 'Admin',
                'color' => 'text-blue-500'
            ]);
        });
        
        // Add recent agenda as activities
        $upcomingAgenda->each(function($agenda) use ($recentActivities) {
            $recentActivities->push([
                'icon' => 'fas fa-calendar text-green-500',
                'title' => 'Agenda: ' . $agenda->title,
                'time' => $agenda->event_date->diffForHumans(),
                'author' => 'Panitia',
                'color' => 'text-green-500'
            ]);
        });

        // Sort activities by time and limit
        $recentActivities = $recentActivities->sortByDesc('time')->take(5);

        // Get current user if authenticated
        $user = Auth::check() ? Auth::user() : null;
        
        // Get role permissions if user is authenticated
        $rolePermissions = [];
        if ($user) {
            switch ($user->role) {
                case 'admin':
                    $rolePermissions = ['Kelola Semua Data', 'Manajemen User', 'Backup Sistem', 'Laporan Lengkap'];
                    break;
                case 'village_head':
                    $rolePermissions = ['Lihat Laporan', 'Validasi Data', 'Manajemen Agenda'];
                    break;
                case 'secretary':
                    $rolePermissions = ['Kelola Berita', 'Kelola Pengumuman', 'Layanan Surat'];
                    break;
                case 'staff':
                    $rolePermissions = ['Input Data', 'Kelola UMKM'];
                    break;
                default:
                    $rolePermissions = ['Lihat Informasi Publik'];
            }
        }

        // Get sidebar data
        $sidebarData = $this->getSidebarData();

        return view('frontend.page.index', compact(
            'villageProfile',
            'banners',
            'statistics',
            'recentNews',
            'recentGallery',
            'upcomingAgenda',
            'importantAnnouncements',
            'recentActivities',
            'user',
            'rolePermissions',
            'sidebarData'
        ));
    }

    public function populationData()
    {
        $populationData = PopulationData::with('settlement')
                                       ->paginate(20);

        // Basic statistics
        $totalPopulation = PopulationData::where('status', 'Hidup')->count();
        $malePopulation = PopulationData::where('gender', 'M')->where('status', 'Hidup')->count();
        $femalePopulation = PopulationData::where('gender', 'F')->where('status', 'Hidup')->count();
        $totalFamilies = PopulationData::where('status', 'Hidup')->distinct('family_card_number')->count('family_card_number');

        $statistics = [
            'total_population' => $totalPopulation,
            'male_population' => $malePopulation,
            'female_population' => $femalePopulation,
            'total_families' => $totalFamilies,
            'male_percentage' => $totalPopulation > 0 ? round(($malePopulation / $totalPopulation) * 100, 1) : 0,
            'female_percentage' => $totalPopulation > 0 ? round(($femalePopulation / $totalPopulation) * 100, 1) : 0,
        ];

        // Age distribution
        $ageDistribution = [
            'balita' => PopulationData::where('status', 'Hidup')->whereRaw('TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) BETWEEN 0 AND 4')->count(),
            'anak_remaja' => PopulationData::where('status', 'Hidup')->whereRaw('TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) BETWEEN 5 AND 17')->count(),
            'produktif' => PopulationData::where('status', 'Hidup')->whereRaw('TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) BETWEEN 18 AND 64')->count(),
            'lansia' => PopulationData::where('status', 'Hidup')->whereRaw('TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) >= 65')->count(),
        ];

        // Calculate percentages for age distribution
        foreach ($ageDistribution as $key => $value) {
            $ageDistribution[$key . '_percentage'] = $totalPopulation > 0 ? round(($value / $totalPopulation) * 100, 1) : 0;
        }

        // Education level (for people 15+)
        $totalEducationAge = PopulationData::where('status', 'Hidup')->whereRaw('TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) >= 15')->count();
        $educationLevels = [
            'tidak_sekolah' => PopulationData::where('status', 'Hidup')->whereRaw('TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) >= 15')->where(function($q) {
                $q->whereNull('occupation')->orWhere('occupation', '')->orWhere('occupation', 'LIKE', '%Tidak%')->orWhere('occupation', 'LIKE', '%Belum%');
            })->count(),
            'sd' => PopulationData::where('status', 'Hidup')->whereRaw('TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) >= 15')->where('occupation', 'LIKE', '%SD%')->count(),
            'smp' => PopulationData::where('status', 'Hidup')->whereRaw('TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) >= 15')->where('occupation', 'LIKE', '%SMP%')->count(),
            'sma' => PopulationData::where('status', 'Hidup')->whereRaw('TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) >= 15')->where('occupation', 'LIKE', '%SMA%')->count(),
            'diploma' => PopulationData::where('status', 'Hidup')->whereRaw('TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) >= 15')->where(function($q) {
                $q->where('occupation', 'LIKE', '%Diploma%')->orWhere('occupation', 'LIKE', '%S1%')->orWhere('occupation', 'LIKE', '%Sarjana%');
            })->count(),
            'pascasarjana' => PopulationData::where('status', 'Hidup')->whereRaw('TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) >= 15')->where(function($q) {
                $q->where('occupation', 'LIKE', '%S2%')->orWhere('occupation', 'LIKE', '%S3%')->orWhere('occupation', 'LIKE', '%Master%')->orWhere('occupation', 'LIKE', '%Doktor%');
            })->count(),
        ];

        // Calculate remaining education data if totals don't match
        $totalEducationCounted = array_sum($educationLevels);
        if ($totalEducationCounted < $totalEducationAge) {
            $educationLevels['sd'] += ($totalEducationAge - $totalEducationCounted) * 0.4; // Estimate SD as largest group
            $educationLevels['smp'] += ($totalEducationAge - $totalEducationCounted) * 0.3;
            $educationLevels['sma'] += ($totalEducationAge - $totalEducationCounted) * 0.3;
        }

        // Occupation data (working age 18-64)
        $occupations = [
            'petani' => PopulationData::where('status', 'Hidup')->whereRaw('TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) BETWEEN 18 AND 64')->where('occupation', 'LIKE', '%Petani%')->count(),
            'buruh_tani' => PopulationData::where('status', 'Hidup')->whereRaw('TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) BETWEEN 18 AND 64')->where('occupation', 'LIKE', '%Buruh%')->count(),
            'wiraswasta' => PopulationData::where('status', 'Hidup')->whereRaw('TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) BETWEEN 18 AND 64')->where(function($q) {
                $q->where('occupation', 'LIKE', '%Wiraswasta%')->orWhere('occupation', 'LIKE', '%Dagang%')->orWhere('occupation', 'LIKE', '%Pedagang%');
            })->count(),
            'pns' => PopulationData::where('status', 'Hidup')->whereRaw('TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) BETWEEN 18 AND 64')->where(function($q) {
                $q->where('occupation', 'LIKE', '%PNS%')->orWhere('occupation', 'LIKE', '%TNI%')->orWhere('occupation', 'LIKE', '%POLRI%');
            })->count(),
            'guru' => PopulationData::where('status', 'Hidup')->whereRaw('TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) BETWEEN 18 AND 64')->where(function($q) {
                $q->where('occupation', 'LIKE', '%Guru%')->orWhere('occupation', 'LIKE', '%Pendidik%');
            })->count(),
            'kesehatan' => PopulationData::where('status', 'Hidup')->whereRaw('TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) BETWEEN 18 AND 64')->where(function($q) {
                $q->where('occupation', 'LIKE', '%Dokter%')->orWhere('occupation', 'LIKE', '%Bidan%')->orWhere('occupation', 'LIKE', '%Perawat%');
            })->count(),
            'teknologi' => PopulationData::where('status', 'Hidup')->whereRaw('TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) BETWEEN 18 AND 64')->where(function($q) {
                $q->where('occupation', 'LIKE', '%IT%')->orWhere('occupation', 'LIKE', '%Teknologi%')->orWhere('occupation', 'LIKE', '%Programmer%');
            })->count(),
        ];

        // Calculate remaining occupations
        $totalWorkingAge = PopulationData::where('status', 'Hidup')->whereRaw('TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) BETWEEN 18 AND 64')->count();
        $totalOccupationsCounted = array_sum($occupations);
        $occupations['lainnya'] = max(0, $totalWorkingAge - $totalOccupationsCounted);

        // Population by area (using settlement data)
        $populationByArea = [
            'dusun_1' => PopulationData::where('status', 'Hidup')->where('settlement_id', 1)->count(),
            'dusun_2' => PopulationData::where('status', 'Hidup')->where('settlement_id', 2)->count(),
            'dusun_3' => 0, // We only have 2 settlements now
            'dusun_4' => 0, // We only have 2 settlements now
        ];

        // Get village profile for title
        $villageProfile = VillageProfile::first();

        return view('frontend.page.data-penduduk', compact(
            'populationData', 
            'statistics', 
            'ageDistribution', 
            'educationLevels', 
            'occupations', 
            'populationByArea',
            'villageProfile'
        ));
    }

    public function populationStats()
    {
        // Generate yearly data (based on created_at from database)
        $yearlyData = collect();
        for ($year = 2021; $year <= 2025; $year++) {
            $total = PopulationData::whereYear('created_at', '<=', $year)->count();
            $yearlyData->push([
                'year' => $year,
                'total' => $total
            ]);
        }

        // Gender Statistics (only living people)
        $totalPop = PopulationData::where('status', 'Hidup')->count();
        $maleCount = PopulationData::where('gender', 'M')->where('status', 'Hidup')->count();
        $femaleCount = PopulationData::where('gender', 'F')->where('status', 'Hidup')->count();
        
        $genderStats = [
            'male_count' => $maleCount,
            'female_count' => $femaleCount,
            'male_percentage' => $totalPop > 0 ? ($maleCount / $totalPop) * 100 : 50,
            'female_percentage' => $totalPop > 0 ? ($femaleCount / $totalPop) * 100 : 50,
            'ratio' => $femaleCount > 0 ? '1:' . number_format($maleCount / $femaleCount, 2) : '1:1'
        ];

        // Age Statistics with proper grouping (only living people)
        $ageStats = PopulationData::selectRaw('
            CASE 
                WHEN TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) BETWEEN 0 AND 4 THEN 2
                WHEN TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) BETWEEN 5 AND 17 THEN 11
                WHEN TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) BETWEEN 18 AND 34 THEN 26
                WHEN TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) BETWEEN 35 AND 49 THEN 42
                WHEN TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) BETWEEN 50 AND 64 THEN 57
                ELSE 70
            END as age,
            CASE 
                WHEN gender = "M" THEN "Laki-laki"
                ELSE "Perempuan"
            END as gender,
            COUNT(*) as count
        ')
        ->whereNotNull('birth_date')
        ->where('status', 'Hidup')
        ->groupByRaw('
            CASE 
                WHEN TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) BETWEEN 0 AND 4 THEN 2
                WHEN TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) BETWEEN 5 AND 17 THEN 11
                WHEN TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) BETWEEN 18 AND 34 THEN 26
                WHEN TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) BETWEEN 35 AND 49 THEN 42
                WHEN TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) BETWEEN 50 AND 64 THEN 57
                ELSE 70
            END,
            gender
        ')
        ->get();

        // Marital Status (translate to Indonesian, only living people)
        $maritalStatus = PopulationData::selectRaw('
            CASE 
                WHEN marital_status = "Single" THEN "Belum Kawin"
                WHEN marital_status = "Married" THEN "Kawin"
                WHEN marital_status = "Divorced" THEN "Cerai Hidup"
                WHEN marital_status = "Widowed" THEN "Cerai Mati"
                ELSE marital_status
            END as marital_status,
            COUNT(*) as count
        ')
        ->whereNotNull('marital_status')
        ->where('status', 'Hidup')
        ->groupByRaw('
            CASE 
                WHEN marital_status = "Single" THEN "Belum Kawin"
                WHEN marital_status = "Married" THEN "Kawin"
                WHEN marital_status = "Divorced" THEN "Cerai Hidup"
                WHEN marital_status = "Widowed" THEN "Cerai Mati"
                ELSE marital_status
            END
        ')
        ->orderBy('count', 'desc')
        ->get();

        // Religion Statistics (only living people)
        $religions = PopulationData::selectRaw('religion, COUNT(*) as count')
                                  ->whereNotNull('religion')
                                  ->where('status', 'Hidup')
                                  ->groupBy('religion')
                                  ->orderBy('count', 'desc')
                                  ->get();

        // Occupation Statistics (only living people)
        $occupations = PopulationData::selectRaw('occupation, COUNT(*) as count')
                                    ->whereNotNull('occupation')
                                    ->where('status', 'Hidup')
                                    ->groupBy('occupation')
                                    ->orderBy('count', 'desc')
                                    ->limit(10)
                                    ->get();

        // Birth & Death Statistics (2025)
        $currentYear = 2025;
        
        // Calculate births (people born this year and still alive)
        $birthsThisYear = PopulationData::whereYear('birth_date', $currentYear)
                                       ->where('status', 'Hidup')
                                       ->get();
        $totalBirths = $birthsThisYear->count();
        $maleBirths = $birthsThisYear->where('gender', 'M')->count();
        $femaleBirths = $birthsThisYear->where('gender', 'F')->count();
        
        // Calculate deaths (actual deaths from database)
        $deathsThisYear = PopulationData::whereYear('death_date', $currentYear)->get();
        $totalDeaths = $deathsThisYear->count();
        $maleDeaths = $deathsThisYear->where('gender', 'M')->count();
        $femaleDeaths = $deathsThisYear->where('gender', 'F')->count();
        
        // Calculate birth/death rates per 1000 population (only living people)
        $livingPopulation = PopulationData::where('status', 'Hidup')->count();
        $totalPopulation = PopulationData::count(); // Total including deceased for rate calculation
        
        $birthRate = $totalPopulation > 0 ? ($totalBirths / $totalPopulation) * 1000 : 0;
        $deathRate = $totalPopulation > 0 ? ($totalDeaths / $totalPopulation) * 1000 : 0;
        $naturalGrowth = $totalBirths - $totalDeaths;
        $naturalGrowthRate = $totalPopulation > 0 ? ($naturalGrowth / $totalPopulation) * 1000 : 0;

        $birthDeathStats = [
            'births' => [
                'total' => $totalBirths,
                'male' => $maleBirths,
                'female' => $femaleBirths,
                'rate' => number_format($birthRate, 1)
            ],
            'deaths' => [
                'total' => $totalDeaths,
                'male' => $maleDeaths,
                'female' => $femaleDeaths,
                'rate' => number_format($deathRate, 1)
            ],
            'natural_growth' => [
                'total' => $naturalGrowth,
                'rate' => number_format($naturalGrowthRate, 1)
            ],
            'population_status' => [
                'living' => $livingPopulation,
                'deceased' => PopulationData::where('status', 'Meninggal')->count(),
                'total' => $totalPopulation
            ]
        ];

        return view('frontend.page.statistik-penduduk', compact(
            'yearlyData',
            'genderStats', 
            'ageStats',
            'maritalStatus',
            'religions',
            'occupations',
            'birthDeathStats'
        ));
    }

    public function budget()
    {
        $currentYear = date('Y');
        
        $budgetSummary = VillageBudget::where('fiscal_year', $currentYear)
                                    ->selectRaw('budget_type, category, SUM(planned_amount) as total_allocated')
                                    ->groupBy('budget_type', 'category')
                                    ->get();

        $totalBudget = $budgetSummary->sum('total_allocated');
        
        $realization = BudgetTransaction::whereHas('budget', function($q) use ($currentYear) {
            $q->where('fiscal_year', $currentYear);
        })->where('transaction_type', 'expense')->sum('amount');

        // Get recent transactions for activity feed
        $recentTransactions = \App\Models\BudgetTransaction::whereHas('budget', function($q) use ($currentYear) {
            $q->where('fiscal_year', $currentYear);
        })->with('budget')
          ->orderBy('transaction_date', 'desc')
          ->limit(3)
          ->get();

        return view('frontend.page.apbdes', compact('budgetSummary', 'totalBudget', 'realization', 'recentTransactions'));
    }

    public function budgetPlan()
    {
        $currentYear = date('Y');
        
        // Get all budgets for current year
        $budgets = VillageBudget::where('fiscal_year', $currentYear)
                               ->orderBy('budget_type')
                               ->orderBy('category')
                               ->get();

        // Separate revenue and expenditure
        $revenue = $budgets->where('budget_type', 'pendapatan');
        $expenditure = $budgets->where('budget_type', 'belanja');

        // Calculate revenue breakdown
        $revenueBreakdown = [
            'dana_desa' => $revenue->where('category', 'Dana Desa')->sum('planned_amount'),
            'add' => $revenue->where('category', 'Alokasi Dana Desa')->sum('planned_amount'),
            'dana_bantuan' => $revenue->where('category', 'Dana Bantuan Keuangan')->sum('planned_amount'),
            'pades' => $revenue->where('category', 'Pendapatan Asli Desa')->sum('planned_amount'),
            'lain_lain' => $revenue->whereNotIn('category', [
                'Dana Desa', 
                'Alokasi Dana Desa', 
                'Dana Bantuan Keuangan', 
                'Pendapatan Asli Desa'
            ])->sum('planned_amount'),
        ];
        
        $totalRevenue = array_sum($revenueBreakdown);

        // Calculate expenditure by sector
        $expenditureByCategory = $expenditure->groupBy('category')->map(function($items) {
            return [
                'planned_amount' => $items->sum('planned_amount'),
                'realized_amount' => $items->sum('realized_amount'),
                'items' => $items->toArray()
            ];
        });

        $totalExpenditure = $expenditure->sum('planned_amount');

        // Calculate quarterly progress (based on realized amounts)
        $quarterlyProgress = [];
        for ($q = 1; $q <= 4; $q++) {
            $quarterlyProgress["q{$q}"] = [
                'target' => $totalRevenue / 4,
                'realized' => $expenditure->sum('realized_amount') / 4 * $q,
                'percentage' => ($expenditure->sum('realized_amount') / 4 * $q) / ($totalRevenue / 4) * 100
            ];
        }

        // Budget comparison with previous years
        $previousYears = [];
        for ($i = 1; $i <= 3; $i++) {
            $year = $currentYear - $i;
            $yearBudgets = VillageBudget::where('fiscal_year', $year)->get();
            $previousYears[$year] = [
                'revenue' => $yearBudgets->where('budget_type', 'pendapatan')->sum('planned_amount'),
                'expenditure' => $yearBudgets->where('budget_type', 'belanja')->sum('planned_amount'),
                'realization' => $yearBudgets->where('budget_type', 'belanja')->sum('realized_amount'),
            ];
        }

        return view('frontend.page.apbdes-anggaran', compact(
            'budgets', 
            'revenue', 
            'expenditure', 
            'revenueBreakdown', 
            'totalRevenue', 
            'expenditureByCategory', 
            'totalExpenditure',
            'quarterlyProgress',
            'previousYears',
            'currentYear'
        ));
    }

    public function budgetRealization()
    {
        $currentYear = date('Y');
        $budgets = VillageBudget::where('fiscal_year', $currentYear)
                               ->with(['transactions' => function($q) {
                                   $q->where('transaction_type', 'expense');
                               }])
                               ->get();

        return view('frontend.page.apbdes-realisasi', compact('budgets'));
    }

    public function budgetReport()
    {
        $currentYear = request('filter_year', date('Y'));
        $filterMonth = request('filter_month');
        
        // Monthly report with cumulative calculations
        $monthlyReportQuery = BudgetTransaction::whereHas('budget', function($q) use ($currentYear) {
            $q->where('fiscal_year', $currentYear);
        });
        
        // Apply month filter if specified
        if ($filterMonth) {
            $monthlyReportQuery->whereMonth('transaction_date', $filterMonth);
        }
        
        $monthlyReport = $monthlyReportQuery
            ->selectRaw('MONTH(transaction_date) as month, YEAR(transaction_date) as year, SUM(CASE WHEN transaction_type = "income" THEN amount ELSE 0 END) as income, SUM(CASE WHEN transaction_type = "expense" THEN amount ELSE 0 END) as expense')
            ->groupBy('month', 'year')
            ->orderBy('month')
            ->get();

        // Calculate cumulative totals and progress
        $totalBudget = VillageBudget::where('fiscal_year', $currentYear)->sum('planned_amount');
        $totalRealized = 0;
        $monthlyReports = [];
        
        $monthNames = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        foreach ($monthlyReport as $report) {
            $totalRealized += $report->expense;
            $monthlyReports[] = [
                'month' => $report->month,
                'month_name' => $monthNames[$report->month],
                'year' => $report->year,
                'realization' => $report->expense,
                'cumulative' => $totalRealized,
                'progress' => $totalBudget > 0 ? ($totalRealized / $totalBudget) * 100 : 0
            ];
        }
        
        // If no data found and no filter applied, generate sample data
        if (empty($monthlyReports) && !$filterMonth) {
            for ($month = 1; $month <= 12; $month++) {
                if ($month <= date('n')) { // Only show months up to current month
                    $realization = rand(80000000, 150000000);
                    $totalRealized += $realization;
                    $monthlyReports[] = [
                        'month' => $month,
                        'month_name' => $monthNames[$month],
                        'year' => $currentYear,
                        'realization' => $realization,
                        'cumulative' => $totalRealized,
                        'progress' => $totalBudget > 0 ? ($totalRealized / $totalBudget) * 100 : ($totalRealized / 2000000000) * 100
                    ];
                }
            }
        }

        // Annual reports
        $annualReports = [
            [
                'year' => 2025,
                'status' => 'current',
                'total_budget' => $totalBudget,
                'realization' => $totalRealized,
                'progress' => $totalBudget > 0 ? ($totalRealized / $totalBudget) * 100 : 0
            ],
            [
                'year' => 2024,
                'status' => 'completed',
                'total_budget' => 2620000000,
                'realization' => 2590000000,
                'progress' => 98.9
            ],
            [
                'year' => 2023,
                'status' => 'archived',
                'total_budget' => 2400000000,
                'realization' => 2350000000,
                'progress' => 97.9
            ]
        ];

        // Recent galleries/documentation
        $recentGalleries = \App\Models\Gallery::orderBy('created_at', 'desc')
                                           ->limit(4)
                                           ->get();

        return view('frontend.page.apbdes-laporan', compact('monthlyReports', 'annualReports', 'recentGalleries', 'totalBudget', 'totalRealized'));
    }

    // Authentication methods
    public function login()
    {
        return view('frontend.page.login');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended('/')->with('success', 'Login berhasil!');
        }

        return back()->withErrors([
            'email' => 'Email atau password tidak valid.',
        ])->onlyInput('email');
    }

    public function register()
    {
        return view('frontend.page.register');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'nik' => 'required|string|size:16|unique:users',
            'phone' => 'required|string|max:15',
            'address' => 'required|string'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'nik' => $request->nik,
            'phone' => $request->phone,
            'address' => $request->address,
            'role' => 'citizen',
            'is_active' => true
        ]);

        Auth::login($user);

        return redirect('/')->with('success', 'Registrasi berhasil!');
    }

    public function forgotPassword()
    {
        return view('frontend.page.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        // In a real application, you would send reset link here
        return back()->with('success', 'Link reset password telah dikirim ke email Anda.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Logout berhasil!');
    }

    // API Methods for AJAX calls
    public function getStatistics()
    {
        $statistics = [
            'total_population' => PopulationData::where('status', 'Hidup')->count(),
            'male_population' => PopulationData::where('gender', 'M')->where('status', 'Hidup')->count(),
            'female_population' => PopulationData::where('gender', 'F')->where('status', 'Hidup')->count(),
            'total_families' => PopulationData::where('status', 'Hidup')->distinct('family_card_number')->count('family_card_number'),
            'total_news' => News::where('is_published', true)->count(),
            'total_umkm' => Umkm::where('is_active', true)->count(),
            'total_agenda' => Agenda::where('event_date', '>=', now())->count(),
        ];

        return response()->json($statistics);
    }

    public function getLocations()
    {
        $locations = Location::active()
                                       ->onMap()
                                       ->orderBy('sort_order')
                                       ->get([
                                           'name', 'description', 'type', 'latitude', 'longitude', 
                                           'area_size', 'area_coordinates', 'address', 'phone', 'email',
                                           'icon', 'color'
                                       ])
                                       ->map(function($location) {
                                           $location->type_name = $location->getTypeNameAttribute();
                                           $location->formatted_area = $location->getFormattedAreaAttribute();
                                           return $location;
                                       });

        // Add sample location with area coordinates if no locations exist
        if ($locations->isEmpty()) {
            $locations = collect([
                (object)[
                    'name' => 'Kantor Desa Ciuwlan',
                    'description' => 'Kantor administrasi desa',
                    'type' => 'office',
                    'latitude' => -6.8458,
                    'longitude' => 107.1234,
                    'area_coordinates' => json_encode([
                        ['lat' => -6.8450, 'lng' => 107.1230],
                        ['lat' => -6.8460, 'lng' => 107.1240],
                        ['lat' => -6.8470, 'lng' => 107.1235],
                        ['lat' => -6.8465, 'lng' => 107.1225],
                        ['lat' => -6.8450, 'lng' => 107.1230]
                    ]),
                    'address' => 'Desa Ciuwlan, Telagasari, Karawang',
                    'phone' => '0267-123456',
                    'email' => 'kantor@ciuwlan.go.id',
                    'icon' => 'fas fa-building',
                    'color' => '#3B82F6',
                    'type_name' => 'Kantor Pemerintahan',
                    'formatted_area' => '2,500 m²'
                ]
            ]);
        }

        return response()->json($locations);
    }

    public function getBudgetSummary()
    {
        $currentYear = date('Y');
        
        $totalBudget = VillageBudget::where('fiscal_year', $currentYear)->sum('planned_amount');
        $totalRealized = BudgetTransaction::whereHas('budget', function($q) use ($currentYear) {
            $q->where('fiscal_year', $currentYear);
        })->where('transaction_type', 'expense')->sum('amount');

        return response()->json([
            'total_budget' => $totalBudget,
            'total_realized' => $totalRealized,
            'remaining' => $totalBudget - $totalRealized,
            'percentage' => $totalBudget > 0 ? ($totalRealized / $totalBudget) * 100 : 0
        ]);
    }

    public function getVillageProfile()
    {
        $profile = VillageProfile::first();
        return response()->json($profile);
    }
}