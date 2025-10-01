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
            $query->whereDate('start_date', '>=', $request->date_from);
        }
        
        if ($request->has('date_to') && $request->date_to != '') {
            $query->whereDate('end_date', '<=', $request->date_to);
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
        $targetAudiences = ['all' => 'All Citizens', 'adults' => 'Adults Only', 'businesses' => 'Businesses', 'students' => 'Students'];
        
        return view('backend.announcements.create', compact('priorities', 'targetAudiences'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'priority' => 'required|in:low,medium,high,urgent',
            'target_audience' => 'required|array',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120', // 5MB max
            'is_active' => 'boolean',
        ]);

        $data = $request->all();
        $data['author_id'] = Auth::id();

        // Handle file attachments
        if ($request->hasFile('attachments')) {
            $attachments = [];
            foreach ($request->file('attachments') as $file) {
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('announcements/attachments', $filename, 'public');
                $attachments[] = [
                    'filename' => $file->getClientOriginalName(),
                    'path' => $path,
                    'size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                ];
            }
            $data['attachments'] = $attachments;
        }

        Announcement::create($data);

        return redirect()->route('admin.announcements.index')
                        ->with('success', 'Announcement created successfully!');
    }

    public function show($id)
    {
        $announcement = Announcement::with('author')->findOrFail($id);
        
        // Increment views count
        $announcement->increment('views_count');
        
        return view('backend.announcements.show', compact('announcement'));
    }

    public function edit($id)
    {
        $announcement = Announcement::findOrFail($id);
        $priorities = ['low' => 'Low', 'medium' => 'Medium', 'high' => 'High', 'urgent' => 'Urgent'];
        $targetAudiences = ['all' => 'All Citizens', 'adults' => 'Adults Only', 'businesses' => 'Businesses', 'students' => 'Students'];
        
        return view('backend.announcements.edit', compact('announcement', 'priorities', 'targetAudiences'));
    }

    public function update(Request $request, $id)
    {
        $announcement = Announcement::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'priority' => 'required|in:low,medium,high,urgent',
            'target_audience' => 'required|array',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
            'is_active' => 'boolean',
        ]);

        $data = $request->all();

        // Handle file attachments
        if ($request->hasFile('attachments')) {
            // Delete old attachments if any
            if ($announcement->attachments) {
                foreach ($announcement->attachments as $attachment) {
                    Storage::disk('public')->delete($attachment['path']);
                }
            }
            
            $attachments = [];
            foreach ($request->file('attachments') as $file) {
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('announcements/attachments', $filename, 'public');
                $attachments[] = [
                    'filename' => $file->getClientOriginalName(),
                    'path' => $path,
                    'size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                ];
            }
            $data['attachments'] = $attachments;
        }

        $announcement->update($data);

        return redirect()->route('admin.announcements.index')
                        ->with('success', 'Announcement updated successfully!');
    }

    public function destroy($id)
    {
        $announcement = Announcement::findOrFail($id);
        
        // Delete attachments
        if ($announcement->attachments) {
            foreach ($announcement->attachments as $attachment) {
                Storage::disk('public')->delete($attachment['path']);
            }
        }

        $announcement->delete();

        return redirect()->route('admin.announcements.index')
                        ->with('success', 'Announcement deleted successfully!');
    }

    public function toggleStatus(Request $request, $id)
    {
        $announcement = Announcement::findOrFail($id);
        $announcement->is_active = !$announcement->is_active;
        $announcement->save();

        $status = $announcement->is_active ? 'activated' : 'deactivated';
        return response()->json(['message' => "Announcement {$status} successfully!"]);
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

        return redirect()->route('admin.announcements.index')
                        ->with('success', $message);
    }

    public function getActiveAnnouncements()
    {
        $announcements = Announcement::where('is_active', true)
                                   ->where('start_date', '<=', now())
                                   ->where('end_date', '>=', now())
                                   ->orderBy('priority', 'desc')
                                   ->orderBy('created_at', 'desc')
                                   ->take(10)
                                   ->get();

        return response()->json($announcements);
    }
}