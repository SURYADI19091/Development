<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'image_path',
        'link_url',
        'description',
        'is_active',
        'start_date',
        'end_date',
        'sort_order',
        'click_count',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'start_date' => 'date',
        'end_date' => 'date',
        'sort_order' => 'integer',
        'click_count' => 'integer',
    ];
}