<?php

namespace App\Models;

use App\Enums\UserRole;
use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'uuid',
        'first_name',
        'last_name',
        'email',
        'password',
        'avatar',
        'bio',
        'website',
        'linkedin_url',
        'twitter_url',
        'profile_visibility',
        'phone',
        'timezone',
        'locale',
        'is_active',
        'profile_completed_at',
        'last_login_at',
        'last_login_ip',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'profile_completed_at' => 'datetime',
            'last_login_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (User $user) {
            if (empty($user->uuid)) {
                $user->uuid = (string) Str::uuid();
            }
        });
    }

    public function getNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->last_name}");
    }

    public function getInitialsAttribute(): string
    {
        return strtoupper(
            substr($this->first_name, 0, 1).substr($this->last_name, 0, 1)
        );
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_roles')
            ->withPivot('is_primary')
            ->withTimestamps();
    }

    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function studentProfile(): HasOne
    {
        return $this->hasOne(StudentProfile::class);
    }

    public function investorProfile(): HasOne
    {
        return $this->hasOne(InvestorProfile::class);
    }

    public function mentorProfile(): HasOne
    {
        return $this->hasOne(MentorProfile::class);
    }

    public function freelancerProfile(): HasOne
    {
        return $this->hasOne(FreelancerProfile::class);
    }

    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class, 'user_skills')
            ->withPivot('proficiency', 'years')
            ->withTimestamps();
    }

    public function studentPortfolioItems(): HasMany
    {
        return $this->hasMany(StudentPortfolioItem::class)->orderBy('sort_order');
    }

    public function studentCertificates(): HasMany
    {
        return $this->hasMany(StudentCertificate::class)->latest('issued_at');
    }

    public function studentApplications(): HasMany
    {
        return $this->hasMany(StudentApplication::class)->latest('applied_at');
    }

    public function bookmarks(): HasMany
    {
        return $this->hasMany(Bookmark::class);
    }

    public function organizationMemberships(): HasMany
    {
        return $this->hasMany(OrganizationMember::class);
    }

    public function organizations(): BelongsToMany
    {
        return $this->belongsToMany(Organization::class, 'organization_members')
            ->withPivot(['role', 'title', 'is_public', 'joined_at'])
            ->withTimestamps();
    }

    public function roleProfile(): ?Model
    {
        $role = $this->primaryRole()?->name;

        return match ($role) {
            UserRole::Student->value => $this->studentProfile,
            UserRole::Investor->value => $this->investorProfile,
            UserRole::Mentor->value => $this->mentorProfile,
            UserRole::Freelancer->value => $this->freelancerProfile,
            default => null,
        };
    }

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    public function primaryRole(): ?Role
    {
        return $this->roles()->wherePivot('is_primary', true)->first()
            ?? $this->roles()->first();
    }

    public function hasRole(string|UserRole $role): bool
    {
        $name = $role instanceof UserRole ? $role->value : $role;

        return $this->roles()->where('name', $name)->exists();
    }

    public function hasAnyRole(array $roles): bool
    {
        $names = array_map(
            fn ($role) => $role instanceof UserRole ? $role->value : $role,
            $roles
        );

        return $this->roles()->whereIn('name', $names)->exists();
    }

    public function hasPermission(string $permission): bool
    {
        return $this->roles()
            ->whereHas('permissions', fn ($q) => $q->where('name', $permission))
            ->exists();
    }

    public function assignRole(string|UserRole $role, bool $primary = false): void
    {
        $name = $role instanceof UserRole ? $role->value : $role;
        $roleModel = Role::where('name', $name)->firstOrFail();

        if ($primary) {
            $this->load('roles');
            foreach ($this->roles as $existingRole) {
                $this->roles()->updateExistingPivot($existingRole->id, ['is_primary' => false]);
            }
        }

        $this->roles()->syncWithoutDetaching([
            $roleModel->id => ['is_primary' => $primary],
        ]);
    }

    public function isProfileComplete(): bool
    {
        return $this->profile_completed_at !== null;
    }

    public function isAdmin(): bool
    {
        return $this->hasAnyRole([
            UserRole::Admin,
            UserRole::SuperAdmin,
        ]);
    }

    public function canAccessAdmin(): bool
    {
        return $this->hasAnyRole([
            UserRole::Admin,
            UserRole::SuperAdmin,
            UserRole::Moderator,
            UserRole::Support,
        ]);
    }

    public function dashboardRoute(): string
    {
        $primary = $this->primaryRole();

        if (! $primary) {
            return 'onboarding.role';
        }

        $role = UserRole::tryFrom($primary->name);

        return $role?->dashboardRoute() ?? 'dashboard';
    }
}
