<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VillageStatistic extends Model
{
    use HasFactory;

    protected $fillable = [
        'year',
        'month',
        'category',
        'subcategory',
        'value',
        'unit',
        'description',
        'data_source',
        'recorded_by',
        'notes',
    ];

    protected $casts = [
        'year' => 'integer',
        'month' => 'integer',
        'value' => 'decimal:2',
        'recorded_by' => 'integer',
    ];
}