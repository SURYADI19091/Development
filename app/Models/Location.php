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
        'is_active' => 'boolean',
        'show_on_map' => 'boolean',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
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
}
