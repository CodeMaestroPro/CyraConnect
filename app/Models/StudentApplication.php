<?php

namespace App\Models;

use App\Enums\StudentApplicationStatus;
use App\Enums\StudentApplicationType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentApplication extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'company',
        'type',
        'status',
        'applied_at',
        'external_url',
        'notes',
        'job_id',
    ];

    protected function casts(): array
    {
        return [
            'type' => StudentApplicationType::class,
            'status' => StudentApplicationStatus::class,
            'applied_at' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
