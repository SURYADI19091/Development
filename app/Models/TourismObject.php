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
        'latitude',
        'longitude',
        'facilities',
        'ticket_price',
        'operating_hours',
        'contact_person',
        'contact_phone',
        'images',
        'is_active',
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'ticket_price' => 'decimal:2',
        'images' => 'array',
        'is_active' => 'boolean',
    ];

    // Accessor to ensure images is always an array
    public function getImagesAttribute($value)
    {
        if (is_null($value) || $value === '') {
            return [];
        }
        return is_string($value) ? json_decode($value, true) ?? [] : (is_array($value) ? $value : []);
    }


}