<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LetterTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'letter_type',
        'description',
        'template_file',
        'template_file_original_name',
        'template_file_size',
        'template_file_mime_type',
        'template_type',
        'template_content',
        'required_fields',
        'variables',
        'replacement_map',
        'word_bookmarks',
        'header_logo_path',
        'letter_header',
        'letter_footer',
        'format',
        'orientation',
        'margins',
        'word_settings',
        'is_active',
        'sort_order',
        'usage_count',
        'last_used_at',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'required_fields' => 'array',
        'variables' => 'array',
        'replacement_map' => 'array',
        'word_bookmarks' => 'array',
        'margins' => 'array',
        'word_settings' => 'array',
        'is_active' => 'boolean',
        'template_file_size' => 'integer',
        'usage_count' => 'integer',
        'last_used_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByLetterType($query, $letterType)
    {
        return $query->where('letter_type', $letterType);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    // Accessors
    public function getLetterTypeNameAttribute()
    {
        $letterTypeNames = [
            'domisili' => 'Surat Keterangan Domisili',
            'usaha' => 'Surat Keterangan Usaha',
            'tidak_mampu' => 'Surat Keterangan Tidak Mampu',
            'penghasilan' => 'Surat Keterangan Penghasilan',
            'pengantar_ktp' => 'Surat Pengantar KTP',
            'pengantar_kk' => 'Surat Pengantar KK',
            'pengantar_akta' => 'Surat Pengantar Akta',
            'pengantar_nikah' => 'Surat Pengantar Nikah',
            'kelahiran' => 'Surat Keterangan Kelahiran',
            'kematian' => 'Surat Keterangan Kematian',
            'pindah' => 'Surat Keterangan Pindah',
            'beda_nama' => 'Surat Keterangan Beda Nama',
            'kehilangan' => 'Surat Keterangan Kehilangan',
            'lainnya' => 'Lainnya'
        ];

        return $letterTypeNames[$this->letter_type] ?? $this->letter_type;
    }

    public function getStatusBadgeAttribute()
    {
        return $this->is_active ? 'bg-success text-white' : 'bg-secondary text-white';
    }

    public function getStatusTextAttribute()
    {
        return $this->is_active ? 'Aktif' : 'Tidak Aktif';
    }

    // Methods
    public function processTemplate($data = [])
    {
        $content = $this->template_content;
        
        // Replace variables with data
        foreach ($data as $key => $value) {
            $content = str_replace('{{' . $key . '}}', $value, $content);
            $content = str_replace('{' . $key . '}', $value, $content);
        }

        // Add default village data if not provided
        $villageProfile = \App\Models\VillageProfile::first();
        if ($villageProfile) {
            $defaultData = [
                'village_name' => $villageProfile->village_name,
                'village_address' => $villageProfile->address,
                'village_phone' => $villageProfile->phone,
                'village_email' => $villageProfile->email,
                'head_name' => $villageProfile->head_name,
                'head_nip' => $villageProfile->head_nip,
            ];

            foreach ($defaultData as $key => $value) {
                $content = str_replace('{{' . $key . '}}', $value, $content);
                $content = str_replace('{' . $key . '}', $value, $content);
            }
        }

        return $content;
    }

    public function getRequiredFieldsList()
    {
        return $this->required_fields ?? [];
    }

    public function getVariablesList()
    {
        return $this->variables ?? [];
    }

    public function duplicate()
    {
        $template = $this->replicate();
        $template->name = $this->name . ' (Copy)';
        $template->code = $this->code . '_copy_' . time();
        $template->is_active = false;
        $template->created_by = auth()->id();
        $template->save();

        return $template;
    }

    /**
     * Check if template is Word type
     */
    public function isWordTemplate(): bool
    {
        return $this->template_type === 'word' && !empty($this->template_file);
    }

    /**
     * Check if template is HTML type
     */
    public function isHtmlTemplate(): bool
    {
        return $this->template_type === 'html' || empty($this->template_file);
    }

    /**
     * Get template file path
     */
    public function getTemplateFilePath(): ?string
    {
        if (empty($this->template_file)) {
            return null;
        }
        
        return storage_path('app/' . $this->template_file);
    }

    /**
     * Get template file URL for download
     */
    public function getTemplateFileUrl(): ?string
    {
        if (empty($this->template_file)) {
            return null;
        }
        
        return route('backend.letter-templates.download', $this->id);
    }

    /**
     * Get formatted file size
     */
    public function getFormattedFileSizeAttribute(): string
    {
        if (empty($this->template_file_size)) {
            return '-';
        }
        
        $bytes = $this->template_file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Get available bookmarks for Word template
     */
    public function getAvailableBookmarks(): array
    {
        if ($this->isWordTemplate() && !empty($this->word_bookmarks)) {
            return $this->word_bookmarks;
        }
        
        // Return default bookmarks
        return $this->getDefaultBookmarks();
    }

    /**
     * Get default bookmarks for templates
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
            ['name' => 'rt', 'description' => 'RT', 'type' => 'number'],
            ['name' => 'rw', 'description' => 'RW', 'type' => 'number'],
            ['name' => 'village_name', 'description' => 'Nama desa', 'type' => 'text'],
            ['name' => 'village_address', 'description' => 'Alamat kantor desa', 'type' => 'text'],
            ['name' => 'head_name', 'description' => 'Nama kepala desa', 'type' => 'text'],
            ['name' => 'head_nip', 'description' => 'NIP kepala desa', 'type' => 'text'],
            ['name' => 'purpose', 'description' => 'Keperluan surat', 'type' => 'text'],
            ['name' => 'letter_number', 'description' => 'Nomor surat', 'type' => 'text'],
            ['name' => 'current_date', 'description' => 'Tanggal surat', 'type' => 'date']
        ];
    }
}
