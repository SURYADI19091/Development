<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Agenda;
use App\Models\AgendaParticipant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class AgendaController extends Controller
{
    public function index(Request $request)
    {
        $query = Agenda::where('is_public', true)
                      ->where('is_completed', false);

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Filter by month
        if ($request->filled('month')) {
            $query->whereMonth('event_date', $request->month);
        }

        $agendas = $query->orderBy('event_date', 'asc')
                        ->paginate(10);

        // Get today's events
        $todayEvents = Agenda::where('is_public', true)
                            ->whereDate('event_date', today())
                            ->orderBy('start_time', 'asc')
                            ->get();

        // Get upcoming events (next 5)
        $upcomingEvents = Agenda::where('is_public', true)
                               ->where('event_date', '>', now())
                               ->orderBy('event_date', 'asc')
                               ->limit(5)
                               ->get();

        // Get statistics
        $totalAgendas = Agenda::count();
        $activeAgendas = Agenda::where('is_public', true)
                              ->where('is_completed', false)
                              ->count();
        $thisMonthAgendas = Agenda::whereMonth('event_date', now()->month)
                                 ->whereYear('event_date', now()->year)
                                 ->count();

        $statistics = [
            'this_month' => $thisMonthAgendas,
            'completed' => Agenda::where('is_completed', true)->count(),
            'upcoming' => Agenda::where('event_date', '>', now())->count(),
            'cancelled' => 0, // Since we don't have cancelled status in current schema
            'participation' => 89, // Mock data for now
        ];

        // Get village profile for page data
        $villageProfile = \App\Models\VillageProfile::first();

        return view('frontend.page.agenda', compact(
            'agendas', 
            'todayEvents', 
            'upcomingEvents', 
            'statistics',
            'totalAgendas',
            'activeAgendas',
            'thisMonthAgendas',
            'villageProfile'
        ));
    }

    public function show($id)
    {
        $agenda = Agenda::where('is_public', true)
                       ->findOrFail($id);

        // Get related agendas
        $relatedAgendas = Agenda::where('is_public', true)
                               ->where('category', $agenda->category)
                               ->where('id', '!=', $agenda->id)
                               ->limit(3)
                               ->get();

        // Get village profile for page data
        $villageProfile = \App\Models\VillageProfile::first();

        return view('frontend.page.agenda-detail', compact('agenda', 'relatedAgendas', 'villageProfile'));
    }

    public function calendar($year, $month)
    {
        $events = Agenda::where('is_public', true)
                       ->whereYear('event_date', $year)
                       ->whereMonth('event_date', $month)
                       ->get(['id', 'title', 'category', 'event_date', 'start_time', 'end_time']);

        $calendarData = [];
        foreach ($events as $event) {
            $day = (int) $event->event_date->format('d');
            if (!isset($calendarData[$day])) {
                $calendarData[$day] = [];
            }
            $calendarData[$day][] = [
                'id' => $event->id,
                'title' => $event->title,
                'category' => $event->category,
                'time' => $event->start_time->format('H:i'),
            ];
        }

        return response()->json([
            'events' => $calendarData,
            'month' => $month,
            'year' => $year
        ]);
    }

    public function register(Request $request, $id)
    {
        $agenda = Agenda::where('is_public', true)
                       ->where('registration_required', true)
                       ->findOrFail($id);

        // Check if registration is still open
        if ($agenda->registration_deadline < now() || $agenda->event_date < now()) {
            return redirect()->back()->with('error', 'Registration is closed for this event.');
        }

        // Check if max participants reached
        if ($agenda->max_participants && $agenda->participants()->count() >= $agenda->max_participants) {
            return redirect()->back()->with('error', 'This event has reached maximum participants.');
        }

        $validator = Validator::make($request->all(), [
            'participant_name' => 'required|string|max:255',
            'participant_email' => 'required|email|max:255',
            'participant_phone' => 'required|string|max:20',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Check if already registered
        $existingParticipant = AgendaParticipant::where('agenda_id', $agenda->id)
                                              ->where('participant_email', $request->participant_email)
                                              ->first();

        if ($existingParticipant) {
            return redirect()->back()->with('error', 'You are already registered for this event.');
        }

        AgendaParticipant::create([
            'agenda_id' => $agenda->id,
            'participant_name' => $request->participant_name,
            'participant_email' => $request->participant_email,
            'participant_phone' => $request->participant_phone,
            'registration_date' => now(),
            'attendance_status' => 'registered',
        ]);

        return redirect()->back()->with('success', 'Successfully registered for the event!');
    }

    // API Methods
    public function getUpcomingAgenda()
    {
        $agendas = Agenda::where('is_public', true)
                        ->where('event_date', '>=', now())
                        ->orderBy('event_date', 'asc')
                        ->limit(5)
                        ->get(['id', 'title', 'category', 'event_date', 'start_time', 'location']);

        return response()->json($agendas);
    }

    public function getAgendaDetail($id)
    {
        $agenda = Agenda::where('is_public', true)
                       ->findOrFail($id);

        return response()->json($agenda);
    }

    public function getCalendar($year, $month)
    {
        $events = Agenda::where('is_public', true)
                       ->whereYear('event_date', $year)
                       ->whereMonth('event_date', $month)
                       ->get(['id', 'title', 'description', 'category', 'event_date', 'start_time', 'end_time']);

        $calendarData = [];
        foreach ($events as $event) {
            $day = (int) $event->event_date->format('d');
            if (!isset($calendarData[$day])) {
                $calendarData[$day] = [];
            }
            $calendarData[$day][] = [
                'id' => $event->id,
                'title' => $event->title,
                'description' => $event->description,
                'type' => $event->category,
                'category' => $event->category,
                'time' => $event->start_time ? Carbon::parse($event->start_time)->format('H:i') : '',
                'formatted_time' => $event->formatted_time ?? ($event->start_time ? Carbon::parse($event->start_time)->format('H:i') . ' WIB' : ''),
            ];
        }

        return response()->json([
            'events' => $calendarData,
            'month' => $month,
            'year' => $year
        ]);
    }
}