<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GalleryLike extends Model
{
    use HasFactory;

    protected $fillable = [
        'gallery_id',
        'user_id',
        'ip_address',
    ];

    protected $casts = [
        'gallery_id' => 'integer',
        'user_id' => 'integer',
    ];

    public function gallery(): BelongsTo
    {
        return $this->belongsTo(Gallery::class, 'gallery_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}