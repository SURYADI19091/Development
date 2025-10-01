<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\LetterRequest;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function letters()
    {
        // Get available letter types and requirements
        $letterTypes = [
            'Surat Keterangan Domisili',
            'Surat Keterangan Tidak Mampu',
            'Surat Keterangan Usaha',
            'Surat Keterangan Kelahiran',
            'Surat Keterangan Kematian',
            'Surat Pengantar KTP',
            'Surat Pengantar Nikah',
            'Surat Keterangan Belum Nikah',
            'Surat Keterangan Kehilangan',
            'Surat Keterangan Beda Nama'
        ];

        // Get recent requests statistics
        $stats = [
            'pending' => LetterRequest::where('status', 'pending')->count(),
            'approved' => LetterRequest::where('status', 'approved')->count(),
            'rejected' => LetterRequest::where('status', 'rejected')->count(),
            'total' => LetterRequest::count()
        ];

        return view('frontend.page.layanan-surat', compact('letterTypes', 'stats'));
    }
    
    public function letterRequest()
    {
        return view('frontend.page.pengajuan-surat');
    }
    
    public function submitLetterRequest(Request $request)
    {
        $request->validate([
            'letter_type' => 'required|string|max:255',
            'purpose' => 'required|string',
            'requester_name' => 'required|string|max:255',
            'requester_nik' => 'required|string|size:16',
            'requester_phone' => 'required|string|max:20',
            'requester_address' => 'required|string',
            'supporting_documents' => 'nullable|array',
            'supporting_documents.*' => 'file|mimes:pdf,jpg,jpeg,png|max:2048',
            'notes' => 'nullable|string'
        ]);
        
        $documentPaths = [];
        if ($request->hasFile('supporting_documents')) {
            foreach ($request->file('supporting_documents') as $file) {
                $documentPaths[] = $file->store('letter_requests', 'public');
            }
        }
        
        LetterRequest::create([
            'letter_type' => $request->letter_type,
            'purpose' => $request->purpose,
            'requester_name' => $request->requester_name,
            'requester_nik' => $request->requester_nik,
            'requester_phone' => $request->requester_phone,
            'requester_address' => $request->requester_address,
            'supporting_documents' => json_encode($documentPaths),
            'notes' => $request->notes,
            'status' => 'pending',
            'user_id' => auth()->id()
        ]);
        
        return redirect()->route('services.letter-request')
                         ->with('success', 'Pengajuan surat berhasil dikirim. Silakan tunggu konfirmasi dari petugas desa.');
    }

    public function checkStatus()
    {
        $user = auth()->user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'Silakan login untuk melihat status pengajuan.');
        }

        $requests = LetterRequest::where('user_id', $user->id)
                                ->orderBy('created_at', 'desc')
                                ->paginate(10);

        return view('frontend.page.status-pengajuan', compact('requests'));
    }

    public function downloadLetter($id)
    {
        $request = LetterRequest::where('user_id', auth()->id())
                               ->where('status', 'approved')
                               ->findOrFail($id);

        if (!$request->approved_letter_path) {
            return redirect()->back()->with('error', 'Surat belum tersedia untuk diunduh.');
        }

        return response()->download(storage_path('app/' . $request->approved_letter_path));
    }

    // API Methods
    public function getServiceStats()
    {
        $stats = [
            'pending' => LetterRequest::where('status', 'pending')->count(),
            'processing' => LetterRequest::where('status', 'processing')->count(),
            'approved' => LetterRequest::where('status', 'approved')->count(),
            'rejected' => LetterRequest::where('status', 'rejected')->count(),
            'total' => LetterRequest::count()
        ];

        return response()->json($stats);
    }

    public function trackRequest($tracking_number)
    {
        $request = LetterRequest::where('tracking_number', $tracking_number)->first();
        
        if (!$request) {
            return response()->json(['error' => 'Nomor tracking tidak ditemukan'], 404);
        }

        return response()->json([
            'id' => $request->id,
            'letter_type' => $request->letter_type,
            'status' => $request->status,
            'submitted_at' => $request->created_at->format('d M Y H:i'),
            'processed_at' => $request->processed_at ? $request->processed_at->format('d M Y H:i') : null,
            'notes' => $request->admin_notes
        ]);
    }
}