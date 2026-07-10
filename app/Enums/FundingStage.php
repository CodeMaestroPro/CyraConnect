<?php

namespace App\Enums;

enum FundingStage: string
{
    case PreSeed = 'pre_seed';
    case Seed = 'seed';
    case SeriesA = 'series_a';
    case SeriesB = 'series_b';
    case SeriesC = 'series_c';
    case Growth = 'growth';
    case Ipo = 'ipo';

    public function label(): string
    {
        return match ($this) {
            self::PreSeed => 'Pre-seed',
            self::Seed => 'Seed',
            self::SeriesA => 'Series A',
            self::SeriesB => 'Series B',
            self::SeriesC => 'Series C',
            self::Growth => 'Growth',
            self::Ipo => 'IPO',
        };
    }
}
