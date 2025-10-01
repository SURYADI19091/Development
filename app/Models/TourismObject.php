<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TourismObject extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'category',
        'address',
        'settlement_id',
        'latitude',
        'longitude',
        'contact_info',
        'operating_hours',
        'entry_fee',
        'facilities',
        'images',
        'rating',
        'total_reviews',
        'is_active',
        'featured',
        'is_featured',
        'accessibility',
    ];

    protected $casts = [
        'settlement_id' => 'integer',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'entry_fee' => 'decimal:2',
        'facilities' => 'array',
        'images' => 'array',
        'rating' => 'decimal:2',
        'total_reviews' => 'integer',
        'is_active' => 'boolean',
        'featured' => 'boolean',
        'is_featured' => 'boolean',
        'accessibility' => 'array',
    ];

    public function settlement(): BelongsTo
    {
        return $this->belongsTo(Settlement::class, 'settlement_id');
    }
}