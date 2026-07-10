<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentPortfolioItem extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'url',
        'image',
        'technologies',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'technologies' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
