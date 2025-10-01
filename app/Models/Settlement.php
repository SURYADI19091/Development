<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Settlement extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'code',
        'description',
        'hamlet_name',
        'hamlet_leader',
        'neighborhood_name',
        'neighborhood_number',
        'community_name',
        'community_number',
        'district',
        'regency',
        'province',
        'area_size',
        'population',
        'postal_code',
        'latitude',
        'longitude',
        'is_active',
    ];

    protected $casts = [
        'area_size' => 'decimal:2',
        'population' => 'integer',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'is_active' => 'boolean',
    ];

    public function populationData(): HasMany
    {
        return $this->hasMany(PopulationData::class, 'settlement_id');
    }

    public function umkms(): HasMany
    {
        return $this->hasMany(Umkm::class, 'settlement_id');
    }

    public function tourismObjects(): HasMany
    {
        return $this->hasMany(TourismObject::class, 'settlement_id');
    }
}