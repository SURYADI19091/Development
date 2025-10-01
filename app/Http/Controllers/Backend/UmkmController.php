<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Umkm;
use App\Models\Settlement;
use App\Models\UmkmReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UmkmController extends Controller
{
    public function index(Request $request)
    {
        $query = Umkm::with('settlement');

        // Search
        if ($request->has('search') && $request->search != '') {
            $query->where(function ($q) use ($request) {
                $q->where('business_name', 'like', '%' . $request->search . '%')
                  ->orWhere('owner_name', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by category
        if ($request->has('category') && $request->category != '') {
            $query->where('category', $request->category);
        }

        // Filter by status
        if ($request->has('status')) {
            if ($request->status == 'verified') {
                $query->where('is_verified', true);
            } elseif ($request->status == 'unverified') {
                $query->where('is_verified', false);
            } elseif ($request->status == 'active') {
                $query->where('is_active', true);
            } elseif ($request->status == 'inactive') {
                $query->where('is_active', false);
            }
        }

        $umkms = $query->orderBy('created_at', 'desc')->paginate(15);

        $stats = [
            'total' => Umkm::count(),
            'active' => Umkm::where('is_active', true)->count(),
            'verified' => Umkm::where('is_verified', true)->count(),
            'categories' => Umkm::selectRaw('category, COUNT(*) as count')
                                ->groupBy('category')
                                ->pluck('count', 'category'),
        ];

        return view('backend.umkm.index', compact('umkms', 'stats'));
    }

    public function create()
    {
        $settlements = Settlement::where('is_active', true)->get();
        $categories = ['makanan', 'kerajinan', 'pertanian', 'jasa', 'tekstil', 'lainnya'];
        return view('backend.umkm.create', compact('settlements', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'business_name' => 'required|string|max:255',
            'owner_name' => 'required|string|max:255',
            'category' => 'required|in:makanan,kerajinan,pertanian,jasa,tekstil,lainnya',
            'description' => 'nullable|string',
            'address' => 'required|string',
            'settlement_id' => 'nullable|exists:settlements,id',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email',
            'website' => 'nullable|url',
            'operating_hours' => 'nullable|string',
            'products' => 'nullable|string',
            'services' => 'nullable|string',
            'price_range' => 'nullable|string|max:100',
            'employee_count' => 'integer|min:1',
            'monthly_revenue' => 'nullable|numeric|min:0',
            'logo_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'photos.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
            'is_verified' => 'boolean',
            'registered_at' => 'nullable|date',
        ]);

        $data = $request->all();

        // Handle logo upload
        if ($request->hasFile('logo_path')) {
            $logoPath = $request->file('logo_path')->store('umkm/logos', 'public');
            $data['logo_path'] = $logoPath;
        }

        // Handle photos upload
        if ($request->hasFile('photos')) {
            $photoPaths = [];
            foreach ($request->file('photos') as $photo) {
                $photoPaths[] = $photo->store('umkm/photos', 'public');
            }
            $data['photos'] = $photoPaths;
        }

        Umkm::create($data);

        return redirect()->route('backend.umkm.index')
                        ->with('success', 'UMKM created successfully!');
    }

    public function show($id)
    {
        $umkm = Umkm::with(['settlement', 'reviews'])->findOrFail($id);
        return view('backend.umkm.show', compact('umkm'));
    }

    public function edit($id)
    {
        $umkm = Umkm::findOrFail($id);
        $settlements = Settlement::where('is_active', true)->get();
        $categories = ['makanan', 'kerajinan', 'pertanian', 'jasa', 'tekstil', 'lainnya'];
        return view('backend.umkm.edit', compact('umkm', 'settlements', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $umkm = Umkm::findOrFail($id);

        $request->validate([
            'business_name' => 'required|string|max:255',
            'owner_name' => 'required|string|max:255',
            'category' => 'required|in:makanan,kerajinan,pertanian,jasa,tekstil,lainnya',
            'description' => 'nullable|string',
            'address' => 'required|string',
            'settlement_id' => 'nullable|exists:settlements,id',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email',
            'website' => 'nullable|url',
            'operating_hours' => 'nullable|string',
            'products' => 'nullable|string',
            'services' => 'nullable|string',
            'price_range' => 'nullable|string|max:100',
            'employee_count' => 'integer|min:1',
            'monthly_revenue' => 'nullable|numeric|min:0',
            'logo_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'photos.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
            'is_verified' => 'boolean',
            'registered_at' => 'nullable|date',
        ]);

        $data = $request->all();

        // Handle logo upload
        if ($request->hasFile('logo_path')) {
            // Delete old logo
            if ($umkm->logo_path) {
                Storage::disk('public')->delete($umkm->logo_path);
            }
            $logoPath = $request->file('logo_path')->store('umkm/logos', 'public');
            $data['logo_path'] = $logoPath;
        }

        // Handle photos upload
        if ($request->hasFile('photos')) {
            $photoPaths = [];
            foreach ($request->file('photos') as $photo) {
                $photoPaths[] = $photo->store('umkm/photos', 'public');
            }
            $data['photos'] = array_merge($umkm->photos ?? [], $photoPaths);
        }

        $umkm->update($data);

        return redirect()->route('backend.umkm.index')
                        ->with('success', 'UMKM updated successfully!');
    }

    public function destroy($id)
    {
        $umkm = Umkm::findOrFail($id);
        
        // Delete logo and photos
        if ($umkm->logo_path) {
            Storage::disk('public')->delete($umkm->logo_path);
        }
        
        if ($umkm->photos) {
            foreach ($umkm->photos as $photo) {
                Storage::disk('public')->delete($photo);
            }
        }

        $umkm->delete();

        return redirect()->route('backend.umkm.index')
                        ->with('success', 'UMKM deleted successfully!');
    }

    public function toggleStatus(Request $request, $id)
    {
        $umkm = Umkm::findOrFail($id);
        
        if ($request->type == 'active') {
            $umkm->is_active = !$umkm->is_active;
            $status = $umkm->is_active ? 'activated' : 'deactivated';
        } elseif ($request->type == 'verified') {
            $umkm->is_verified = !$umkm->is_verified;
            $status = $umkm->is_verified ? 'verified' : 'unverified';
        }
        
        $umkm->save();

        return response()->json(['message' => "UMKM {$status} successfully!"]);
    }

    public function reviews($id)
    {
        $umkm = Umkm::findOrFail($id);
        $reviews = UmkmReview::where('umkm_id', $id)
                            ->orderBy('created_at', 'desc')
                            ->paginate(10);

        return view('backend.umkm.reviews', compact('umkm', 'reviews'));
    }

    public function verifyReview(Request $request, $reviewId)
    {
        $review = UmkmReview::findOrFail($reviewId);
        $review->is_verified = !$review->is_verified;
        $review->save();

        // Update UMKM rating
        $this->updateUmkmRating($review->umkm);

        $status = $review->is_verified ? 'verified' : 'unverified';
        return response()->json(['message' => "Review {$status} successfully!"]);
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
}