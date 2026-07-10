<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StartupMilestone extends Model
{
    protected $fillable = [
        'startup_id',
        'title',
        'description',
        'achieved_at',
    ];

    protected function casts(): array
    {
        return [
            'achieved_at' => 'date',
        ];
    }

    public function startup(): BelongsTo
    {
        return $this->belongsTo(Startup::class);
    }
}
