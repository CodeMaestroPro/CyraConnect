<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MentorProfile extends Model
{
    protected $fillable = [
        'user_id',
        'headline',
        'expertise_areas',
        'years_experience',
        'hourly_rate',
        'is_available',
        'max_sessions_per_week',
    ];

    protected function casts(): array
    {
        return [
            'expertise_areas' => 'array',
            'hourly_rate' => 'decimal:2',
            'is_available' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
