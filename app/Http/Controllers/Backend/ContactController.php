<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = ContactMessage::query();

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%");
            });
        }

        // Date range filter
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $contacts = $query->with('repliedBy')
                         ->orderBy('created_at', 'desc')
                         ->paginate(20);

        // Get statistics
        $stats = [
            'total' => ContactMessage::count(),
            'unread' => ContactMessage::where('status', 'unread')->count(),
            'read' => ContactMessage::where('status', 'read')->count(),
            'replied' => ContactMessage::where('status', 'replied')->count(),
        ];

        return view('backend.contact.index', compact('contacts', 'stats'));
    }

    /**
     * Display the specified resource.
     */
    public function show(ContactMessage $contact)
    {
        // Mark as read if it's unread
        if ($contact->status === 'unread') {
            $contact->markAsRead();
        }

        return view('backend.contact.show', compact('contact'));
    }

    /**
     * Show reply form
     */
    public function reply(ContactMessage $contact)
    {
        return view('backend.contact.reply', compact('contact'));
    }

    /**
     * Store reply
     */
    public function storeReply(Request $request, ContactMessage $contact)
    {
        $request->validate([
            'admin_reply' => 'required|string|min:10'
        ], [
            'admin_reply.required' => 'Balasan wajib diisi.',
            'admin_reply.min' => 'Balasan minimal 10 karakter.'
        ]);

        try {
            $contact->markAsReplied($request->admin_reply, Auth::id());

            return redirect()->route('admin.contact.show', $contact)
                           ->with('success', 'Balasan berhasil disimpan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat menyimpan balasan.')
                        ->withInput();
        }
    }

    /**
     * Update status
     */
    public function updateStatus(Request $request, ContactMessage $contact)
    {
        $request->validate([
            'status' => 'required|in:unread,read,replied'
        ]);

        try {
            $contact->update(['status' => $request->status]);

            return response()->json([
                'success' => true,
                'message' => 'Status berhasil diperbarui.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui status.'
            ], 500);
        }
    }

    /**
     * Bulk actions
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:mark_read,mark_unread,delete',
            'ids' => 'required|array',
            'ids.*' => 'exists:contact_messages,id'
        ]);

        try {
            $contacts = ContactMessage::whereIn('id', $request->ids);

            switch ($request->action) {
                case 'mark_read':
                    $contacts->update(['status' => 'read']);
                    $message = count($request->ids) . ' pesan berhasil ditandai sebagai sudah dibaca.';
                    break;
                    
                case 'mark_unread':
                    $contacts->update(['status' => 'unread']);
                    $message = count($request->ids) . ' pesan berhasil ditandai sebagai belum dibaca.';
                    break;
                    
                case 'delete':
                    $contacts->delete();
                    $message = count($request->ids) . ' pesan berhasil dihapus.';
                    break;
            }

            return response()->json([
                'success' => true,
                'message' => $message
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memproses aksi.'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ContactMessage $contact)
    {
        try {
            $contact->delete();

            return redirect()->route('admin.contact.index')
                           ->with('success', 'Pesan kontak berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat menghapus pesan.');
        }
    }

    /**
     * Get contact statistics for dashboard
     */
    public function getStats()
    {
        return response()->json([
            'total' => ContactMessage::count(),
            'unread' => ContactMessage::unread()->count(),
            'today' => ContactMessage::whereDate('created_at', today())->count(),
            'this_week' => ContactMessage::whereBetween('created_at', [
                now()->startOfWeek(), 
                now()->endOfWeek()
            ])->count(),
            'this_month' => ContactMessage::whereMonth('created_at', now()->month)
                                        ->whereYear('created_at', now()->year)
                                        ->count(),
        ]);
    }
}
