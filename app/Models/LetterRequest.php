<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LetterRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'request_number',
        'letter_type',
        'custom_letter_type',
        'full_name',
        'nik',
        'birth_place',
        'birth_date',
        'gender',
        'religion',
        'marital_status',
        'occupation',
        'address',
        'rt',
        'rw',
        'phone',
        'email',
        'purpose',
        'ktp_file_path',
        'kk_file_path',
        'other_files',
        'status',
        'processed_by',
        'processed_at',
        'completion_date',
        'notes',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'processed_at' => 'datetime',
        'completion_date' => 'date',
        'processed_by' => 'integer',
        'other_files' => 'array',
    ];

    public function processor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    // Alias untuk kompatibilitas dengan controller yang menggunakan $request->user
    public function user(): BelongsTo
    {
        return $this->processor();
    }
}