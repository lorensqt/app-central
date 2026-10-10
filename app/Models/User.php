<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
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
        'google_id',
        'avatar',
        'title_id',
        'pin',
        'is_admin',
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
            'is_admin' => 'boolean',
        ];
    }

    /**
     * Get the title associated with the user.
     */
    public function title(): BelongsTo
    {
        return $this->belongsTo(Title::class);
    }

    /**
     * The permissions assigned to the user.
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class);
    }

    /**
     * Determine if the user has a specific permission slug (super admins bypass all checks).
     */
    public function hasPermission(string $slug): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        // Check against loaded relationship if already loaded to avoid N+1 queries
        if ($this->relationLoaded('permissions')) {
            return $this->permissions->contains('slug', $slug);
        }

        return $this->permissions()->where('slug', $slug)->exists();
    }

    /**
     * Synchronize permissions for this user given an array of slugs.
     */
    public function syncPermissions(array $slugs): void
    {
        $permissionIds = Permission::whereIn('slug', $slugs)->pluck('id');
        $this->permissions()->sync($permissionIds);
    }

    /**
     * Grant a permission slug to this user.
     */
    public function givePermission(string $slug): void
    {
        $permission = Permission::where('slug', $slug)->first();
        if ($permission && !$this->permissions()->where('slug', $slug)->exists()) {
            $this->permissions()->attach($permission->id);
        }
    }

    /**
     * Determine if the user is a super admin (either the primary fallback email or explicitly marked as an admin).
     */
    public function isSuperAdmin(): bool
    {
        $superAdminEmail = config('app.super_admin_email');
        return ($superAdminEmail && $this->email === $superAdminEmail) || (bool) $this->is_admin;
    }
}
