<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentProfile extends Model
{
    protected $fillable = [
        'user_id',
        'headline',
        'university',
        'field_of_study',
        'graduation_year',
        'github_url',
        'portfolio_url',
        'resume',
        'is_open_to_internships',
        'is_open_to_jobs',
    ];

    protected function casts(): array
    {
        return [
            'is_open_to_internships' => 'boolean',
            'is_open_to_jobs' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
