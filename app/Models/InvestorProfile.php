<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvestorProfile extends Model
{
    protected $fillable = [
        'user_id',
        'investor_type',
        'firm_name',
        'title',
        'investment_thesis',
        'min_check_size',
        'max_check_size',
        'preferred_stages',
        'preferred_sectors',
        'is_actively_investing',
    ];

    protected function casts(): array
    {
        return [
            'preferred_stages' => 'array',
            'preferred_sectors' => 'array',
            'min_check_size' => 'decimal:2',
            'max_check_size' => 'decimal:2',
            'is_actively_investing' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
