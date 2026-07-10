<?php

namespace App\Enums;

enum OrganizationType: string
{
    case Startup = 'startup';
    case TechHub = 'tech_hub';
    case Corporate = 'corporate';
    case University = 'university';
    case Government = 'government';
    case Ngo = 'ngo';

    public function label(): string
    {
        return match ($this) {
            self::Startup => 'Startup',
            self::TechHub => 'Tech Hub',
            self::Corporate => 'Corporate',
            self::University => 'University',
            self::Government => 'Government',
            self::Ngo => 'NGO',
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::Startup => 'Early-stage or growth company building innovative products',
            self::TechHub => 'Innovation hub, incubator, or accelerator',
            self::Corporate => 'Established company or enterprise',
            self::University => 'Higher education institution',
            self::Government => 'Government agency or public sector body',
            self::Ngo => 'Non-profit or non-governmental organization',
        };
    }
}
