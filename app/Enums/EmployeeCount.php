<?php

namespace App\Enums;

enum EmployeeCount: string
{
    case Micro = '1-10';
    case Small = '11-50';
    case Medium = '51-200';
    case Large = '201-500';
    case Enterprise = '500+';

    public function label(): string
    {
        return match ($this) {
            self::Micro => '1–10 employees',
            self::Small => '11–50 employees',
            self::Medium => '51–200 employees',
            self::Large => '201–500 employees',
            self::Enterprise => '500+ employees',
        };
    }
}
