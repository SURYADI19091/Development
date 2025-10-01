<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Umkm;
use App\Models\UmkmReview;
use App\Models\VillageProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UmkmController extends Controller
{
    public function index(Request $request)
    {
        $query = Umkm::where('is_active', true);

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Search functionality
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('business_name', 'like', '%' . $request->search . '%')
                  ->orWhere('owner_name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%')
                  ->orWhere('products', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by rating
        if ($request->filled('rating')) {
            $query->where('rating', '>=', $request->rating);
        }

        $umkms = $query->orderBy('is_verified', 'desc')
                      ->orderBy('rating', 'desc')
                      ->paginate(6);

        // Get statistics for overview
        $statistics = [
            'total_umkm' => Umkm::where('is_active', true)->count(),
            'total_workers' => Umkm::where('is_active', true)->sum('employee_count'),
            'monthly_revenue' => Umkm::where('is_active', true)->sum('monthly_revenue'),
            'categories_count' => Umkm::where('is_active', true)->distinct('category')->count('category'),
        ];

        // Get category statistics
        $categoryStats = Umkm::where('is_active', true)
                            ->selectRaw('category, COUNT(*) as count')
                            ->groupBy('category')
                            ->get();

        // Get village profile
        $villageProfile = VillageProfile::first();

        return view('frontend.umkm.index', compact('umkms', 'statistics', 'categoryStats', 'villageProfile'));
    }

    public function show($slug)
    {
        $umkm = Umkm::where('slug', $slug)
                   ->where('is_active', true)
                   ->firstOrFail();

        // Get related UMKM in same category
        $relatedUmkm = Umkm::where('is_active', true)
                          ->where('category', $umkm->category)
                          ->where('id', '!=', $umkm->id)
                          ->limit(4)
                          ->get();

        // Get village profile
        $villageProfile = VillageProfile::first();

        return view('frontend.umkm.show', compact('umkm', 'relatedUmkm', 'villageProfile'));
    }

    public function category($category)
    {
        $umkms = Umkm::where('is_active', true)
                    ->where('category', $category)
                    ->orderBy('rating', 'desc')
                    ->paginate(12);

        $categoryName = ucfirst(str_replace('_', ' ', $category));

        // Get village profile
        $villageProfile = VillageProfile::first();

        return view('frontend.umkm.category', compact('umkms', 'category', 'categoryName', 'villageProfile'));
    }

    public function submitReview(Request $request, $id)
    {
        $umkm = Umkm::where('is_active', true)->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'reviewer_name' => 'required|string|max:255',
            'reviewer_email' => 'required|email|max:255',
            'rating' => 'required|numeric|min:1|max:5',
            'review_text' => 'required|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Check if user has already reviewed this UMKM
        $existingReview = UmkmReview::where('umkm_id', $umkm->id)
                                  ->where('reviewer_email', $request->reviewer_email)
                                  ->first();

        if ($existingReview) {
            return redirect()->back()->with('error', 'You have already reviewed this business.');
        }

        UmkmReview::create([
            'umkm_id' => $umkm->id,
            'reviewer_name' => $request->reviewer_name,
            'reviewer_email' => $request->reviewer_email,
            'rating' => $request->rating,
            'review_text' => $request->review_text,
            'is_verified' => false, // Will be verified by admin
        ]);

        // Update UMKM rating
        $this->updateUmkmRating($umkm);

        return redirect()->back()->with('success', 'Review submitted successfully! It will be published after verification.');
    }

    private function updateUmkmRating($umkm)
    {
        $reviews = UmkmReview::where('umkm_id', $umkm->id)
                            ->where('is_verified', true)
                            ->get();

        if ($reviews->count() > 0) {
            $averageRating = $reviews->avg('rating');
            $umkm->update([
                'rating' => round($averageRating, 2),
                'total_reviews' => $reviews->count()
            ]);
        }
    }

    public function verified()
    {
        $verifiedUmkms = Umkm::where('is_active', true)
                            ->where('is_verified', true)
                            ->orderBy('rating', 'desc')
                            ->paginate(12);

        // Get village profile
        $villageProfile = VillageProfile::first();

        return view('frontend.umkm.verified', compact('verifiedUmkms', 'villageProfile'));
    }

    // API Methods
    public function getUmkm()
    {
        $umkms = Umkm::where('is_active', true)
                    ->orderBy('rating', 'desc')
                    ->limit(12)
                    ->get(['id', 'business_name', 'slug', 'owner_name', 'category', 'description', 'rating', 'is_verified']);

        return response()->json($umkms);
    }

    public function getUmkmDetail($slug)
    {
        $umkm = Umkm::where('slug', $slug)
                   ->where('is_active', true)
                   ->firstOrFail();

        return response()->json($umkm);
    }

    public function getUmkmByCategory($category)
    {
        $umkms = Umkm::where('is_active', true)
                    ->where('category', $category)
                    ->orderBy('rating', 'desc')
                    ->paginate(10);

        return response()->json($umkms);
    }

    public function search(Request $request)
    {
        $query = $request->get('q');
        
        if (!$query) {
            return response()->json(['data' => []]);
        }

        $umkms = Umkm::where('is_active', true)
                    ->where(function ($q) use ($query) {
                        $q->where('business_name', 'like', '%' . $query . '%')
                          ->orWhere('owner_name', 'like', '%' . $query . '%')
                          ->orWhere('description', 'like', '%' . $query . '%')
                          ->orWhere('products', 'like', '%' . $query . '%');
                    })
                    ->orderBy('rating', 'desc')
                    ->limit(10)
                    ->get(['id', 'business_name', 'slug', 'owner_name', 'category', 'description', 'rating']);

        return response()->json(['data' => $umkms]);
    }

    public function filterUmkm(Request $request)
    {
        $query = Umkm::where('is_active', true);

        // Filter by category
        if ($request->filled('category') && $request->category !== '') {
            $query->where('category', $request->category);
        }

        // Search functionality
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('business_name', 'like', '%' . $request->search . '%')
                  ->orWhere('owner_name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%')
                  ->orWhere('products', 'like', '%' . $request->search . '%');
            });
        }

        $umkms = $query->orderBy('is_verified', 'desc')
                      ->orderBy('rating', 'desc')
                      ->paginate(6);

        // Get statistics
        $statistics = [
            'total_umkm' => Umkm::where('is_active', true)->count(),
            'total_workers' => Umkm::where('is_active', true)->sum('employee_count'),
            'monthly_revenue' => Umkm::where('is_active', true)->sum('monthly_revenue'),
            'categories_count' => Umkm::where('is_active', true)->distinct('category')->count('category'),
        ];

        // Get category statistics
        $categoryStats = Umkm::where('is_active', true)
                            ->selectRaw('category, COUNT(*) as count')
                            ->groupBy('category')
                            ->get();

        return response()->json([
            'umkms' => $umkms,
            'statistics' => $statistics,
            'categoryStats' => $categoryStats,
            'html' => view('frontend.umkm.partials.umkm-cards', compact('umkms'))->render(),
            'pagination' => view('frontend.umkm.partials.umkm-pagination', compact('umkms'))->render(),
            'stats' => view('frontend.umkm.partials.umkm-stats', compact('categoryStats'))->render()
        ]);
    }
}