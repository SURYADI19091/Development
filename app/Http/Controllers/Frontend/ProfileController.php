<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\VillageProfile;
use App\Models\VillageOfficial;
use App\Models\TourismObject;
use App\Models\CommunityInstitution;
use App\Models\Infrastructure;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index()
    {
        $villageProfile = VillageProfile::first();
        
        // Get village statistics
        $statistics = [
            'officials_count' => VillageOfficial::where('is_active', true)->count(),
            'tourism_count' => TourismObject::where('is_active', true)->count(),
            'area_size' => $villageProfile->area_size ?? 0,
            'population' => $villageProfile->total_population ?? 0
        ];
        
        // Get infrastructure data from database
        $infrastructure = Infrastructure::getGroupedData();
        
        return view('frontend.page.profil', compact('villageProfile', 'statistics', 'infrastructure'));
    }
    
    public function history()
    {
        $villageProfile = VillageProfile::first();
        
        return view('frontend.page.sejarah', compact('villageProfile'));
    }
    
    public function visionMission()
    {
        $villageProfile = VillageProfile::first();
        
        return view('frontend.page.visi-misi', compact('villageProfile'));
    }
    
    public function government()
    {
        $villageProfile = VillageProfile::first();
        
        // Get village officials grouped by position
        $officials = \App\Models\VillageOfficial::where('is_active', true)
                                                ->orderBy('position')
                                                ->orderBy('start_date', 'desc')
                                                ->get();
        
        $groupedOfficials = $officials->groupBy('position');
        
        // Get BPD members (stored as staff with BPD specialization)
        $bpdMembers = \App\Models\VillageOfficial::where('is_active', true)
                                               ->where('position', 'staff')
                                               ->where('specialization', 'LIKE', '%BPD%')
                                               ->orderByRaw("CASE 
                                                   WHEN specialization LIKE '%Ketua BPD%' THEN 1
                                                   WHEN specialization LIKE '%Wakil Ketua BPD%' THEN 2  
                                                   WHEN specialization LIKE '%Sekretaris BPD%' THEN 3
                                                   ELSE 4
                                               END")
                                               ->get();
        
        // Get population data for kadus areas - using settlement relationship
        $populationByArea = \App\Models\PopulationData::join('settlements', 'population_data.settlement_id', '=', 'settlements.id')
            ->selectRaw('
                settlements.hamlet_name as area,
                COUNT(population_data.id) as total_people,
                COUNT(DISTINCT population_data.family_card_number) as total_families
            ')
            ->groupBy('settlements.hamlet_name')
            ->get()
            ->keyBy('area');
            
        // If no settlement data, create mock data for display
        if ($populationByArea->isEmpty()) {
            $populationByArea = collect([
                'Dusun I' => (object)['area' => 'Dusun I', 'total_people' => 892, 'total_families' => 285],
                'Dusun II' => (object)['area' => 'Dusun II', 'total_people' => 925, 'total_families' => 298],
                'Dusun III' => (object)['area' => 'Dusun III', 'total_people' => 845, 'total_families' => 272],
                'Dusun IV' => (object)['area' => 'Dusun IV', 'total_people' => 370, 'total_families' => 131],
            ]);
        }
        
        // Get community institutions
        $communityInstitutions = CommunityInstitution::where('is_active', true)
                                                     ->orderBy('type')
                                                     ->orderBy('name')
                                                     ->get();
        
        return view('frontend.page.struktur-pemerintahan', compact(
            'villageProfile', 
            'officials', 
            'groupedOfficials', 
            'populationByArea',
            'bpdMembers',
            'communityInstitutions'
        ));
    }
    
    public function officials()
    {
        $officials = VillageOfficial::where('is_active', true)
                                   ->orderBy('order')
                                   ->get();
        
        return view('frontend.page.perangkat-desa', compact('officials'));
    }
    
    public function tourism()
    {
        $tourism = TourismObject::where('is_active', true)
                               ->orderBy('created_at', 'desc')
                               ->paginate(12);
        
        $featuredTourism = TourismObject::where('is_active', true)
                                       ->where('is_featured', true)
                                       ->take(3)
                                       ->get();
        
        return view('frontend.page.wisata', compact('tourism', 'featuredTourism'));
    }
    
    public function tourismShow($slug)
    {
        $tourism = TourismObject::where('slug', $slug)
                               ->where('is_active', true)
                               ->firstOrFail();
        
        $relatedTourism = TourismObject::where('category', $tourism->category)
                                      ->where('id', '!=', $tourism->id)
                                      ->where('is_active', true)
                                      ->take(3)
                                      ->get();
        
        return view('frontend.tourism.show', compact('tourism', 'relatedTourism'));
    }
    
    public function contact()
    {
        $villageProfile = VillageProfile::first();
        
        return view('frontend.page.kontak', compact('villageProfile'));
    }
    
    public function submitContact(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10'
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'subject.required' => 'Kategori pesan wajib dipilih.',
            'message.required' => 'Pesan wajib diisi.',
            'message.min' => 'Pesan minimal 10 karakter.'
        ]);
        
        try {
            // Save contact message to database
            ContactMessage::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'subject' => $request->subject,
                'message' => $request->message,
                'status' => 'unread',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);
            
            return redirect()->route('contact')
                           ->with('success', 'Pesan Anda berhasil dikirim dan akan segera ditindaklanjuti. Terima kasih!');
                           
        } catch (\Exception $e) {
            return redirect()->route('contact')
                           ->with('error', 'Terjadi kesalahan saat mengirim pesan. Silakan coba lagi.')
                           ->withInput();
        }
    }

    public function geography()
    {
        $villageProfile = VillageProfile::first();
        
        return view('frontend.page.geografis', compact('villageProfile'));
    }

    public function demographics()
    {
        $villageProfile = VillageProfile::first();
        
        // Get demographic statistics if available
        $demographics = [
            'total_population' => $villageProfile->total_population ?? 0,
            'male_population' => $villageProfile->male_population ?? 0,
            'female_population' => $villageProfile->female_population ?? 0,
            'total_families' => $villageProfile->total_families ?? 0,
            'total_area' => $villageProfile->total_area ?? 0
        ];
        
        return view('frontend.page.demografi', compact('villageProfile', 'demographics'));
    }

    public function facilities()
    {
        $villageProfile = VillageProfile::first();
        
        return view('frontend.page.fasilitas', compact('villageProfile'));
    }

    // API Methods
    public function getVillageData()
    {
        $villageProfile = VillageProfile::first();
        
        if (!$villageProfile) {
            return response()->json(['error' => 'Village profile not found'], 404);
        }

        return response()->json([
            'name' => $villageProfile->village_name,
            'head_name' => $villageProfile->head_name,
            'address' => $villageProfile->address,
            'phone' => $villageProfile->phone,
            'email' => $villageProfile->email,
            'website' => $villageProfile->website,
            'total_population' => $villageProfile->total_population,
            'total_area' => $villageProfile->total_area
        ]);
    }

    public function getOfficials()
    {
        $officials = VillageOfficial::where('is_active', true)
                                   ->orderBy('order')
                                   ->get(['id', 'name', 'position', 'photo', 'phone', 'email']);

        return response()->json($officials);
    }

    public function getTourismObjects()
    {
        $tourism = TourismObject::where('is_active', true)
                               ->orderBy('created_at', 'desc')
                               ->get(['id', 'name', 'category', 'address', 'photo', 'rating', 'slug']);

        return response()->json($tourism);
    }
}