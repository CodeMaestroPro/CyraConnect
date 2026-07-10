<?php

namespace App\Enums;

enum UserRole: string
{
    case Student = 'student';
    case StartupFounder = 'startup_founder';
    case Investor = 'investor';
    case TechHub = 'tech_hub';
    case University = 'university';
    case Corporate = 'corporate';
    case Government = 'government';
    case Ngo = 'ngo';
    case Mentor = 'mentor';
    case Recruiter = 'recruiter';
    case Freelancer = 'freelancer';
    case ServiceProvider = 'service_provider';
    case Admin = 'administrator';
    case SuperAdmin = 'super_administrator';
    case Moderator = 'moderator';
    case Support = 'support_team';

    public function label(): string
    {
        return match ($this) {
            self::Student => 'Student',
            self::StartupFounder => 'Startup Founder',
            self::Investor => 'Investor',
            self::TechHub => 'Tech Hub',
            self::University => 'University',
            self::Corporate => 'Corporate',
            self::Government => 'Government',
            self::Ngo => 'NGO',
            self::Mentor => 'Mentor',
            self::Recruiter => 'Recruiter',
            self::Freelancer => 'Freelancer',
            self::ServiceProvider => 'Service Provider',
            self::Admin => 'Administrator',
            self::SuperAdmin => 'Super Administrator',
            self::Moderator => 'Moderator',
            self::Support => 'Support Team',
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::Student => 'Build skills, find internships, and launch your career',
            self::StartupFounder => 'Raise funding, hire talent, and grow your startup',
            self::Investor => 'Discover deals and manage your investment portfolio',
            self::TechHub => 'Manage programs, students, and innovation events',
            self::University => 'Partner with hubs and track graduate outcomes',
            self::Corporate => 'Post challenges, hire talent, and procure innovation',
            self::Government => 'Monitor ecosystem growth and publish programs',
            self::Ngo => 'Fund programs and track development impact',
            self::Mentor => 'Guide talent and build your reputation',
            self::Recruiter => 'Source candidates and manage hiring pipelines',
            self::Freelancer => 'Find projects and grow your client base',
            self::ServiceProvider => 'Offer professional services in the marketplace',
            self::Admin => 'Manage platform operations and content',
            self::SuperAdmin => 'Full platform control and configuration',
            self::Moderator => 'Review content and enforce community guidelines',
            self::Support => 'Assist users and handle support tickets',
        };
    }

    public function dashboardRoute(): string
    {
        return match ($this) {
            self::Student => 'student.dashboard',
            self::StartupFounder => 'startup.dashboard',
            self::Investor => 'investor.dashboard',
            self::TechHub => 'hub.dashboard',
            self::Corporate => 'corporate.dashboard',
            self::Admin, self::SuperAdmin, self::Moderator, self::Support => 'admin.dashboard',
            default => 'dashboard',
        };
    }

    /** @return list<UserRole> */
    public static function onboardingRoles(): array
    {
        return array_filter(
            self::cases(),
            fn (self $role) => ! in_array($role, [self::Admin, self::SuperAdmin, self::Moderator, self::Support], true)
        );
    }
}
