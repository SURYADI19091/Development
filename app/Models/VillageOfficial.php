<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VillageOfficial extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'position',
        'nip',
        'education',
        'work_period',
        'phone',
        'email',
        'address',
        'photo_path',
        'specialization',
        'work_area',
        'is_active',
        'start_date',
        'end_date',
        'order',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    public function getPositionTitleAttribute()
    {
        $titles = [
            'kepala_desa' => 'Kepala Desa',
            'sekretaris_desa' => 'Sekretaris Desa',
            'kaur_pemerintahan' => 'Kepala Urusan Pemerintahan',
            'kaur_keuangan' => 'Kepala Urusan Keuangan',
            'kaur_pelayanan' => 'Kepala Urusan Pelayanan',
            'kadus' => 'Kepala Dusun',
            'staff' => 'Staff',
        ];
        
        return $titles[$this->position] ?? ucfirst(str_replace('_', ' ', $this->position));
    }

    public function getSpecializationListAttribute()
    {
        return $this->specialization ? explode(',', $this->specialization) : [];
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByPosition($query, $position)
    {
        return $query->where('position', $position);
    }
}