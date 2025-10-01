<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\GalleryLike;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GalleryController extends Controller
{
    public function index(Request $request)
    {
        $query = Gallery::query();

        // Filter by category
        if ($request->has('category') && $request->category != '') {
            $query->where('category', $request->category);
        }

        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        $galleries = $query->orderBy('created_at', 'desc')
                          ->paginate(12);

        $categories = Gallery::distinct()->pluck('category');
        
        // Get village profile for page data
        $villageProfile = \App\Models\VillageProfile::first();

        // If no galleries exist, you can optionally create some sample data or just show empty state
        // For now, we'll just show the empty state when no galleries are found

        return view('frontend.gallery.index', compact('galleries', 'categories', 'villageProfile'));
    }

    public function show($id)
    {
        $gallery = Gallery::with('uploader')->findOrFail($id);
        
        // Increment views count
        $gallery->increment('views_count');

        // Check if user has liked this gallery
        $hasLiked = false;
        if (Auth::check()) {
            $hasLiked = GalleryLike::where('gallery_id', $gallery->id)
                                  ->where('user_id', Auth::id())
                                  ->exists();
        }
        
        // Get village profile for page data
        $villageProfile = \App\Models\VillageProfile::first();

        return view('frontend.gallery.show', compact('gallery', 'hasLiked', 'villageProfile'));
    }

    public function like(Request $request, $id)
    {
        $gallery = Gallery::findOrFail($id);
        $userId = Auth::id();
        $ipAddress = $request->ip();

        if ($userId) {
            // For authenticated users
            $existingLike = GalleryLike::where('gallery_id', $gallery->id)
                                     ->where('user_id', $userId)
                                     ->first();

            if ($existingLike) {
                $existingLike->delete();
                $gallery->decrement('likes_count');
                $liked = false;
            } else {
                GalleryLike::create([
                    'gallery_id' => $gallery->id,
                    'user_id' => $userId,
                    'ip_address' => $ipAddress,
                ]);
                $gallery->increment('likes_count');
                $liked = true;
            }
        } else {
            // For anonymous users (based on IP)
            $existingLike = GalleryLike::where('gallery_id', $gallery->id)
                                     ->where('ip_address', $ipAddress)
                                     ->whereNull('user_id')
                                     ->first();

            if ($existingLike) {
                return response()->json(['error' => 'You have already liked this photo'], 400);
            }

            GalleryLike::create([
                'gallery_id' => $gallery->id,
                'user_id' => null,
                'ip_address' => $ipAddress,
            ]);
            $gallery->increment('likes_count');
            $liked = true;
        }

        return response()->json([
            'liked' => $liked,
            'likes_count' => $gallery->likes_count
        ]);
    }

    public function featured()
    {
        $featuredGalleries = Gallery::where('is_featured', true)
                                   ->orderBy('created_at', 'desc')
                                   ->paginate(12);
        
        // Get village profile for page data
        $villageProfile = \App\Models\VillageProfile::first();

        return view('frontend.gallery.featured', compact('featuredGalleries', 'villageProfile'));
    }

    // API Methods
    public function getRecentGallery()
    {
        $galleries = Gallery::orderBy('created_at', 'desc')
                           ->limit(6)
                           ->get(['id', 'title', 'image_path', 'category', 'likes_count', 'views_count', 'created_at']);

        return response()->json($galleries);
    }

    public function getFeaturedGallery()
    {
        $galleries = Gallery::where('is_featured', true)
                           ->orderBy('created_at', 'desc')
                           ->limit(8)
                           ->get(['id', 'title', 'image_path', 'category', 'likes_count', 'views_count']);

        return response()->json($galleries);
    }

    public function getGalleryStats()
    {
        $stats = [
            'total_photos' => Gallery::count(),
            'total_likes' => Gallery::sum('likes_count'),
            'total_views' => Gallery::sum('views_count'),
            'categories' => Gallery::distinct('category')->count('category'),
            'featured' => Gallery::where('is_featured', true)->count()
        ];

        return response()->json($stats);
    }

    public function getPopularGallery()
    {
        $galleries = Gallery::orderBy('likes_count', 'desc')
                           ->orderBy('views_count', 'desc')
                           ->limit(8)
                           ->get(['id', 'title', 'image_path', 'category', 'likes_count', 'views_count']);

        return response()->json($galleries);
    }
}