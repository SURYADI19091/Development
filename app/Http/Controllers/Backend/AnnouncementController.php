<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AnnouncementController extends Controller
{
    public function index(Request $request)
    {
        $query = Announcement::with('author');

        // Search
        if ($request->has('search') && $request->search != '') {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('content', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by priority
        if ($request->has('priority') && $request->priority != '') {
            $query->where('priority', $request->priority);
        }

        // Filter by status
        if ($request->has('status')) {
            if ($request->status == 'active') {
                $query->where('is_active', true);
            } elseif ($request->status == 'inactive') {
                $query->where('is_active', false);
            }
        }

        // Filter by date range
        if ($request->has('date_from') && $request->date_from != '') {
            $query->whereDate('valid_from', '>=', $request->date_from);
        }
        
        if ($request->has('date_to') && $request->date_to != '') {
            $query->whereDate('valid_until', '<=', $request->date_to);
        }

        $announcements = $query->orderBy('priority', 'desc')
                              ->orderBy('created_at', 'desc')
                              ->paginate(15);

        $priorities = ['low', 'medium', 'high', 'urgent'];

        return view('backend.announcements.index', compact('announcements', 'priorities'));
    }

    public function create()
    {
        $priorities = ['low' => 'Low', 'medium' => 'Medium', 'high' => 'High', 'urgent' => 'Urgent'];
        
        return view('backend.announcements.create', compact('priorities'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'priority' => 'required|in:low,medium,high,urgent',
            'category' => 'nullable|string|max:100',
            'valid_from' => 'nullable|date',
            'valid_until' => 'nullable|date|after_or_equal:valid_from',
            'is_active' => 'boolean',
        ]);

        $data = $request->only(['title', 'content', 'priority', 'category', 'valid_from', 'valid_until', 'is_active']);
        $data['created_by'] = Auth::id();

        Announcement::create($data);

        return redirect()->route('backend.announcements.index')
                        ->with('success', 'Announcement created successfully!');
    }

    public function show($id)
    {
        $announcement = Announcement::with('author')->findOrFail($id);
        
        return view('backend.announcements.show', compact('announcement'));
    }

    public function edit($id)
    {
        $announcement = Announcement::findOrFail($id);
        $priorities = ['low' => 'Low', 'medium' => 'Medium', 'high' => 'High', 'urgent' => 'Urgent'];
        
        return view('backend.announcements.edit', compact('announcement', 'priorities'));
    }

    public function update(Request $request, $id)
    {
        $announcement = Announcement::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'priority' => 'required|in:low,medium,high,urgent',
            'category' => 'nullable|string|max:100',
            'valid_from' => 'nullable|date',
            'valid_until' => 'nullable|date|after_or_equal:valid_from',
            'is_active' => 'boolean',
        ]);

        $data = $request->only(['title', 'content', 'priority', 'category', 'valid_from', 'valid_until', 'is_active']);

        $announcement->update($data);

        return redirect()->route('backend.announcements.index')
                        ->with('success', 'Announcement updated successfully!');
    }

    public function destroy($id)
    {
        $announcement = Announcement::findOrFail($id);
        $announcement->delete();

        return redirect()->route('backend.announcements.index')
                        ->with('success', 'Announcement deleted successfully!');
    }

    public function toggleStatus(Request $request, $id)
    {
        $announcement = Announcement::findOrFail($id);
        $announcement->is_active = !$announcement->is_active;
        $announcement->save();

        $status = $announcement->is_active ? 'activated' : 'deactivated';
        return response()->json([
            'success' => true,
            'message' => "Announcement {$status} successfully!"
        ]);
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:activate,deactivate,delete',
            'announcements' => 'required|array',
            'announcements.*' => 'exists:announcements,id',
        ]);

        $announcements = Announcement::whereIn('id', $request->announcements);

        switch ($request->action) {
            case 'activate':
                $announcements->update(['is_active' => true]);
                $message = 'Selected announcements activated successfully!';
                break;
                
            case 'deactivate':
                $announcements->update(['is_active' => false]);
                $message = 'Selected announcements deactivated successfully!';
                break;
                
            case 'delete':
                // Delete attachments for each announcement
                foreach ($announcements->get() as $announcement) {
                    if ($announcement->attachments) {
                        foreach ($announcement->attachments as $attachment) {
                            Storage::disk('public')->delete($attachment['path']);
                        }
                    }
                }
                $announcements->delete();
                $message = 'Selected announcements deleted successfully!';
                break;
        }

        return redirect()->route('backend.announcements.index')
                        ->with('success', $message);
    }

    public function getActiveAnnouncements()
    {
        $announcements = Announcement::where('is_active', true)
                                   ->where(function($query) {
                                       $query->whereNull('valid_from')
                                             ->orWhere('valid_from', '<=', now());
                                   })
                                   ->where(function($query) {
                                       $query->whereNull('valid_until')
                                             ->orWhere('valid_until', '>=', now());
                                   })
                                   ->orderBy('priority', 'desc')
                                   ->orderBy('created_at', 'desc')
                                   ->take(10)
                                   ->get();

        return response()->json($announcements);
    }
}