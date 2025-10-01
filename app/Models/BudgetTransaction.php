<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BudgetTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'budget_id',
        'transaction_date',
        'transaction_type',
        'amount',
        'description',
        'reference_number',
        'vendor_supplier',
        'approved_by',
        'receipt_path',
        'notes',
    ];

    protected $casts = [
        'transaction_date' => 'date',
        'amount' => 'decimal:2',
        'approved_by' => 'integer',
    ];

    public function budget(): BelongsTo
    {
        return $this->belongsTo(VillageBudget::class, 'budget_id');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}