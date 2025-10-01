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
        'registered_at',
        'registered_ip',
        'user_agent',
        'password_changed_at',
        'last_login_at',
        'last_login_ip',
        'login_attempts',
        'locked_until',
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
            'registered_at' => 'datetime',
            'password_changed_at' => 'datetime',
            'last_login_at' => 'datetime',
            'locked_until' => 'datetime',
        ];
    }

    /**
     * Check if user has a specific role.
     */
    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    /**
     * Check if user has any of the given roles.
     */
    public function hasAnyRole(array $roles): bool
    {
        return in_array($this->role, $roles);
    }

    /**
     * Check if user is active.
     */
    public function isActive(): bool
    {
        return $this->is_active === true;
    }

    /**
     * Check if user is verified.
     */
    public function isVerified(): bool
    {
        return $this->email_verified_at !== null;
    }

    /**
     * Get user's role object
     */
    public function roleObject(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Role::class, 'role', 'name');
    }

    /**
     * Get user's permissions through role
     */
    public function permissions(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'user_permissions')
                    ->withPivot('type')
                    ->withTimestamps();
    }

    /**
     * Check if user has specific permission
     */
    public function hasPermission(string $permission): bool
    {
        // Super admin has all permissions
        if ($this->role === 'super_admin') {
            return true;
        }

        // Check direct user permissions first
        $userPermission = $this->permissions()
            ->where('name', $permission)
            ->first();
        
        if ($userPermission) {
            return $userPermission->pivot->type === 'grant';
        }

        // Check role permissions
        $roleObject = $this->roleObject;
        if ($roleObject) {
            return $roleObject->hasPermission($permission);
        }

        return false;
    }

    /**
     * Grant permission to user
     */
    public function grantPermission(string $permission): void
    {
        $permissionModel = Permission::where('name', $permission)->first();
        if ($permissionModel) {
            $this->permissions()->syncWithoutDetaching([
                $permissionModel->id => ['type' => 'grant']
            ]);
        }
    }

    /**
     * Deny permission to user  
     */
    public function denyPermission(string $permission): void
    {
        $permissionModel = Permission::where('name', $permission)->first();
        if ($permissionModel) {
            $this->permissions()->syncWithoutDetaching([
                $permissionModel->id => ['type' => 'deny']
            ]);
        }
    }

    /**
     * Check if user is locked.
     */
    public function isLocked(): bool
    {
        return $this->locked_until && $this->locked_until->isFuture();
    }

    /**
     * Check if user is admin.
     */
    public function isAdmin(): bool
    {
        return in_array($this->role, ['admin', 'super_admin']);
    }

    /**
     * Check if user is super admin.
     */
    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    /**
     * Get user's full name with role.
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->name . ' (' . ucfirst($this->role) . ')';
    }

    /**
     * Get user's avatar URL.
     */
    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            return asset('storage/avatars/' . $this->avatar);
        }

        // Generate avatar with initials
        $initials = collect(explode(' ', $this->name))
            ->map(fn($name) => strtoupper(substr($name, 0, 1)))
            ->take(2)
            ->implode('');

        return 'https://ui-avatars.com/api/?name=' . urlencode($initials) . 
               '&background=3B82F6&color=ffffff&size=200&rounded=true';
    }

    /**
     * Scope for active users.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for verified users.
     */
    public function scopeVerified($query)
    {
        return $query->whereNotNull('email_verified_at');
    }

    /**
     * Scope for users with specific role.
     */
    public function scopeWithRole($query, string $role)
    {
        return $query->where('role', $role);
    }

    /**
     * Scope for admin users.
     */
    public function scopeAdmins($query)
    {
        return $query->whereIn('role', ['admin', 'super_admin']);
    }

    /**
     * Scope for regular users.
     */
    public function scopeUsers($query)
    {
        return $query->whereIn('role', ['user', 'member', 'resident']);
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
