<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Infrastructure extends Model
{
    use HasFactory;

    protected $fillable = [
        'category',
        'name',
        'label',
        'value',
        'unit',
        'order',
        'is_active'
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'order' => 'integer',
        'is_active' => 'boolean'
    ];

    // Get infrastructure data grouped by category
    public static function getGroupedData()
    {
        return self::where('is_active', true)
            ->orderBy('category')
            ->orderBy('order')
            ->get()
            ->groupBy('category')
            ->map(function ($items) {
                return $items->keyBy('name');
            });
    }
}
