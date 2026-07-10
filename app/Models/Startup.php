<?php

namespace App\Models;

use App\Enums\BusinessModel;
use App\Enums\FundingStage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Startup extends Model
{
    protected $fillable = [
        'organization_id',
        'funding_stage',
        'total_funding',
        'last_funding_date',
        'last_funding_amount',
        'pitch_deck',
        'business_model',
        'revenue_model',
        'monthly_users',
        'monthly_revenue',
        'is_hiring',
        'is_raising',
        'target_raise',
        'views_count',
        'verification_requested_at',
    ];

    protected function casts(): array
    {
        return [
            'funding_stage' => FundingStage::class,
            'business_model' => BusinessModel::class,
            'total_funding' => 'decimal:2',
            'last_funding_amount' => 'decimal:2',
            'target_raise' => 'decimal:2',
            'last_funding_date' => 'date',
            'is_hiring' => 'boolean',
            'is_raising' => 'boolean',
            'verification_requested_at' => 'datetime',
        ];
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function sectors(): BelongsToMany
    {
        return $this->belongsToMany(Sector::class, 'startup_sectors');
    }

    public function milestones(): HasMany
    {
        return $this->hasMany(StartupMilestone::class)->orderByDesc('achieved_at');
    }

    public function media(): HasMany
    {
        return $this->hasMany(StartupMedia::class)->orderBy('sort_order');
    }

    public function profileCompletionPercent(): int
    {
        $checks = [
            filled($this->funding_stage),
            filled($this->business_model),
            $this->sectors()->exists(),
            filled($this->pitch_deck),
            $this->milestones()->exists(),
        ];

        return (int) round((count(array_filter($checks)) / count($checks)) * 100);
    }
}
