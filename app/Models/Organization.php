<?php

namespace App\Models;

use App\Enums\EmployeeCount;
use App\Enums\OrganizationType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Organization extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'uuid',
        'type',
        'name',
        'slug',
        'tagline',
        'description',
        'logo',
        'cover_image',
        'website',
        'email',
        'phone',
        'country_id',
        'state_id',
        'city_id',
        'address',
        'latitude',
        'longitude',
        'founded_year',
        'employee_count',
        'is_verified',
        'verified_at',
        'verified_by',
        'is_featured',
        'is_active',
        'settings',
    ];

    protected function casts(): array
    {
        return [
            'type' => OrganizationType::class,
            'employee_count' => EmployeeCount::class,
            'is_verified' => 'boolean',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'verified_at' => 'datetime',
            'settings' => 'array',
            'latitude' => 'decimal:8',
            'longitude' => 'decimal:8',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Organization $organization) {
            if (empty($organization->uuid)) {
                $organization->uuid = (string) Str::uuid();
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function members(): HasMany
    {
        return $this->hasMany(OrganizationMember::class);
    }

    public function startup(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Startup::class);
    }

    public function isStartup(): bool
    {
        return $this->type === OrganizationType::Startup;
    }

    public function publicMembers(): HasMany
    {
        return $this->members()->where('is_public', true)->with('user');
    }

    public function locationLabel(): ?string
    {
        $parts = array_filter([
            $this->city?->name,
            $this->state?->name,
            $this->country?->name,
        ]);

        return $parts ? implode(', ', $parts) : null;
    }
}
