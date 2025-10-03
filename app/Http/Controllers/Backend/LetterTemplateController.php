<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\LetterTemplate;
use App\Services\WordTemplateService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Exception;

class LetterTemplateController extends Controller
{
    public function index(Request $request)
    {
        $query = LetterTemplate::with(['creator', 'updater']);

        // Filter by letter type
        if ($request->filled('letter_type')) {
            $query->where('letter_type', $request->letter_type);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $templates = $query->ordered()->paginate(15);

        // Get statistics
        $stats = [
            'total' => LetterTemplate::count(),
            'active' => LetterTemplate::where('is_active', true)->count(),
            'inactive' => LetterTemplate::where('is_active', false)->count(),
        ];

        return view('backend.services.templates.index', compact('templates', 'stats'));
    }

    public function create()
    {
        return view('backend.services.templates.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:letter_templates,code',
            'letter_type' => 'required|in:domisili,usaha,tidak_mampu,penghasilan,pengantar_ktp,pengantar_kk,pengantar_akta,pengantar_nikah,kelahiran,kematian,pindah,beda_nama,kehilangan,lainnya',
            'description' => 'nullable|string',
            'template_type' => 'required|in:word,html',
            'template_file' => 'nullable|file|mimes:docx,doc|max:10240', // Max 10MB for Word files
            'template_content' => 'required_if:template_type,html|string',
            'required_fields' => 'nullable|array',
            'variables' => 'nullable|array',
            'replacement_map' => 'nullable|array',
            'header_logo' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
            'letter_header' => 'nullable|string',
            'letter_footer' => 'nullable|string',
            'format' => 'required|in:A4,Legal,Letter',
            'orientation' => 'required|in:portrait,landscape',
            'margins' => 'nullable|array',
            'word_settings' => 'nullable|array',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0'
        ]);

        $data = $request->except(['header_logo', 'template_file']);
        
        try {
            // Handle Word template upload
            if ($request->template_type === 'word' && $request->hasFile('template_file')) {
                $wordService = new WordTemplateService();
                $templateInfo = $wordService->uploadTemplate($request->file('template_file'));
                
                $data['template_file'] = $templateInfo['path'];
                $data['template_file_original_name'] = $templateInfo['original_name'];
                $data['template_file_size'] = $templateInfo['size'];
                $data['template_file_mime_type'] = $templateInfo['mime_type'];
                $data['word_bookmarks'] = $templateInfo['bookmarks'];
            }
            
            // Handle logo upload
            if ($request->hasFile('header_logo')) {
                $data['header_logo_path'] = $request->file('header_logo')->store('letter_templates/logos', 'public');
            }

            // Process arrays
            $data['required_fields'] = $request->required_fields ?? [];
            $data['variables'] = $request->variables ?? [];
            $data['replacement_map'] = $request->replacement_map ?? [];
            $data['word_settings'] = $request->word_settings ?? [];
            $data['margins'] = [
                'top' => $request->margin_top ?? 2.5,
                'bottom' => $request->margin_bottom ?? 2.5,
                'left' => $request->margin_left ?? 2.5,
                'right' => $request->margin_right ?? 2.5,
            ];

            // Set creator
            $data['created_by'] = auth()->id();
            $data['updated_by'] = auth()->id();

            LetterTemplate::create($data);

            return redirect()->route('backend.letter-templates.index')
                           ->with('success', 'Template surat berhasil dibuat.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membuat template surat: ' . $e->getMessage())
                        ->withInput();
        }
    }

    public function show(LetterTemplate $letterTemplate)
    {
        return view('backend.services.templates.show', compact('letterTemplate'));
    }

    public function edit(LetterTemplate $letterTemplate)
    {
        return view('backend.services.templates.edit', compact('letterTemplate'));
    }

    public function update(Request $request, LetterTemplate $letterTemplate)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:letter_templates,code,' . $letterTemplate->id,
            'letter_type' => 'required|in:domisili,usaha,tidak_mampu,penghasilan,pengantar_ktp,pengantar_kk,pengantar_akta,pengantar_nikah,kelahiran,kematian,pindah,beda_nama,kehilangan,lainnya',
            'description' => 'nullable|string',
            'template_type' => 'required|in:word,html',
            'template_file' => 'nullable|file|mimes:docx,doc|max:10240',
            'template_content' => 'required_if:template_type,html|string',
            'required_fields' => 'nullable|array',
            'variables' => 'nullable|array',
            'replacement_map' => 'nullable|array',
            'header_logo' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
            'letter_header' => 'nullable|string',
            'letter_footer' => 'nullable|string',
            'format' => 'required|in:A4,Legal,Letter',
            'orientation' => 'required|in:portrait,landscape',
            'margins' => 'nullable|array',
            'word_settings' => 'nullable|array',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0'
        ]);

        $data = $request->except(['header_logo', 'template_file']);

        try {
            // Handle Word template upload (if changing to Word or updating Word template)
            if ($request->template_type === 'word' && $request->hasFile('template_file')) {
                $wordService = new WordTemplateService();
                
                // Delete old template file
                if ($letterTemplate->template_file) {
                    Storage::disk('public')->delete($letterTemplate->template_file);
                }
                
                $templateInfo = $wordService->uploadTemplate($request->file('template_file'));
                
                $data['template_file'] = $templateInfo['path'];
                $data['template_file_original_name'] = $templateInfo['original_name'];
                $data['template_file_size'] = $templateInfo['size'];
                $data['template_file_mime_type'] = $templateInfo['mime_type'];
                $data['word_bookmarks'] = $templateInfo['bookmarks'];
            }

            // Handle logo upload
            if ($request->hasFile('header_logo')) {
                // Delete old logo
                if ($letterTemplate->header_logo_path) {
                    Storage::disk('public')->delete($letterTemplate->header_logo_path);
                }
                $data['header_logo_path'] = $request->file('header_logo')->store('letter_templates/logos', 'public');
            }

            // Process arrays
            $data['required_fields'] = $request->required_fields ?? [];
            $data['variables'] = $request->variables ?? [];
            $data['replacement_map'] = $request->replacement_map ?? [];
            $data['word_settings'] = $request->word_settings ?? [];
            $data['margins'] = [
                'top' => $request->margin_top ?? 2.5,
                'bottom' => $request->margin_bottom ?? 2.5,
                'left' => $request->margin_left ?? 2.5,
                'right' => $request->margin_right ?? 2.5,
            ];

            // Set updater
            $data['updated_by'] = auth()->id();

            $letterTemplate->update($data);

            return redirect()->route('backend.letter-templates.index')
                           ->with('success', 'Template surat berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui template surat: ' . $e->getMessage())
                        ->withInput();
        }
    }

    public function destroy(LetterTemplate $letterTemplate)
    {
        try {
            // Delete logo file
            if ($letterTemplate->header_logo_path) {
                Storage::disk('public')->delete($letterTemplate->header_logo_path);
            }
            
            // Delete Word template file
            if ($letterTemplate->template_file) {
                Storage::disk('public')->delete($letterTemplate->template_file);
            }

            $letterTemplate->delete();

            return redirect()->route('backend.letter-templates.index')
                           ->with('success', 'Template surat berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus template surat: ' . $e->getMessage());
        }
    }

    public function duplicate(LetterTemplate $letterTemplate)
    {
        try {
            $newTemplate = $letterTemplate->duplicate();

            return redirect()->route('backend.letter-templates.edit', $newTemplate)
                           ->with('success', 'Template berhasil diduplikasi. Silakan edit sesuai kebutuhan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menduplikasi template: ' . $e->getMessage());
        }
    }

    public function toggleStatus(LetterTemplate $letterTemplate)
    {
        try {
            $letterTemplate->update([
                'is_active' => !$letterTemplate->is_active,
                'updated_by' => auth()->id()
            ]);

            $status = $letterTemplate->is_active ? 'diaktifkan' : 'dinonaktifkan';

            return response()->json([
                'success' => true,
                'message' => "Template berhasil {$status}.",
                'status' => $letterTemplate->is_active
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengubah status template.'
            ], 500);
        }
    }

    public function preview(LetterTemplate $letterTemplate, Request $request)
    {
        $sampleData = [
            'full_name' => 'John Doe',
            'nik' => '1234567890123456',
            'birth_place' => 'Jakarta',
            'birth_date' => '01 Januari 1990',
            'gender' => 'Laki-laki',
            'religion' => 'Islam',
            'marital_status' => 'Kawin',
            'occupation' => 'Swasta',
            'address' => 'Jl. Contoh No. 123',
            'rt' => '001',
            'rw' => '002',
            'purpose' => 'Untuk keperluan administrasi',
            'letter_number' => '001/KEL/DS/10/2025',
            'current_date' => now()->format('d M Y'),
        ];

        // Merge with request data if provided
        $data = array_merge($sampleData, $request->all());

        $processedContent = $letterTemplate->processTemplate($data);

        return view('backend.services.templates.preview', compact('letterTemplate', 'processedContent', 'data'));
    }

    /**
     * Download template as PDF
     */
    public function downloadPDF(LetterTemplate $letterTemplate, Request $request)
    {
        $sampleData = [
            'full_name' => 'John Doe',
            'nik' => '1234567890123456',
            'birth_place' => 'Jakarta',
            'birth_date' => '01 Januari 1990',
            'gender' => 'Laki-laki',
            'religion' => 'Islam',
            'marital_status' => 'Kawin',
            'occupation' => 'Swasta',
            'address' => 'Jl. Contoh No. 123',
            'rt' => '001',
            'rw' => '002',
            'purpose' => 'Untuk keperluan administrasi',
            'letter_number' => '001/KEL/DS/10/2025',
            'current_date' => now()->format('d M Y'),
        ];

        // Merge with request data if provided
        $data = array_merge($sampleData, $request->all());

        $processedContent = $letterTemplate->processTemplate($data);

        // Generate PDF using DomPDF
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('backend.services.templates.pdf', compact('letterTemplate', 'processedContent', 'data'));

        // Set paper size and orientation
        $pdf->setPaper($letterTemplate->format ?? 'A4', $letterTemplate->orientation ?? 'portrait');

        // Download PDF
        return $pdf->download('template-preview-' . $letterTemplate->code . '.pdf');
    }

    /**
     * Get Word template bookmarks for AJAX
     */
    public function getBookmarks(LetterTemplate $letterTemplate)
    {
        if (!$letterTemplate->isWordTemplate()) {
            return response()->json([
                'success' => false,
                'message' => 'Template ini bukan Word template.'
            ], 400);
        }

        return response()->json([
            'success' => true,
            'bookmarks' => $letterTemplate->getAvailableBookmarks()
        ]);
    }

    /**
     * Preview Word template
     */
    public function previewWord(LetterTemplate $letterTemplate)
    {
        if (!$letterTemplate->isWordTemplate()) {
            return back()->with('error', 'Template ini bukan Word template.');
        }

        try {
            $wordService = new WordTemplateService();
            
            // Generate sample data for preview
            $sampleData = [
                'village_name' => 'Desa Contoh',
                'village_head' => 'Kepala Desa',
                'village_address' => 'Jalan Contoh No. 123',
                'current_date' => now()->format('d F Y'),
                'letter_number' => 'XXX/XXX/XXXX',
                'citizen_name' => 'Nama Warga',
                'citizen_nik' => '1234567890123456',
                'citizen_address' => 'Alamat Warga'
            ];

            $previewPath = $wordService->generateDocument($letterTemplate, $sampleData, true);
            
            return response()->download(storage_path('app/public/' . $previewPath))
                           ->deleteFileAfterSend(true);
                           
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membuat preview: ' . $e->getMessage());
        }
    }

    /**
     * Download Word template file
     */
    public function downloadTemplate(LetterTemplate $letterTemplate)
    {
        if (!$letterTemplate->isWordTemplate() || !$letterTemplate->template_file) {
            return back()->with('error', 'File template tidak ditemukan.');
        }

        $filePath = storage_path('app/public/' . $letterTemplate->template_file);
        
        if (!file_exists($filePath)) {
            return back()->with('error', 'File template tidak ditemukan di server.');
        }

        return response()->download($filePath, $letterTemplate->template_file_original_name);
    }

    /**
     * Extract bookmarks from uploaded Word file (AJAX)
     */
    public function extractBookmarks(Request $request)
    {
        $request->validate([
            'template_file' => 'required|file|mimes:docx,doc|max:10240'
        ]);

        try {
            $wordService = new WordTemplateService();
            $bookmarks = $wordService->extractBookmarks($request->file('template_file'));
            
            return response()->json([
                'success' => true,
                'bookmarks' => $bookmarks
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengekstrak bookmark: ' . $e->getMessage()
            ], 500);
        }
    }
}
