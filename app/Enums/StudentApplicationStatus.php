<?php

namespace App\Enums;

enum StudentApplicationStatus: string
{
    case Applied = 'applied';
    case Screening = 'screening';
    case Interview = 'interview';
    case Offer = 'offer';
    case Rejected = 'rejected';
    case Withdrawn = 'withdrawn';

    public function label(): string
    {
        return match ($this) {
            self::Applied => 'Applied',
            self::Screening => 'Screening',
            self::Interview => 'Interview',
            self::Offer => 'Offer',
            self::Rejected => 'Rejected',
            self::Withdrawn => 'Withdrawn',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Applied => 'slate',
            self::Screening => 'cyra',
            self::Interview => 'purple',
            self::Offer => 'emerald',
            self::Rejected => 'red',
            self::Withdrawn => 'slate',
        };
    }
}
