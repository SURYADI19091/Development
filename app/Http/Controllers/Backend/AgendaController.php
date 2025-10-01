<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Agenda;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AgendaController extends Controller
{
    public function index(Request $request)
    {
        $query = Agenda::query();
        
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%')
                  ->orWhere('location', 'like', '%' . $request->search . '%');
        }
        
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('month')) {
            $query->whereMonth('event_date', $request->month);
        }
        
        $agendas = $query->orderBy('event_date', 'asc')->paginate(20);
        
        return view('backend.agenda.index', compact('agendas'));
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
            'status' => 'required|in:draft,published,ongoing,completed,cancelled'
        ]);
        
        Agenda::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
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
            'status' => $request->status,
            'user_id' => auth()->id()
        ]);
        
        return redirect()->route('admin.agenda.index')
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
            'status' => 'required|in:draft,published,ongoing,completed,cancelled'
        ]);
        
        $agenda->update([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
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
            'status' => $request->status
        ]);
        
        return redirect()->route('admin.agenda.index')
                         ->with('success', 'Agenda berhasil diperbarui.');
    }
    
    public function destroy(Agenda $agenda)
    {
        $agenda->delete();
        
        return redirect()->route('admin.agenda.index')
                         ->with('success', 'Agenda berhasil dihapus.');
    }
    
    public function toggleStatus(Agenda $agenda)
    {
        $newStatus = $agenda->status === 'published' ? 'draft' : 'published';
        
        $agenda->update(['status' => $newStatus]);
        
        $statusText = $newStatus === 'published' ? 'dipublikasikan' : 'dijadikan draft';
        
        return response()->json([
            'success' => true,
            'message' => "Agenda berhasil {$statusText}.",
            'status' => $newStatus
        ]);
    }
}