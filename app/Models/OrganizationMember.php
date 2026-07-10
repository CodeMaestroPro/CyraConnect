<?php

namespace App\Models;

use App\Enums\OrganizationMemberRole;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrganizationMember extends Model
{
    protected $fillable = [
        'organization_id',
        'user_id',
        'role',
        'title',
        'is_public',
        'joined_at',
    ];

    protected function casts(): array
    {
        return [
            'role' => OrganizationMemberRole::class,
            'is_public' => 'boolean',
            'joined_at' => 'datetime',
        ];
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
