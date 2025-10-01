<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\LetterRequest;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function letterRequests(Request $request)
    {
        $query = LetterRequest::with('user');
        
        if ($request->filled('search')) {
            $query->where('letter_type', 'like', '%' . $request->search . '%')
                  ->orWhere('requester_name', 'like', '%' . $request->search . '%')
                  ->orWhere('requester_nik', 'like', '%' . $request->search . '%');
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('letter_type')) {
            $query->where('letter_type', $request->letter_type);
        }
        
        $letterRequests = $query->orderBy('created_at', 'desc')->paginate(20);
        
        return view('backend.services.letter-requests', compact('letterRequests'));
    }
    
    public function showLetterRequest(LetterRequest $letterRequest)
    {
        return view('backend.services.letter-request-detail', compact('letterRequest'));
    }
    
    public function processLetterRequest(Request $request, LetterRequest $letterRequest)
    {
        $request->validate([
            'notes' => 'nullable|string'
        ]);
        
        $letterRequest->update([
            'status' => 'processing',
            'processed_by' => auth()->id(),
            'processed_at' => now(),
            'admin_notes' => $request->notes
        ]);
        
        return redirect()->route('admin.letter-requests.index')
                         ->with('success', 'Permintaan surat sedang diproses.');
    }
    
    public function completeLetterRequest(Request $request, LetterRequest $letterRequest)
    {
        $request->validate([
            'letter_number' => 'required|string|max:255',
            'notes' => 'nullable|string'
        ]);
        
        $letterRequest->update([
            'status' => 'completed',
            'letter_number' => $request->letter_number,
            'completed_at' => now(),
            'admin_notes' => $request->notes
        ]);
        
        return redirect()->route('admin.letter-requests.index')
                         ->with('success', 'Surat berhasil diselesaikan.');
    }
    
    public function rejectLetterRequest(Request $request, LetterRequest $letterRequest)
    {
        $request->validate([
            'rejection_reason' => 'required|string'
        ]);
        
        $letterRequest->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
            'processed_by' => auth()->id(),
            'processed_at' => now()
        ]);
        
        return redirect()->route('admin.letter-requests.index')
                         ->with('success', 'Permintaan surat ditolak.');
    }
}