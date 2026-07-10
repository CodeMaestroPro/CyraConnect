<?php

namespace App\Enums;

enum BusinessModel: string
{
    case B2b = 'b2b';
    case B2c = 'b2c';
    case B2b2c = 'b2b2c';
    case Marketplace = 'marketplace';
    case Saas = 'saas';
    case Other = 'other';

    public function label(): string
    {
        return match ($this) {
            self::B2b => 'B2B',
            self::B2c => 'B2C',
            self::B2b2c => 'B2B2C',
            self::Marketplace => 'Marketplace',
            self::Saas => 'SaaS',
            self::Other => 'Other',
        };
    }
}
