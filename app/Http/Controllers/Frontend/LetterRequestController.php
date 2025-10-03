<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\LetterRequest;
use App\Models\LetterTemplate;
use App\Models\PopulationData;
use App\Models\Settlement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class LetterRequestController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the form for creating a new letter request
     */
    public function create()
    {
        // Get active letter templates
        $letterTemplates = LetterTemplate::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        // Get user's population data
        $populationData = null;
        $settlementData = null;
        
        if (auth()->user() && auth()->user()->nik) {
            $populationData = PopulationData::where('identity_card_number', auth()->user()->nik)->first();
            
            // Get settlement data if population data exists
            if ($populationData && $populationData->settlement_id) {
                $settlementData = Settlement::find($populationData->settlement_id);
            }
        }

        return view('frontend.page.pengajuan-surat', compact('letterTemplates', 'populationData', 'settlementData'));
    }

    /**
     * Store a newly created letter request
     */
    public function store(Request $request)
    {
        // Get user's population data for validation
        $user = auth()->user();
        $populationData = null;
        
        if ($user && $user->nik) {
            $populationData = PopulationData::where('identity_card_number', $user->nik)->first();
        }
        
        if (!$populationData) {
            return redirect()->back()
                ->with('error', 'Data penduduk Anda tidak ditemukan. Silakan hubungi admin desa.');
        }

        $request->validate([
            'letter_template_id' => 'required_unless:letter_template_id,lainnya|exists:letter_templates,id',
            'custom_letter_type' => 'required_if:letter_template_id,lainnya|string|max:255',
            'purpose' => 'required|string',
            'ktp_file' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'kk_file' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'other_files.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'terms' => 'required|accepted'
        ], [
            'letter_template_id.required_unless' => 'Pilih jenis surat yang akan diajukan.',
            'letter_template_id.exists' => 'Template surat tidak ditemukan.',
            'custom_letter_type.required_if' => 'Sebutkan jenis surat yang diperlukan.',
            'purpose.required' => 'Keperluan surat harus diisi.',
            'ktp_file.required' => 'Upload foto KTP diperlukan.',
            'kk_file.required' => 'Upload foto Kartu Keluarga diperlukan.',
            'terms.required' => 'Anda harus menyetujui pernyataan.',
        ]);

        try {
            // Generate request number
            $requestNumber = $this->generateRequestNumber();

            // Handle file uploads
            $ktpPath = $request->file('ktp_file')->store('letter_requests/ktp', 'public');
            $kkPath = $request->file('kk_file')->store('letter_requests/kk', 'public');
            
            $otherFiles = [];
            if ($request->hasFile('other_files')) {
                foreach ($request->file('other_files') as $file) {
                    $otherFiles[] = $file->store('letter_requests/others', 'public');
                }
            }

            // Determine letter type
            $letterType = 'lainnya';
            $letterTemplateId = null;
            
            if ($request->letter_template_id !== 'lainnya') {
                $template = LetterTemplate::find($request->letter_template_id);
                if ($template) {
                    $letterType = $template->letter_type;
                    $letterTemplateId = $template->id;
                }
            }

            // Create letter request
            LetterRequest::create([
                'request_number' => $requestNumber,
                'letter_type' => $letterType,
                'custom_letter_type' => $request->custom_letter_type,
                'full_name' => $request->full_name,
                'nik' => $request->nik,
                'birth_place' => $request->birth_place,
                'birth_date' => $request->birth_date,
                'gender' => $request->gender,
                'religion' => $request->religion,
                'marital_status' => $request->marital_status,
                'occupation' => $request->occupation,
                'address' => $request->address,
                'rt' => $request->rt,
                'rw' => $request->rw,
                'phone' => $request->phone,
                'email' => $request->email,
                'purpose' => $request->purpose,
                'ktp_file_path' => $ktpPath,
                'kk_file_path' => $kkPath,
                'other_files' => !empty($otherFiles) ? $otherFiles : null,
                'status' => 'pending',
                'notes' => null
            ]);

            return redirect()->back()->with('success', 
                "Pengajuan surat berhasil dikirim dengan nomor: {$requestNumber}. " .
                "Anda akan dihubungi dalam 1-3 hari kerja untuk proses selanjutnya."
            );

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal mengirim pengajuan: ' . $e->getMessage());
        }
    }

    /**
     * Generate unique request number
     */
    private function generateRequestNumber()
    {
        $date = now()->format('Ymd');
        $prefix = 'REQ';
        
        // Get last request number for today
        $lastRequest = LetterRequest::whereDate('created_at', now()->toDateString())
            ->orderBy('created_at', 'desc')
            ->first();
        
        $sequence = 1;
        if ($lastRequest) {
            // Extract sequence number from last request
            $lastNumber = $lastRequest->request_number;
            if (preg_match('/(\d+)$/', $lastNumber, $matches)) {
                $sequence = intval($matches[1]) + 1;
            }
        }
        
        return $prefix . $date . str_pad($sequence, 3, '0', STR_PAD_LEFT);
    }
}
