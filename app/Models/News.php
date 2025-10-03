<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class News extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'category',
        'featured_image',
        'views_count',
        'is_featured',
        'is_published',
        'author_id',
        'published_at'
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_published' => 'boolean',
        'published_at' => 'datetime',
        'views_count' => 'integer'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($news) {
            if (empty($news->slug)) {
                $news->slug = Str::slug($news->title);
            }
            if (empty($news->author_id)) {
                $news->author_id = auth()->id();
            }
        });
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    // Alias untuk kompatibilitas dengan view yang menggunakan $news->user
    public function user()
    {
        return $this->author();
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function getCategoriesAttribute()
    {
        return [
            'kegiatan' => 'Kegiatan',
            'kesehatan' => 'Kesehatan',
            'ekonomi' => 'Ekonomi',
            'infrastruktur' => 'Infrastruktur',
            'pendidikan' => 'Pendidikan',
            'olahraga' => 'Olahraga',
            'lainnya' => 'Lainnya'
        ];
    }

    public function getCategoryLabelAttribute()
    {
        $categories = $this->categories;
        return $categories[$this->category] ?? $this->category;
    }
}
