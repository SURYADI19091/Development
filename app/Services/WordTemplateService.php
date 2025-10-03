<?php

namespace App\Services;

use App\Models\LetterTemplate;
use App\Models\LetterRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\TemplateProcessor;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Settings;
use Exception;
use Illuminate\Support\Str;

class WordTemplateService
{
    protected $templatePath;
    protected $outputPath;

    public function __construct()
    {
        $this->templatePath = storage_path('app/templates/');
        $this->outputPath = storage_path('app/generated/');
        
        // Ensure directories exist
        if (!file_exists($this->templatePath)) {
            mkdir($this->templatePath, 0755, true);
        }
        if (!file_exists($this->outputPath)) {
            mkdir($this->outputPath, 0755, true);
        }
    }

    /**
     * Upload and store Word template
     */
    public function uploadTemplate(UploadedFile $file): array
    {
        $originalName = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        
        // Validate file type
        if (!in_array(strtolower($extension), ['docx', 'doc'])) {
            throw new Exception('File harus berformat Word (.docx atau .doc)');
        }

        // Generate unique filename
        $filename = Str::uuid() . '.' . $extension;
        $path = 'templates/' . $filename;
        
        // Store file
        Storage::disk('local')->put($path, file_get_contents($file->getRealPath()));
        
        // Extract bookmarks from template
        $bookmarks = $this->extractBookmarks(Storage::disk('local')->path($path));
        
        return [
            'path' => $path,
            'original_name' => $originalName,
            'size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
            'bookmarks' => $bookmarks
        ];
    }

    /**
     * Extract bookmarks from Word template
     */
    public function extractBookmarks(string $templatePath): array
    {
        try {
            $templateProcessor = new TemplateProcessor($templatePath);
            $variables = $templateProcessor->getVariables();
            
            // Convert to bookmarks format
            $bookmarks = [];
            foreach ($variables as $variable) {
                $bookmarks[] = [
                    'name' => $variable,
                    'description' => $this->getVariableDescription($variable),
                    'type' => $this->getVariableType($variable)
                ];
            }
            
            return $bookmarks;
        } catch (Exception $e) {
            // Fallback: return common variables
            return $this->getDefaultBookmarks();
        }
    }

    /**
     * Generate document from template
     */
    public function generateDocument(LetterTemplate $template, array $data): string
    {
        if ($template->template_type !== 'word' || !$template->template_file) {
            throw new Exception('Template bukan tipe Word atau file tidak ditemukan');
        }

        $templatePath = Storage::disk('local')->path($template->template_file);
        
        if (!file_exists($templatePath)) {
            throw new Exception('File template tidak ditemukan: ' . $templatePath);
        }

        try {
            // Load template
            $templateProcessor = new TemplateProcessor($templatePath);
            
            // Process data replacement
            $processedData = $this->processReplacementData($template, $data);
            
            // Replace variables in template
            foreach ($processedData as $key => $value) {
                $templateProcessor->setValue($key, $value);
            }
            
            // Handle tables if any
            $this->processTableData($templateProcessor, $processedData);
            
            // Generate output filename
            $outputFilename = 'surat_' . $template->code . '_' . time() . '.docx';
            $outputPath = $this->outputPath . $outputFilename;
            
            // Save generated document
            $templateProcessor->saveAs($outputPath);
            
            // Update template usage
            $template->increment('usage_count');
            $template->update(['last_used_at' => now()]);
            
            return $outputPath;
            
        } catch (Exception $e) {
            throw new Exception('Error generating document: ' . $e->getMessage());
        }
    }

    /**
     * Convert Word document to PDF
     */
    public function convertToPdf(string $wordFilePath): string
    {
        try {
            // Load Word document
            $phpWord = IOFactory::load($wordFilePath);
            
            // Set PDF renderer
            Settings::setPdfRendererPath(base_path('vendor/tecnickcom/tcpdf'));
            Settings::setPdfRendererName('TCPDF');
            
            // Generate PDF filename
            $pdfFilename = str_replace('.docx', '.pdf', basename($wordFilePath));
            $pdfPath = $this->outputPath . $pdfFilename;
            
            // Save as PDF
            $pdfWriter = IOFactory::createWriter($phpWord, 'PDF');
            $pdfWriter->save($pdfPath);
            
            return $pdfPath;
            
        } catch (Exception $e) {
            throw new Exception('Error converting to PDF: ' . $e->getMessage());
        }
    }

    /**
     * Process replacement data according to template mapping
     */
    protected function processReplacementData(LetterTemplate $template, array $data): array
    {
        $processedData = [];
        $replacementMap = $template->replacement_map ?? [];
        
        // If no mapping defined, use direct mapping
        if (empty($replacementMap)) {
            $replacementMap = $this->getDefaultReplacementMap();
        }
        
        foreach ($replacementMap as $placeholder => $dataKey) {
            $value = data_get($data, $dataKey, '[' . strtoupper($placeholder) . ']');
            
            // Format value based on type
            $processedData[$placeholder] = $this->formatValue($value, $placeholder);
        }
        
        // Add system variables
        $processedData = array_merge($processedData, $this->getSystemVariables());
        
        return $processedData;
    }

    /**
     * Process table data in template
     */
    protected function processTableData(TemplateProcessor $templateProcessor, array $data): void
    {
        // Handle cloning tables for multiple records
        $tableVariables = ['family_members', 'documents', 'witnesses'];
        
        foreach ($tableVariables as $tableVar) {
            if (isset($data[$tableVar]) && is_array($data[$tableVar])) {
                $templateProcessor->cloneRowAndSetValues($tableVar, $data[$tableVar]);
            }
        }
    }

    /**
     * Format value based on placeholder type
     */
    protected function formatValue($value, string $placeholder): string
    {
        if (is_null($value) || $value === '') {
            return '[TIDAK DIISI]';
        }
        
        // Format dates
        if (in_array($placeholder, ['birth_date', 'current_date', 'issue_date'])) {
            return $this->formatDate($value);
        }
        
        // Format currency
        if (in_array($placeholder, ['salary', 'income', 'amount'])) {
            return $this->formatCurrency($value);
        }
        
        // Format gender
        if ($placeholder === 'gender') {
            return $value === 'L' ? 'Laki-laki' : ($value === 'P' ? 'Perempuan' : $value);
        }
        
        return (string) $value;
    }

    /**
     * Format date value
     */
    protected function formatDate($date): string
    {
        if (is_string($date)) {
            $date = \Carbon\Carbon::parse($date);
        }
        
        if (!$date instanceof \Carbon\Carbon) {
            return (string) $date;
        }
        
        // Format: 15 Oktober 2024
        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        
        return $date->day . ' ' . $months[$date->month] . ' ' . $date->year;
    }

    /**
     * Format currency value
     */
    protected function formatCurrency($value): string
    {
        return 'Rp ' . number_format((float) $value, 0, ',', '.');
    }

    /**
     * Get system variables
     */
    protected function getSystemVariables(): array
    {
        return [
            'current_date' => $this->formatDate(now()),
            'current_year' => date('Y'),
            'current_month' => date('m'),
            'current_day' => date('d'),
            'letter_number' => $this->generateLetterNumber(),
        ];
    }

    /**
     * Generate letter number
     */
    protected function generateLetterNumber(): string
    {
        $sequence = str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
        $month = date('m');
        $year = date('Y');
        
        return "{$sequence}/DS/{$month}/{$year}";
    }

    /**
     * Get variable description for documentation
     */
    protected function getVariableDescription(string $variable): string
    {
        $descriptions = [
            'full_name' => 'Nama lengkap pemohon',
            'nik' => 'Nomor Induk Kependudukan',
            'birth_place' => 'Tempat lahir',
            'birth_date' => 'Tanggal lahir',
            'gender' => 'Jenis kelamin',
            'religion' => 'Agama',
            'occupation' => 'Pekerjaan',
            'address' => 'Alamat lengkap',
            'rt' => 'RT',
            'rw' => 'RW',
            'village_name' => 'Nama desa',
            'village_address' => 'Alamat kantor desa',
            'head_name' => 'Nama kepala desa',
            'head_nip' => 'NIP kepala desa',
            'purpose' => 'Keperluan surat',
            'letter_number' => 'Nomor surat',
            'current_date' => 'Tanggal surat dibuat'
        ];
        
        return $descriptions[$variable] ?? 'Variable tidak dikenal';
    }

    /**
     * Get variable type for validation
     */
    protected function getVariableType(string $variable): string
    {
        $types = [
            'birth_date' => 'date',
            'current_date' => 'date',
            'nik' => 'number',
            'rt' => 'number',
            'rw' => 'number',
            'gender' => 'select'
        ];
        
        return $types[$variable] ?? 'text';
    }

    /**
     * Get default bookmarks for new templates
     */
    protected function getDefaultBookmarks(): array
    {
        return [
            ['name' => 'full_name', 'description' => 'Nama lengkap pemohon', 'type' => 'text'],
            ['name' => 'nik', 'description' => 'Nomor Induk Kependudukan', 'type' => 'number'],
            ['name' => 'birth_place', 'description' => 'Tempat lahir', 'type' => 'text'],
            ['name' => 'birth_date', 'description' => 'Tanggal lahir', 'type' => 'date'],
            ['name' => 'gender', 'description' => 'Jenis kelamin', 'type' => 'select'],
            ['name' => 'religion', 'description' => 'Agama', 'type' => 'text'],
            ['name' => 'occupation', 'description' => 'Pekerjaan', 'type' => 'text'],
            ['name' => 'address', 'description' => 'Alamat lengkap', 'type' => 'text'],
            ['name' => 'village_name', 'description' => 'Nama desa', 'type' => 'text'],
            ['name' => 'head_name', 'description' => 'Nama kepala desa', 'type' => 'text'],
            ['name' => 'purpose', 'description' => 'Keperluan surat', 'type' => 'text'],
            ['name' => 'current_date', 'description' => 'Tanggal surat', 'type' => 'date']
        ];
    }

    /**
     * Get default replacement mapping
     */
    protected function getDefaultReplacementMap(): array
    {
        return [
            'full_name' => 'full_name',
            'nik' => 'nik',
            'birth_place' => 'birth_place',
            'birth_date' => 'birth_date',
            'gender' => 'gender',
            'religion' => 'religion',
            'occupation' => 'occupation',
            'address' => 'address',
            'rt' => 'rt',
            'rw' => 'rw',
            'village_name' => 'village_profile.name',
            'village_address' => 'village_profile.address',
            'head_name' => 'village_profile.head_name',
            'head_nip' => 'village_profile.head_nip',
            'purpose' => 'purpose',
            'letter_number' => 'letter_number',
            'current_date' => 'current_date'
        ];
    }

    /**
     * Delete template file
     */
    public function deleteTemplate(string $filePath): bool
    {
        return Storage::disk('local')->delete($filePath);
    }

    /**
     * Get template file info
     */
    public function getTemplateInfo(string $filePath): array
    {
        if (!Storage::disk('local')->exists($filePath)) {
            throw new Exception('Template file tidak ditemukan');
        }
        
        $fullPath = Storage::disk('local')->path($filePath);
        
        return [
            'path' => $filePath,
            'full_path' => $fullPath,
            'size' => Storage::disk('local')->size($filePath),
            'last_modified' => Storage::disk('local')->lastModified($filePath),
            'exists' => file_exists($fullPath),
            'readable' => is_readable($fullPath)
        ];
    }
}