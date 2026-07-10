# 03 — Database Schema

**Project:** CyraConnect  
**Version:** 1.0.0  
**Date:** July 10, 2026  
**Database:** MySQL 8+

---

## 1. Schema Overview

### 1.1 Design Principles
- **Normalization:** 3NF minimum, denormalization only for read performance
- **UUIDs:** Primary identifiers for public-facing entities
- **Soft Deletes:** All user-generated content
- **Audit Trails:** created_at, updated_at, deleted_at on all tables
- **Foreign Keys:** Enforced with ON DELETE RESTRICT/CASCADE as appropriate
- **Indexes:** On all foreign keys, search fields, and common query patterns

### 1.2 Entity Relationship Diagram (High-Level)

```
┌─────────────┐     ┌─────────────────┐     ┌─────────────┐
│    users    │────▶│ organization_   │◀────│organizations│
│             │     │   members       │     │             │
└──────┬──────┘     └─────────────────┘     └──────┬──────┘
       │                                              │
       │         ┌────────────────────────────────────┤
       │         │                    │               │
       ▼         ▼                    ▼               ▼
┌─────────────┐ ┌─────────────┐ ┌─────────────┐ ┌─────────────┐
│  profiles   │ │    jobs     │ │   events    │ │  startups   │
│ (polymorphic│ │             │ │             │ │  (extends   │
│  or typed)  │ │             │ │             │ │   org)      │
└─────────────┘ └──────┬──────┘ └──────┬──────┘ └─────────────┘
                       │               │
                       ▼               ▼
               ┌─────────────┐ ┌─────────────┐
               │ applications│ │ registrations│
               └─────────────┘ └─────────────┘
```

---

## 2. Core Tables

### 2.1 Users & Authentication

#### `users`
| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | BIGINT UNSIGNED | PK, AUTO_INCREMENT | Internal ID |
| uuid | CHAR(36) | UNIQUE, NOT NULL | Public identifier |
| email | VARCHAR(255) | UNIQUE, NOT NULL | Login email |
| email_verified_at | TIMESTAMP | NULL | Verification timestamp |
| password | VARCHAR(255) | NOT NULL | Bcrypt hash |
| first_name | VARCHAR(100) | NOT NULL | |
| last_name | VARCHAR(100) | NOT NULL | |
| avatar | VARCHAR(255) | NULL | Avatar path |
| phone | VARCHAR(20) | NULL | Phone number |
| phone_verified_at | TIMESTAMP | NULL | |
| timezone | VARCHAR(50) | DEFAULT 'UTC' | |
| locale | VARCHAR(10) | DEFAULT 'en' | |
| two_factor_secret | TEXT | NULL | 2FA secret |
| two_factor_confirmed_at | TIMESTAMP | NULL | |
| remember_token | VARCHAR(100) | NULL | |
| last_login_at | TIMESTAMP | NULL | |
| last_login_ip | VARCHAR(45) | NULL | |
| is_active | BOOLEAN | DEFAULT TRUE | Account status |
| profile_completed_at | TIMESTAMP | NULL | Onboarding complete |
| created_at | TIMESTAMP | | |
| updated_at | TIMESTAMP | | |
| deleted_at | TIMESTAMP | NULL | Soft delete |

**Indexes:** `uuid`, `email`, `is_active`, `created_at`

#### `roles`
| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | BIGINT UNSIGNED | PK | |
| name | VARCHAR(50) | UNIQUE | student, startup_founder, investor, etc. |
| display_name | VARCHAR(100) | NOT NULL | Human-readable name |
| description | TEXT | NULL | |
| is_system | BOOLEAN | DEFAULT FALSE | System roles can't be deleted |
| created_at | TIMESTAMP | | |
| updated_at | TIMESTAMP | | |

#### `permissions`
| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | BIGINT UNSIGNED | PK | |
| name | VARCHAR(100) | UNIQUE | startup.create, job.apply, etc. |
| display_name | VARCHAR(150) | NOT NULL | |
| module | VARCHAR(50) | NOT NULL | Grouping: startup, job, admin |
| created_at | TIMESTAMP | | |
| updated_at | TIMESTAMP | | |

#### `role_permissions`
| Column | Type | Constraints |
|--------|------|-------------|
| role_id | BIGINT UNSIGNED | FK → roles.id |
| permission_id | BIGINT UNSIGNED | FK → permissions.id |

**PK:** (role_id, permission_id)

#### `user_roles`
| Column | Type | Constraints |
|--------|------|-------------|
| id | BIGINT UNSIGNED | PK |
| user_id | BIGINT UNSIGNED | FK → users.id |
| role_id | BIGINT UNSIGNED | FK → roles.id |
| is_primary | BOOLEAN | DEFAULT FALSE |
| created_at | TIMESTAMP | |

**Unique:** (user_id, role_id)

#### `password_reset_tokens`
| Column | Type | Constraints |
|--------|------|-------------|
| email | VARCHAR(255) | PK |
| token | VARCHAR(255) | NOT NULL |
| created_at | TIMESTAMP | NULL |

#### `personal_access_tokens` (Sanctum)
Standard Laravel Sanctum table.

---

### 2.2 Organizations

#### `organizations`
| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | BIGINT UNSIGNED | PK | |
| uuid | CHAR(36) | UNIQUE | Public identifier |
| type | ENUM | NOT NULL | startup, tech_hub, corporate, university, government, ngo |
| name | VARCHAR(255) | NOT NULL | Organization name |
| slug | VARCHAR(255) | UNIQUE | URL-friendly name |
| tagline | VARCHAR(255) | NULL | Short description |
| description | TEXT | NULL | Full description |
| logo | VARCHAR(255) | NULL | Logo path |
| cover_image | VARCHAR(255) | NULL | Cover/banner image |
| website | VARCHAR(255) | NULL | |
| email | VARCHAR(255) | NULL | Contact email |
| phone | VARCHAR(20) | NULL | |
| country_id | BIGINT UNSIGNED | FK → countries.id | |
| state_id | BIGINT UNSIGNED | FK → states.id, NULL | |
| city_id | BIGINT UNSIGNED | FK → cities.id, NULL | |
| address | TEXT | NULL | Physical address |
| latitude | DECIMAL(10,8) | NULL | For map |
| longitude | DECIMAL(11,8) | NULL | For map |
| founded_year | YEAR | NULL | |
| employee_count | ENUM | NULL | 1-10, 11-50, 51-200, 201-500, 500+ |
| is_verified | BOOLEAN | DEFAULT FALSE | Verification badge |
| verified_at | TIMESTAMP | NULL | |
| verified_by | BIGINT UNSIGNED | FK → users.id, NULL | Admin who verified |
| is_featured | BOOLEAN | DEFAULT FALSE | Featured on homepage |
| is_active | BOOLEAN | DEFAULT TRUE | |
| settings | JSON | NULL | Org-specific settings |
| created_at | TIMESTAMP | | |
| updated_at | TIMESTAMP | | |
| deleted_at | TIMESTAMP | NULL | |

**Indexes:** `uuid`, `slug`, `type`, `country_id`, `is_verified`, `is_active`

#### `organization_members`
| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | BIGINT UNSIGNED | PK | |
| organization_id | BIGINT UNSIGNED | FK → organizations.id | |
| user_id | BIGINT UNSIGNED | FK → users.id | |
| role | ENUM | NOT NULL | owner, admin, member |
| title | VARCHAR(100) | NULL | Job title at org |
| is_public | BOOLEAN | DEFAULT TRUE | Show on org profile |
| joined_at | TIMESTAMP | | |
| created_at | TIMESTAMP | | |
| updated_at | TIMESTAMP | | |

**Unique:** (organization_id, user_id)

---

### 2.3 Location Tables

#### `countries`
| Column | Type | Constraints |
|--------|------|-------------|
| id | BIGINT UNSIGNED | PK |
| name | VARCHAR(100) | NOT NULL |
| code | CHAR(2) | UNIQUE (ISO 3166-1 alpha-2) |
| phone_code | VARCHAR(10) | |
| currency_code | CHAR(3) | |
| is_active | BOOLEAN | DEFAULT TRUE |

#### `states`
| Column | Type | Constraints |
|--------|------|-------------|
| id | BIGINT UNSIGNED | PK |
| country_id | BIGINT UNSIGNED | FK → countries.id |
| name | VARCHAR(100) | NOT NULL |
| code | VARCHAR(10) | NULL |

#### `cities`
| Column | Type | Constraints |
|--------|------|-------------|
| id | BIGINT UNSIGNED | PK |
| state_id | BIGINT UNSIGNED | FK → states.id |
| name | VARCHAR(100) | NOT NULL |

---

### 2.4 Profile Tables

#### `student_profiles`
| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | BIGINT UNSIGNED | PK | |
| user_id | BIGINT UNSIGNED | FK → users.id, UNIQUE | |
| headline | VARCHAR(255) | NULL | e.g., "CS Student at UNILAG" |
| bio | TEXT | NULL | |
| university | VARCHAR(255) | NULL | |
| field_of_study | VARCHAR(100) | NULL | |
| graduation_year | YEAR | NULL | |
| linkedin_url | VARCHAR(255) | NULL | |
| github_url | VARCHAR(255) | NULL | |
| portfolio_url | VARCHAR(255) | NULL | |
| resume | VARCHAR(255) | NULL | Resume file path |
| is_open_to_internships | BOOLEAN | DEFAULT TRUE | |
| is_open_to_jobs | BOOLEAN | DEFAULT TRUE | |
| created_at | TIMESTAMP | | |
| updated_at | TIMESTAMP | | |

#### `investor_profiles`
| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | BIGINT UNSIGNED | PK | |
| user_id | BIGINT UNSIGNED | FK → users.id, UNIQUE | |
| investor_type | ENUM | NOT NULL | angel, vc, corporate, impact, family_office |
| firm_name | VARCHAR(255) | NULL | |
| title | VARCHAR(100) | NULL | |
| bio | TEXT | NULL | |
| investment_thesis | TEXT | NULL | |
| min_check_size | DECIMAL(15,2) | NULL | USD |
| max_check_size | DECIMAL(15,2) | NULL | USD |
| preferred_stages | JSON | NULL | seed, series_a, etc. |
| preferred_sectors | JSON | NULL | fintech, healthtech, etc. |
| preferred_countries | JSON | NULL | Country IDs |
| linkedin_url | VARCHAR(255) | NULL | |
| is_actively_investing | BOOLEAN | DEFAULT TRUE | |
| created_at | TIMESTAMP | | |
| updated_at | TIMESTAMP | | |

#### `mentor_profiles`
| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | BIGINT UNSIGNED | PK | |
| user_id | BIGINT UNSIGNED | FK → users.id, UNIQUE | |
| headline | VARCHAR(255) | NULL | |
| bio | TEXT | NULL | |
| expertise_areas | JSON | NULL | |
| years_experience | TINYINT | NULL | |
| hourly_rate | DECIMAL(10,2) | NULL | USD, NULL = free |
| is_available | BOOLEAN | DEFAULT TRUE | |
| max_sessions_per_week | TINYINT | DEFAULT 5 | |
| linkedin_url | VARCHAR(255) | NULL | |
| created_at | TIMESTAMP | | |
| updated_at | TIMESTAMP | | |

#### `freelancer_profiles`
| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | BIGINT UNSIGNED | PK | |
| user_id | BIGINT UNSIGNED | FK → users.id, UNIQUE | |
| headline | VARCHAR(255) | NULL | |
| bio | TEXT | NULL | |
| hourly_rate | DECIMAL(10,2) | NULL | |
| daily_rate | DECIMAL(10,2) | NULL | |
| availability | ENUM | DEFAULT 'available' | available, busy, unavailable |
| years_experience | TINYINT | NULL | |
| portfolio_url | VARCHAR(255) | NULL | |
| created_at | TIMESTAMP | | |
| updated_at | TIMESTAMP | | |

---

### 2.5 Startup-Specific Tables

#### `startups` (extends organizations)
| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | BIGINT UNSIGNED | PK | |
| organization_id | BIGINT UNSIGNED | FK → organizations.id, UNIQUE | |
| funding_stage | ENUM | NULL | pre_seed, seed, series_a, series_b, series_c, growth, ipo |
| total_funding | DECIMAL(15,2) | NULL | Total raised USD |
| last_funding_date | DATE | NULL | |
| last_funding_amount | DECIMAL(15,2) | NULL | |
| pitch_deck | VARCHAR(255) | NULL | PDF path |
| business_model | ENUM | NULL | b2b, b2c, b2b2c, marketplace, saas, other |
| revenue_model | VARCHAR(100) | NULL | |
| is_hiring | BOOLEAN | DEFAULT FALSE | |
| is_raising | BOOLEAN | DEFAULT FALSE | |
| target_raise | DECIMAL(15,2) | NULL | |
| created_at | TIMESTAMP | | |
| updated_at | TIMESTAMP | | |

#### `startup_sectors`
| Column | Type | Constraints |
|--------|------|-------------|
| startup_id | BIGINT UNSIGNED | FK → startups.id |
| sector_id | BIGINT UNSIGNED | FK → sectors.id |

#### `startup_milestones`
| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | BIGINT UNSIGNED | PK | |
| startup_id | BIGINT UNSIGNED | FK → startups.id | |
| title | VARCHAR(255) | NOT NULL | |
| description | TEXT | NULL | |
| achieved_at | DATE | NOT NULL | |
| created_at | TIMESTAMP | | |
| updated_at | TIMESTAMP | | |

#### `sectors`
| Column | Type | Constraints |
|--------|------|-------------|
| id | BIGINT UNSIGNED | PK |
| name | VARCHAR(100) | UNIQUE |
| slug | VARCHAR(100) | UNIQUE |
| icon | VARCHAR(50) | NULL |
| is_active | BOOLEAN | DEFAULT TRUE |

---

### 2.6 Tech Hub Tables

#### `tech_hubs` (extends organizations)
| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | BIGINT UNSIGNED | PK | |
| organization_id | BIGINT UNSIGNED | FK → organizations.id, UNIQUE | |
| hub_type | ENUM | NOT NULL | incubator, accelerator, coworking, training, mixed |
| capacity | INT | NULL | Max students/members |
| facilities | JSON | NULL | wifi, labs, meeting_rooms, etc. |
| operating_hours | JSON | NULL | |
| created_at | TIMESTAMP | | |
| updated_at | TIMESTAMP | | |

#### `courses`
| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | BIGINT UNSIGNED | PK | |
| uuid | CHAR(36) | UNIQUE | |
| tech_hub_id | BIGINT UNSIGNED | FK → tech_hubs.id | |
| title | VARCHAR(255) | NOT NULL | |
| slug | VARCHAR(255) | NOT NULL | |
| description | TEXT | NULL | |
| curriculum | JSON | NULL | Modules/lessons |
| duration_weeks | TINYINT | NULL | |
| level | ENUM | NULL | beginner, intermediate, advanced |
| format | ENUM | NULL | online, in_person, hybrid |
| price | DECIMAL(10,2) | NULL | NULL = free |
| currency | CHAR(3) | DEFAULT 'USD' | |
| max_students | INT | NULL | |
| start_date | DATE | NULL | |
| end_date | DATE | NULL | |
| is_published | BOOLEAN | DEFAULT FALSE | |
| created_at | TIMESTAMP | | |
| updated_at | TIMESTAMP | | |
| deleted_at | TIMESTAMP | NULL | |

**Unique:** (tech_hub_id, slug)

#### `course_enrollments`
| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | BIGINT UNSIGNED | PK | |
| course_id | BIGINT UNSIGNED | FK → courses.id | |
| user_id | BIGINT UNSIGNED | FK → users.id | |
| status | ENUM | DEFAULT 'pending' | pending, approved, rejected, completed, dropped |
| progress | TINYINT | DEFAULT 0 | 0-100 percent |
| enrolled_at | TIMESTAMP | NULL | |
| completed_at | TIMESTAMP | NULL | |
| certificate_id | BIGINT UNSIGNED | FK → certificates.id, NULL | |
| created_at | TIMESTAMP | | |
| updated_at | TIMESTAMP | | |

**Unique:** (course_id, user_id)

---

### 2.7 Jobs & Applications

#### `jobs`
| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | BIGINT UNSIGNED | PK | |
| uuid | CHAR(36) | UNIQUE | |
| organization_id | BIGINT UNSIGNED | FK → organizations.id | |
| posted_by | BIGINT UNSIGNED | FK → users.id | |
| title | VARCHAR(255) | NOT NULL | |
| slug | VARCHAR(255) | NOT NULL | |
| description | TEXT | NOT NULL | |
| requirements | TEXT | NULL | |
| benefits | TEXT | NULL | |
| job_type | ENUM | NOT NULL | full_time, part_time, contract, internship, freelance |
| location_type | ENUM | NOT NULL | remote, hybrid, onsite |
| country_id | BIGINT UNSIGNED | FK → countries.id, NULL | |
| city_id | BIGINT UNSIGNED | FK → cities.id, NULL | |
| salary_min | DECIMAL(12,2) | NULL | |
| salary_max | DECIMAL(12,2) | NULL | |
| salary_currency | CHAR(3) | DEFAULT 'USD' | |
| salary_period | ENUM | DEFAULT 'yearly' | hourly, monthly, yearly |
| experience_level | ENUM | NULL | entry, mid, senior, lead, executive |
| application_deadline | DATE | NULL | |
| is_published | BOOLEAN | DEFAULT FALSE | |
| published_at | TIMESTAMP | NULL | |
| views_count | INT | DEFAULT 0 | |
| applications_count | INT | DEFAULT 0 | |
| created_at | TIMESTAMP | | |
| updated_at | TIMESTAMP | | |
| deleted_at | TIMESTAMP | NULL | |

**Indexes:** `uuid`, `organization_id`, `is_published`, `job_type`, `location_type`

#### `job_skills`
| Column | Type | Constraints |
|--------|------|-------------|
| job_id | BIGINT UNSIGNED | FK → jobs.id |
| skill_id | BIGINT UNSIGNED | FK → skills.id |

#### `job_applications`
| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | BIGINT UNSIGNED | PK | |
| job_id | BIGINT UNSIGNED | FK → jobs.id | |
| user_id | BIGINT UNSIGNED | FK → users.id | |
| cover_letter | TEXT | NULL | |
| resume | VARCHAR(255) | NULL | |
| status | ENUM | DEFAULT 'submitted' | submitted, reviewing, shortlisted, interview, offered, hired, rejected |
| notes | TEXT | NULL | Internal notes |
| reviewed_at | TIMESTAMP | NULL | |
| reviewed_by | BIGINT UNSIGNED | FK → users.id, NULL | |
| created_at | TIMESTAMP | | |
| updated_at | TIMESTAMP | | |

**Unique:** (job_id, user_id)

---

### 2.8 Grants & Opportunities

#### `grants`
| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | BIGINT UNSIGNED | PK | |
| uuid | CHAR(36) | UNIQUE | |
| title | VARCHAR(255) | NOT NULL | |
| slug | VARCHAR(255) | UNIQUE | |
| description | TEXT | NOT NULL | |
| eligibility | TEXT | NULL | |
| grant_type | ENUM | NOT NULL | grant, scholarship, accelerator, incubator, competition, hackathon |
| funding_amount_min | DECIMAL(15,2) | NULL | |
| funding_amount_max | DECIMAL(15,2) | NULL | |
| currency | CHAR(3) | DEFAULT 'USD' | |
| provider_name | VARCHAR(255) | NULL | |
| provider_url | VARCHAR(255) | NULL | |
| application_url | VARCHAR(255) | NULL | External apply link |
| deadline | DATE | NULL | |
| target_audience | JSON | NULL | students, startups, etc. |
| target_sectors | JSON | NULL | |
| target_countries | JSON | NULL | |
| is_featured | BOOLEAN | DEFAULT FALSE | |
| is_active | BOOLEAN | DEFAULT TRUE | |
| created_at | TIMESTAMP | | |
| updated_at | TIMESTAMP | | |
| deleted_at | TIMESTAMP | NULL | |

**Indexes:** `uuid`, `slug`, `grant_type`, `deadline`, `is_active`

---

### 2.9 Events

#### `events`
| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | BIGINT UNSIGNED | PK | |
| uuid | CHAR(36) | UNIQUE | |
| organization_id | BIGINT UNSIGNED | FK → organizations.id, NULL | Host org |
| created_by | BIGINT UNSIGNED | FK → users.id | |
| title | VARCHAR(255) | NOT NULL | |
| slug | VARCHAR(255) | NOT NULL | |
| description | TEXT | NULL | |
| event_type | ENUM | NOT NULL | conference, hackathon, meetup, workshop, demo_day, summit, training |
| format | ENUM | NOT NULL | in_person, virtual, hybrid |
| venue | VARCHAR(255) | NULL | |
| country_id | BIGINT UNSIGNED | FK → countries.id, NULL | |
| city_id | BIGINT UNSIGNED | FK → cities.id, NULL | |
| virtual_url | VARCHAR(255) | NULL | |
| starts_at | TIMESTAMP | NOT NULL | |
| ends_at | TIMESTAMP | NOT NULL | |
| timezone | VARCHAR(50) | DEFAULT 'UTC' | |
| capacity | INT | NULL | NULL = unlimited |
| registration_deadline | TIMESTAMP | NULL | |
| is_free | BOOLEAN | DEFAULT TRUE | |
| price | DECIMAL(10,2) | NULL | |
| currency | CHAR(3) | DEFAULT 'USD' | |
| cover_image | VARCHAR(255) | NULL | |
| is_published | BOOLEAN | DEFAULT FALSE | |
| registrations_count | INT | DEFAULT 0 | |
| created_at | TIMESTAMP | | |
| updated_at | TIMESTAMP | | |
| deleted_at | TIMESTAMP | NULL | |

#### `event_registrations`
| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | BIGINT UNSIGNED | PK | |
| event_id | BIGINT UNSIGNED | FK → events.id | |
| user_id | BIGINT UNSIGNED | FK → users.id | |
| status | ENUM | DEFAULT 'registered' | registered, confirmed, cancelled, attended |
| ticket_code | VARCHAR(20) | UNIQUE | |
| registered_at | TIMESTAMP | | |
| checked_in_at | TIMESTAMP | NULL | |
| created_at | TIMESTAMP | | |
| updated_at | TIMESTAMP | | |

**Unique:** (event_id, user_id)

---

### 2.10 Skills & User Skills

#### `skills`
| Column | Type | Constraints |
|--------|------|-------------|
| id | BIGINT UNSIGNED | PK |
| name | VARCHAR(100) | UNIQUE |
| slug | VARCHAR(100) | UNIQUE |
| category | VARCHAR(50) | NULL |
| is_active | BOOLEAN | DEFAULT TRUE |

#### `user_skills`
| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | BIGINT UNSIGNED | PK | |
| user_id | BIGINT UNSIGNED | FK → users.id | |
| skill_id | BIGINT UNSIGNED | FK → skills.id | |
| proficiency | ENUM | DEFAULT 'intermediate' | beginner, intermediate, advanced, expert |
| years | TINYINT | NULL | |
| is_endorsed | BOOLEAN | DEFAULT FALSE | |
| created_at | TIMESTAMP | | |
| updated_at | TIMESTAMP | | |

**Unique:** (user_id, skill_id)

---

### 2.11 Mentorship

#### `mentor_availability`
| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | BIGINT UNSIGNED | PK | |
| mentor_profile_id | BIGINT UNSIGNED | FK → mentor_profiles.id | |
| day_of_week | TINYINT | NOT NULL | 0=Sunday, 6=Saturday |
| start_time | TIME | NOT NULL | |
| end_time | TIME | NOT NULL | |
| timezone | VARCHAR(50) | DEFAULT 'UTC' | |
| created_at | TIMESTAMP | | |
| updated_at | TIMESTAMP | | |

#### `mentor_sessions`
| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | BIGINT UNSIGNED | PK | |
| uuid | CHAR(36) | UNIQUE | |
| mentor_id | BIGINT UNSIGNED | FK → users.id | |
| mentee_id | BIGINT UNSIGNED | FK → users.id | |
| topic | VARCHAR(255) | NOT NULL | |
| description | TEXT | NULL | |
| scheduled_at | TIMESTAMP | NOT NULL | |
| duration_minutes | TINYINT | DEFAULT 60 | |
| meeting_url | VARCHAR(255) | NULL | |
| status | ENUM | DEFAULT 'pending' | pending, confirmed, completed, cancelled, no_show |
| notes | TEXT | NULL | Mentor notes |
| rating | TINYINT | NULL | 1-5 |
| review | TEXT | NULL | |
| created_at | TIMESTAMP | | |
| updated_at | TIMESTAMP | | |

---

### 2.12 Messaging

#### `conversations`
| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | BIGINT UNSIGNED | PK | |
| uuid | CHAR(36) | UNIQUE | |
| type | ENUM | DEFAULT 'direct' | direct, group |
| name | VARCHAR(255) | NULL | For group chats |
| created_at | TIMESTAMP | | |
| updated_at | TIMESTAMP | | |

#### `conversation_participants`
| Column | Type | Constraints |
|--------|------|-------------|
| id | BIGINT UNSIGNED | PK |
| conversation_id | BIGINT UNSIGNED | FK → conversations.id |
| user_id | BIGINT UNSIGNED | FK → users.id |
| last_read_at | TIMESTAMP | NULL |
| created_at | TIMESTAMP | |

**Unique:** (conversation_id, user_id)

#### `messages`
| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | BIGINT UNSIGNED | PK | |
| conversation_id | BIGINT UNSIGNED | FK → conversations.id | |
| sender_id | BIGINT UNSIGNED | FK → users.id | |
| body | TEXT | NOT NULL | |
| attachment | VARCHAR(255) | NULL | |
| is_edited | BOOLEAN | DEFAULT FALSE | |
| created_at | TIMESTAMP | | |
| updated_at | TIMESTAMP | | |
| deleted_at | TIMESTAMP | NULL | |

**Indexes:** `conversation_id`, `created_at`

---

### 2.13 Community

#### `posts`
| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | BIGINT UNSIGNED | PK | |
| uuid | CHAR(36) | UNIQUE | |
| user_id | BIGINT UNSIGNED | FK → users.id | |
| community_id | BIGINT UNSIGNED | FK → communities.id, NULL | |
| content | TEXT | NOT NULL | |
| media | JSON | NULL | Image/video paths |
| link_url | VARCHAR(255) | NULL | |
| link_preview | JSON | NULL | OG metadata |
| likes_count | INT | DEFAULT 0 | |
| comments_count | INT | DEFAULT 0 | |
| is_pinned | BOOLEAN | DEFAULT FALSE | |
| created_at | TIMESTAMP | | |
| updated_at | TIMESTAMP | | |
| deleted_at | TIMESTAMP | NULL | |

#### `communities`
| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | BIGINT UNSIGNED | PK | |
| uuid | CHAR(36) | UNIQUE | |
| name | VARCHAR(100) | NOT NULL | |
| slug | VARCHAR(100) | UNIQUE | |
| description | TEXT | NULL | |
| cover_image | VARCHAR(255) | NULL | |
| is_private | BOOLEAN | DEFAULT FALSE | |
| members_count | INT | DEFAULT 0 | |
| created_by | BIGINT UNSIGNED | FK → users.id | |
| created_at | TIMESTAMP | | |
| updated_at | TIMESTAMP | | |

#### `comments`
| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | BIGINT UNSIGNED | PK | |
| post_id | BIGINT UNSIGNED | FK → posts.id | |
| user_id | BIGINT UNSIGNED | FK → users.id | |
| parent_id | BIGINT UNSIGNED | FK → comments.id, NULL | For replies |
| content | TEXT | NOT NULL | |
| likes_count | INT | DEFAULT 0 | |
| created_at | TIMESTAMP | | |
| updated_at | TIMESTAMP | | |
| deleted_at | TIMESTAMP | NULL | |

#### `reactions`
| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | BIGINT UNSIGNED | PK | |
| user_id | BIGINT UNSIGNED | FK → users.id | |
| reactable_type | VARCHAR(50) | NOT NULL | Post, Comment |
| reactable_id | BIGINT UNSIGNED | NOT NULL | |
| type | ENUM | NOT NULL | like, celebrate, insightful, support |
| created_at | TIMESTAMP | | |

**Unique:** (user_id, reactable_type, reactable_id)

---

### 2.14 Bookmarks & Saved Items

#### `bookmarks`
| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | BIGINT UNSIGNED | PK | |
| user_id | BIGINT UNSIGNED | FK → users.id | |
| bookmarkable_type | VARCHAR(50) | NOT NULL | Job, Grant, Startup, Event |
| bookmarkable_id | BIGINT UNSIGNED | NOT NULL | |
| created_at | TIMESTAMP | | |

**Unique:** (user_id, bookmarkable_type, bookmarkable_id)

---

### 2.15 Notifications

#### `notifications`
Standard Laravel notifications table (UUID primary key).

---

### 2.16 Media Library

#### `media`
| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | BIGINT UNSIGNED | PK | |
| uuid | CHAR(36) | UNIQUE | |
| mediable_type | VARCHAR(50) | NOT NULL | Organization, User, Post |
| mediable_id | BIGINT UNSIGNED | NOT NULL | |
| collection | VARCHAR(50) | DEFAULT 'default' | logo, gallery, documents |
| file_name | VARCHAR(255) | NOT NULL | |
| file_path | VARCHAR(255) | NOT NULL | |
| mime_type | VARCHAR(100) | NOT NULL | |
| size | BIGINT UNSIGNED | NOT NULL | Bytes |
| alt_text | VARCHAR(255) | NULL | |
| order | TINYINT | DEFAULT 0 | |
| created_at | TIMESTAMP | | |
| updated_at | TIMESTAMP | | |

**Indexes:** (mediable_type, mediable_id), `collection`

---

### 2.17 Audit & Activity Logs

#### `activity_logs`
| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | BIGINT UNSIGNED | PK | |
| user_id | BIGINT UNSIGNED | FK → users.id, NULL | |
| action | VARCHAR(100) | NOT NULL | created, updated, deleted, login, etc. |
| subject_type | VARCHAR(50) | NULL | Model class |
| subject_id | BIGINT UNSIGNED | NULL | |
| properties | JSON | NULL | Old/new values |
| ip_address | VARCHAR(45) | NULL | |
| user_agent | TEXT | NULL | |
| created_at | TIMESTAMP | | |

**Indexes:** `user_id`, `action`, `subject_type`, `created_at`

---

### 2.18 Certificates

#### `certificates`
| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | BIGINT UNSIGNED | PK | |
| uuid | CHAR(36) | UNIQUE | |
| user_id | BIGINT UNSIGNED | FK → users.id | |
| issued_by | BIGINT UNSIGNED | FK → organizations.id, NULL | |
| course_id | BIGINT UNSIGNED | FK → courses.id, NULL | |
| title | VARCHAR(255) | NOT NULL | |
| description | TEXT | NULL | |
| file_path | VARCHAR(255) | NULL | |
| issued_at | DATE | NOT NULL | |
| expires_at | DATE | NULL | |
| verification_code | VARCHAR(20) | UNIQUE | |
| created_at | TIMESTAMP | | |
| updated_at | TIMESTAMP | | |

---

## 3. Index Strategy

### 3.1 Primary Indexes (Automatic)
All primary keys and foreign keys.

### 3.2 Search Indexes
```sql
-- Full-text search on startups
ALTER TABLE organizations ADD FULLTEXT INDEX ft_search (name, tagline, description);

-- Full-text search on jobs
ALTER TABLE jobs ADD FULLTEXT INDEX ft_search (title, description);

-- Full-text search on grants
ALTER TABLE grants ADD FULLTEXT INDEX ft_search (title, description);
```

### 3.3 Composite Indexes
```sql
-- Job listings filter
CREATE INDEX idx_jobs_listing ON jobs (is_published, job_type, location_type, created_at);

-- Event calendar
CREATE INDEX idx_events_calendar ON events (is_published, starts_at, country_id);

-- User activity feed
CREATE INDEX idx_posts_feed ON posts (created_at DESC, deleted_at);
```

---

## 4. Migration Order

1. Core: users, roles, permissions, role_permissions, user_roles
2. Location: countries, states, cities
3. Organizations: organizations, organization_members
4. Profiles: student_profiles, investor_profiles, mentor_profiles, freelancer_profiles
5. Startups: sectors, startups, startup_sectors, startup_milestones
6. Tech Hubs: tech_hubs, courses, course_enrollments
7. Skills: skills, user_skills
8. Jobs: jobs, job_skills, job_applications
9. Grants: grants
10. Events: events, event_registrations
11. Mentorship: mentor_availability, mentor_sessions
12. Messaging: conversations, conversation_participants, messages
13. Community: communities, posts, comments, reactions
14. Supporting: bookmarks, media, certificates, activity_logs, notifications

---

## 5. Seed Data Requirements

| Seeder | Data |
|--------|------|
| RoleSeeder | All 16 user roles |
| PermissionSeeder | All module permissions |
| RolePermissionSeeder | Role-permission mappings |
| CountrySeeder | All African countries + major global |
| StateSeeder | States for Nigeria, Kenya, South Africa, Egypt, Ghana |
| CitySeeder | Major cities per state |
| SectorSeeder | 20+ industry sectors |
| SkillSeeder | 100+ common skills |
| AdminUserSeeder | Super admin account |
