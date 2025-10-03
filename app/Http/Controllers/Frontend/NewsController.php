<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\Announcement;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $query = News::where('is_published', true);

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Search functionality
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('content', 'like', '%' . $request->search . '%')
                  ->orWhere('excerpt', 'like', '%' . $request->search . '%');
            });
        }

        $news = $query->with('author')
                     ->orderBy('published_at', 'desc')
                     ->paginate(9);

        // Get categories for filter
        $categories = News::where('is_published', true)
                         ->distinct()
                         ->pluck('category')
                         ->filter()
                         ->sort();

        // Get featured news for hero section
        $featuredNews = News::where('is_published', true)
                           ->where('is_featured', true)
                           ->orderBy('published_at', 'desc')
                           ->first();

        // Calculate news statistics
        $newsStats = [
            'total' => News::count(),
            'published' => News::where('is_published', true)->count(),
            'this_month' => News::where('is_published', true)
                               ->whereMonth('published_at', now()->month)
                               ->whereYear('published_at', now()->year)
                               ->count(),
            'total_views' => News::where('is_published', true)->sum('views_count') ?? 0
        ];

        return view('frontend.page.berita', compact('news', 'categories', 'featuredNews', 'newsStats'));
    }

    public function show($slug)
    {
        $news = News::where('slug', $slug)
                   ->where('is_published', true)
                   ->with('author')
                   ->firstOrFail();

        // Increment views count
        $news->increment('views_count');

        // Get related news
        $relatedNews = News::where('category', $news->category)
                          ->where('id', '!=', $news->id)
                          ->where('is_published', true)
                          ->limit(4)
                          ->get();

        // Get recent news
        $recentNews = News::where('is_published', true)
                         ->where('id', '!=', $news->id)
                         ->orderBy('published_at', 'desc')
                         ->limit(5)
                         ->get();

        return view('frontend.news.show', compact('news', 'relatedNews', 'recentNews'));
    }

    public function category($category)
    {
        $news = News::where('is_published', true)
                   ->where('category', $category)
                   ->with('author')
                   ->orderBy('published_at', 'desc')
                   ->paginate(12);

        $categoryName = ucfirst(str_replace('_', ' ', $category));

        return view('frontend.news.category', compact('news', 'category', 'categoryName'));
    }

    public function announcements(Request $request)
    {
        $query = Announcement::where('is_active', true);

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Search functionality
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('content', 'like', '%' . $request->search . '%');
            });
        }

        $announcements = $query->orderBy('created_at', 'desc')->paginate(6);

        // Get the most important announcement for hero section
        $importantAnnouncement = Announcement::where('is_active', true)
                                            ->where('priority', 'urgent')
                                            ->orderBy('created_at', 'desc')
                                            ->first();

        // Get categories for filter
        $categories = Announcement::where('is_active', true)
                                 ->distinct()
                                 ->pluck('category')
                                 ->filter()
                                 ->sort();

        // Calculate announcement statistics
        $announcementStats = [
            'this_month' => Announcement::where('is_active', true)
                                      ->whereMonth('created_at', now()->month)
                                      ->whereYear('created_at', now()->year)
                                      ->count(),
            'this_year' => Announcement::where('is_active', true)
                                     ->whereYear('created_at', now()->year)
                                     ->count(),
            'read_percentage' => 89, // This would be calculated from actual read tracking
            'rating' => 4.8 // This would come from user ratings
        ];

        return view('frontend.page.pengumuman', compact(
            'announcements', 
            'importantAnnouncement', 
            'categories', 
            'announcementStats'
        ));
    }

    public function announcementShow($id)
    {
        $announcement = Announcement::where('id', $id)
                                  ->where('is_active', true)
                                  ->firstOrFail();

        // Get related announcements
        $relatedAnnouncements = Announcement::where('is_active', true)
                                          ->where('category', $announcement->category)
                                          ->where('id', '!=', $announcement->id)
                                          ->limit(5)
                                          ->get();

        return view('frontend.announcements.show', compact('announcement', 'relatedAnnouncements'));
    }

    // API Methods
    public function getRecentNews()
    {
        $news = News::where('is_published', true)
                   ->with('user:id,name')
                   ->orderBy('published_at', 'desc')
                   ->limit(6)
                   ->get(['id', 'title', 'slug', 'excerpt', 'featured_image', 'category', 'published_at', 'user_id']);

        return response()->json($news);
    }

    public function getNewsDetail($slug)
    {
        $news = News::where('slug', $slug)
                   ->where('is_published', true)
                   ->with('user:id,name')
                   ->firstOrFail();

        // Increment views count
        $news->increment('views_count');

        return response()->json($news);
    }

    public function getNewsByCategory($category)
    {
        $news = News::where('is_published', true)
                   ->where('category', $category)
                   ->with('user:id,name')
                   ->orderBy('published_at', 'desc')
                   ->paginate(10);

        return response()->json($news);
    }

    public function getAnnouncements()
    {
        $announcements = Announcement::where('is_active', true)
                                   ->orderBy('created_at', 'desc')
                                   ->limit(5)
                                   ->get(['id', 'title', 'slug', 'content', 'priority', 'valid_until', 'created_at']);

        return response()->json($announcements);
    }

    public function search(Request $request)
    {
        $query = $request->get('q');
        
        if (!$query) {
            return response()->json(['data' => []]);
        }

        $news = News::where('is_published', true)
                   ->where(function ($q) use ($query) {
                       $q->where('title', 'like', '%' . $query . '%')
                         ->orWhere('content', 'like', '%' . $query . '%')
                         ->orWhere('excerpt', 'like', '%' . $query . '%');
                   })
                   ->with('user:id,name')
                   ->orderBy('published_at', 'desc')
                   ->limit(10)
                   ->get(['id', 'title', 'slug', 'excerpt', 'featured_image', 'category', 'published_at', 'user_id']);

        return response()->json(['data' => $news]);
    }

    public function getLatest(Request $request)
    {
        // Get the latest news count (for notification purposes)
        $lastCheckTime = $request->get('last_check', now()->subMinutes(5));
        
        $newCount = News::where('is_published', true)
                       ->where('published_at', '>', $lastCheckTime)
                       ->count();

        $latestNews = News::where('is_published', true)
                         ->orderBy('published_at', 'desc')
                         ->limit(3)
                         ->get(['id', 'title', 'slug', 'published_at']);

        return response()->json([
            'newCount' => $newCount,
            'latestNews' => $latestNews,
            'timestamp' => now()
        ]);
    }
}