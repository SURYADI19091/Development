<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UmkmReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'umkm_id',
        'reviewer_name',
        'reviewer_email',
        'rating',
        'review_text',
        'is_verified',
        'helpful_count',
        'response_from_owner',
        'photos',
    ];

    protected $casts = [
        'umkm_id' => 'integer',
        'rating' => 'decimal:1',
        'is_verified' => 'boolean',
        'helpful_count' => 'integer',
        'photos' => 'array',
    ];

    // Accessor to ensure photos is always an array
    public function getPhotosAttribute($value)
    {
        if (is_null($value) || $value === '') {
            return [];
        }
        return is_string($value) ? json_decode($value, true) ?? [] : (is_array($value) ? $value : []);
    }

    public function umkm(): BelongsTo
    {
        return $this->belongsTo(Umkm::class, 'umkm_id');
    }
}