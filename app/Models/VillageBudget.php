<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VillageBudget extends Model
{
    use HasFactory;

    protected $fillable = [
        'fiscal_year',
        'budget_type',
        'category',
        'sub_category',
        'description',
        'planned_amount',
        'realized_amount',
        'created_by',
    ];

    protected $casts = [
        'fiscal_year' => 'integer',
        'planned_amount' => 'decimal:2',
        'realized_amount' => 'decimal:2',
        'percentage' => 'decimal:2',
    ];

    public function transactions(): HasMany
    {
        return $this->hasMany(BudgetTransaction::class, 'budget_id');
    }
}