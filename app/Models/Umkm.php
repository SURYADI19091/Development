<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Umkm extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_name',
        'slug',
        'owner_name',
        'category',
        'description',
        'address',
        'settlement_id',
        'phone',
        'email',
        'website',
        'operating_hours',
        'products',
        'services',
        'price_range',
        'employee_count',
        'monthly_revenue',
        'logo_path',
        'photos',
        'rating',
        'total_reviews',
        'is_active',
        'is_verified',
        'registered_at',
    ];

    protected $casts = [
        'employee_count' => 'integer',
        'monthly_revenue' => 'decimal:2',
        'photos' => 'array',
        'rating' => 'decimal:2',
        'total_reviews' => 'integer',
        'is_active' => 'boolean',
        'is_verified' => 'boolean',
        'registered_at' => 'date',
    ];

    // Accessor to ensure photos is always an array
    public function getPhotosAttribute($value)
    {
        if (is_null($value) || $value === '') {
            return [];
        }
        return is_string($value) ? json_decode($value, true) ?? [] : (is_array($value) ? $value : []);
    }

    public function settlement(): BelongsTo
    {
        return $this->belongsTo(Settlement::class, 'settlement_id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(UmkmReview::class, 'umkm_id');
    }
}