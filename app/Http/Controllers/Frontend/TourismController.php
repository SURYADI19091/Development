<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\TourismObject;
use Illuminate\Http\Request;

class TourismController extends Controller
{
    public function index(Request $request)
    {
        $query = TourismObject::where('is_active', true);

        // Filter by category
        if ($request->has('category') && $request->category != '') {
            $query->where('category', $request->category);
        }

        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by rating
        if ($request->has('rating') && $request->rating != '') {
            $query->where('rating', '>=', $request->rating);
        }

        $tourismObjects = $query->with('settlement')
                               ->orderBy('featured', 'desc')
                               ->orderBy('rating', 'desc')
                               ->paginate(12);

        $categories = TourismObject::distinct()->pluck('category');

        return view('frontend.tourism.index', compact('tourismObjects', 'categories'));
    }

    public function show($id)
    {
        $tourism = TourismObject::where('is_active', true)
                               ->with('settlement')
                               ->findOrFail($id);

        // Get nearby tourism objects
        $nearbyTourism = TourismObject::where('is_active', true)
                                    ->where('id', '!=', $tourism->id)
                                    ->where('settlement_id', $tourism->settlement_id)
                                    ->limit(4)
                                    ->get();

        return view('frontend.tourism.show', compact('tourism', 'nearbyTourism'));
    }

    public function featured()
    {
        $featuredTourism = TourismObject::where('is_active', true)
                                      ->where('featured', true)
                                      ->with('settlement')
                                      ->orderBy('rating', 'desc')
                                      ->paginate(12);

        return view('frontend.tourism.featured', compact('featuredTourism'));
    }
}