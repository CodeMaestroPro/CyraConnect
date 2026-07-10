<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentCertificate extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'issuer',
        'issued_at',
        'credential_url',
        'file',
        'is_public',
    ];

    protected function casts(): array
    {
        return [
            'issued_at' => 'date',
            'is_public' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
