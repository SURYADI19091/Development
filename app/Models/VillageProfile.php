<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VillageProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'village_name',
        'district',
        'regency',
        'province',
        'village_code',
        'postal_code',
        'area_size',
        'total_population',
        'total_families',
        'male_population',
        'female_population',
        'latitude',
        'longitude',
        'altitude',
        'topography',
        'north_border',
        'south_border',
        'east_border',
        'west_border',
        'description',
        'vision',
        'mission',
        'logo_path',
    ];

    protected $casts = [
        'area_size' => 'decimal:2',
        'total_population' => 'integer',
        'total_families' => 'integer',
        'male_population' => 'integer',
        'female_population' => 'integer',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];
}