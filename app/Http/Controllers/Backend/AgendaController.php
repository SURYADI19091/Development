<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Agenda;
use App\Traits\HasPagination;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AgendaController extends Controller
{
    use HasPagination;

    public function index(Request $request)
    {
        $query = Agenda::query();
        
        // Apply search
        $searchableFields = ['title', 'description', 'location'];
        $query = $this->applySearch($query, $request, $searchableFields);

        // Apply filters
        $filters = [
            'category' => 'category',
            'status' => 'status',
            'month' => [
                'callback' => function ($query, $value) {
                    return $query->whereMonth('event_date', $value);
                }
            ]
        ];
        $query = $this->applyFilters($query, $request, $filters);

        // Apply sorting
        $query = $this->applySorting($query, $request, 'event_date', 'asc');

        // Paginate results
        $agendas = $this->paginateQuery($query, $request);

        // Get statistics
        $stats = [
            'total' => Agenda::count(),
            'upcoming' => Agenda::where('event_date', '>=', now())->count(),
            'this_month' => Agenda::whereMonth('event_date', now()->month)->count(),
            'completed' => Agenda::where('is_completed', true)->count()
        ];

        // Get filter options
        $categories = Agenda::distinct()->whereNotNull('category')->pluck('category');

        // Prepare pagination info
        $paginationInfo = $this->getPaginationInfo($agendas);

        return view('backend.pages.agenda.index', compact('agendas', 'categories', 'stats', 'paginationInfo'));
    }
    
    public function create()
    {
        return view('backend.agenda.create');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|in:rapat,pelayanan,olahraga,gotong_royong,keagamaan,pendidikan,kesehatan,budaya',
            'event_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'location' => 'required|string|max:255',
            'organizer' => 'nullable|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'max_participants' => 'nullable|integer|min:1',
            'is_public' => 'boolean',
            'is_completed' => 'boolean'
        ]);
        
        Agenda::create([
            'title' => $request->title,
            'description' => $request->description,
            'category' => $request->category,
            'event_date' => $request->event_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'location' => $request->location,
            'organizer' => $request->organizer,
            'contact_person' => $request->contact_person,
            'contact_phone' => $request->contact_phone,
            'max_participants' => $request->max_participants,
            'is_public' => $request->boolean('is_public', true),
            'is_completed' => $request->boolean('is_completed', false),
            'created_by' => auth()->id()
        ]);
        
        return redirect()->route('backend.agenda.index')
                         ->with('success', 'Agenda berhasil ditambahkan.');
    }
    
    public function show(Agenda $agenda)
    {
        return view('backend.agenda.show', compact('agenda'));
    }
    
    public function edit(Agenda $agenda)
    {
        return view('backend.agenda.edit', compact('agenda'));
    }
    
    public function update(Request $request, Agenda $agenda)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|in:rapat,pelayanan,olahraga,gotong_royong,keagamaan,pendidikan,kesehatan,budaya',
            'event_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'location' => 'required|string|max:255',
            'organizer' => 'nullable|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'max_participants' => 'nullable|integer|min:1',
            'is_public' => 'boolean',
            'is_completed' => 'boolean'
        ]);
        
        $agenda->update([
            'title' => $request->title,
            'description' => $request->description,
            'category' => $request->category,
            'event_date' => $request->event_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'location' => $request->location,
            'organizer' => $request->organizer,
            'contact_person' => $request->contact_person,
            'contact_phone' => $request->contact_phone,
            'max_participants' => $request->max_participants,
            'is_public' => $request->boolean('is_public', true),
            'is_completed' => $request->boolean('is_completed', false)
        ]);
        
        return redirect()->route('backend.agenda.index')
                         ->with('success', 'Agenda berhasil diperbarui.');
    }
    
    public function destroy(Agenda $agenda)
    {
        $agenda->delete();
        
        return redirect()->route('backend.agenda.index')
                         ->with('success', 'Agenda berhasil dihapus.');
    }
    
    public function toggleStatus(Agenda $agenda)
    {
        $newCompleted = !$agenda->is_completed;
        
        $agenda->update(['is_completed' => $newCompleted]);
        
        $statusText = $newCompleted ? 'ditandai selesai' : 'ditandai belum selesai';
        
        return response()->json([
            'success' => true,
            'message' => "Agenda berhasil {$statusText}.",
            'is_completed' => $newCompleted
        ]);
    }
    
    public function export(Request $request)
    {
        // Get agendas with applied filters
        $query = Agenda::query();
        
        // Apply filters from request
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%')
                  ->orWhere('location', 'like', '%' . $request->search . '%');
            });
        }
        
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        
        if ($request->filled('is_completed')) {
            $query->where('is_completed', $request->is_completed);
        }
        
        if ($request->filled('month')) {
            $query->whereMonth('event_date', $request->month);
        }
        
        if ($request->filled('year')) {
            $query->whereYear('event_date', $request->year);
        }
        
        // For now, return a message indicating export functionality needs implementation
        // You can implement Excel export using maatwebsite/excel package
        return redirect()->back()->with('info', 'Export functionality untuk agenda akan segera tersedia. Silakan gunakan fitur pencetakan browser untuk sementara.');
    }
}