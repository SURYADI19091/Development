<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Gallery extends Model
{
    use HasFactory;

    protected $table = 'galleries';

    protected $fillable = [
        'title',
        'description',
        'image_path',
        'category',
        'photographer',
        'location',
        'taken_at',
        'uploaded_by',
        'event_date',
        'tags',
        'is_featured',
        'likes_count',
        'views_count',
        'alt_text',
    ];

    protected $casts = [
        'event_date' => 'date',
        'taken_at' => 'date',
        'tags' => 'array',
        'is_featured' => 'boolean',
        'likes_count' => 'integer',
        'views_count' => 'integer',
    ];

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function likes(): HasMany
    {
        return $this->hasMany(GalleryLike::class, 'gallery_id');
    }
}