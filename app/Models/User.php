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
        'role',
        'is_active',
        'nik',
        'phone',
        'address',
        'last_login_at',
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
            'last_login_at' => 'datetime',
        ];
    }

    /**
     * Get user's role relationship
     */
    public function roleObject()
    {
        return $this->belongsTo(Role::class, 'role', 'name');
    }

    /**
     * Get user's direct permissions
     */
    public function permissions(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'user_permissions')
                    ->withPivot('type')
                    ->withTimestamps();
    }

    /**
     * Check if user has a specific permission
     */
    public function hasPermission(string $permission): bool
    {
        // Super admin has all permissions unless explicitly denied
        if ($this->role === 'super_admin') {
            $userPermission = $this->permissions()
                ->where('permissions.name', $permission)
                ->first();
                
            if ($userPermission && $userPermission->pivot->type === 'deny') {
                return false; // Explicit deny overrides super admin
            }
            
            return true; // Super admin default
        }

        // Check direct user permissions first (explicit grant/deny)
        $userPermission = $this->permissions()
            ->where('permissions.name', $permission)
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
     * Check if user is active
     */
    public function isActive(): bool
    {
        return $this->is_active === true;
    }

    /**
     * Grant permission to user
     */
    public function grantPermission(string $permissionName)
    {
        $permission = Permission::where('name', $permissionName)->first();
        if ($permission) {
            $this->permissions()->syncWithoutDetaching([
                $permission->id => ['type' => 'grant']
            ]);
        }
    }

    /**
     * Deny permission to user  
     */
    public function denyPermission(string $permissionName)
    {
        $permission = Permission::where('name', $permissionName)->first();
        if ($permission) {
            $this->permissions()->syncWithoutDetaching([
                $permission->id => ['type' => 'deny']
            ]);
        }
    }

    /**
     * Remove permission from user
     */
    public function removePermission(string $permissionName)
    {
        $permission = Permission::where('name', $permissionName)->first();
        if ($permission) {
            $this->permissions()->detach($permission->id);
        }
    }
}
