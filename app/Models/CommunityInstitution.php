<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommunityInstitution extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'leader_name',
        'leader_title',
        'member_count',
        'description',
        'contact_phone',
        'contact_email',
        'meeting_schedule',
        'icon_class',
        'color_class',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'member_count' => 'integer',
        'sort_order' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }
}
