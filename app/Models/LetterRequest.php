<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LetterRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'applicant_name',
        'applicant_nik',
        'applicant_address',
        'applicant_phone',
        'applicant_email',
        'letter_type',
        'purpose',
        'additional_data',
        'requested_date',
        'processed_by',
        'approved_by',
        'status',
        'notes',
        'attachments',
        'completed_date',
        'letter_number',
        'fee_amount',
        'payment_status',
    ];

    protected $casts = [
        'additional_data' => 'array',
        'requested_date' => 'date',
        'processed_by' => 'integer',
        'approved_by' => 'integer',
        'attachments' => 'array',
        'completed_date' => 'date',
        'fee_amount' => 'decimal:2',
    ];

    public function processor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}