<?php

namespace App\Enums;

enum StudentApplicationType: string
{
    case Job = 'job';
    case Internship = 'internship';
    case Grant = 'grant';
    case Other = 'other';

    public function label(): string
    {
        return match ($this) {
            self::Job => 'Job',
            self::Internship => 'Internship',
            self::Grant => 'Grant',
            self::Other => 'Other',
        };
    }
}
