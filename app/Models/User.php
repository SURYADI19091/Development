<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'employee_id',
        'role',
        'phone',
        'avatar',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the news authored by this user.
     */
    public function news()
    {
        return $this->hasMany(News::class, 'author_id');
    }

    /**
     * Get the agendas organized by this user.
     */
    public function agendas()
    {
        return $this->hasMany(Agenda::class, 'organizer_id');
    }

    /**
     * Get the announcements authored by this user.
     */
    public function announcements()
    {
        return $this->hasMany(Announcement::class, 'author_id');
    }

    /**
     * Get the gallery items uploaded by this user.
     */
    public function galleries()
    {
        return $this->hasMany(Gallery::class, 'uploaded_by');
    }

    /**
     * Get the budget transactions approved by this user.
     */
    public function approvedTransactions()
    {
        return $this->hasMany(BudgetTransaction::class, 'approved_by');
    }

    /**
     * Get the letter requests processed by this user.
     */
    public function processedLetters()
    {
        return $this->hasMany(LetterRequest::class, 'processed_by');
    }

    /**
     * Get the letter requests approved by this user.
     */
    public function approvedLetters()
    {
        return $this->hasMany(LetterRequest::class, 'approved_by');
    }

    /**
     * Get the activity logs for this user.
     */
    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class, 'user_id');
    }

    /**
     * Get the gallery likes by this user.
     */
    public function galleryLikes()
    {
        return $this->hasMany(GalleryLike::class, 'user_id');
    }
}
