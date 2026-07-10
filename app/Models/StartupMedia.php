<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StartupMedia extends Model
{
    protected $table = 'startup_media';

    protected $fillable = [
        'startup_id',
        'type',
        'path',
        'caption',
        'sort_order',
    ];

    public function startup(): BelongsTo
    {
        return $this->belongsTo(Startup::class);
    }
}
