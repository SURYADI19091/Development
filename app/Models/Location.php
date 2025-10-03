<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'type',
        'latitude',
        'longitude',
        'area_size',
        'area_coordinates',
        'address',
        'phone',
        'email',
        'operating_hours',
        'icon',
        'color',
        'is_active',
        'show_on_map',
        'sort_order',
        'image_path',
        'created_by'
    ];

    protected $casts = [
        'operating_hours' => 'array',
        'area_coordinates' => 'array',
        'is_active' => 'boolean',
        'show_on_map' => 'boolean',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'area_size' => 'decimal:2',
        'sort_order' => 'integer'
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOnMap($query)
    {
        return $query->where('show_on_map', true);
    }

    public function getTypeNameAttribute(): string
    {
        $types = [
            'office' => 'Kantor/Pemerintahan',
            'school' => 'Pendidikan',
            'health' => 'Kesehatan',
            'religious' => 'Tempat Ibadah',
            'commercial' => 'Perdagangan',
            'public' => 'Fasilitas Umum',
            'tourism' => 'Wisata',
            'other' => 'Lainnya'
        ];

        return $types[$this->type] ?? 'Tidak Diketahui';
    }

    public function getFormattedAreaAttribute(): string
    {
        if (!$this->area_size) {
            return '-';
        }

        $area = floatval($this->area_size);
        
        if ($area < 1000) {
            return number_format($area, 2) . ' m²';
        } elseif ($area < 10000) {
            return number_format($area / 10000, 4) . ' Ha';
        } elseif ($area < 1000000) {
            return number_format($area / 10000, 2) . ' Ha';
        } else {
            return number_format($area / 1000000, 2) . ' km²';
        }
    }
}
