# 09 — Folder Structure

**Project:** CyraConnect  
**Version:** 1.0.0  
**Date:** July 10, 2026

---

## Project Structure

```
cyra-connect/
├── app/
│   ├── Domains/                    # Domain-driven modules
│   │   ├── Auth/                   # Actions, DTOs, Models, Repositories, Services
│   │   ├── User/                   # Profile types, skills
│   │   ├── Organization/           # Orgs, members, locations
│   │   ├── Startup/                # Startup-specific logic
│   │   ├── Opportunity/            # Jobs, grants, events
│   │   ├── Application/            # Applications, enrollments
│   │   ├── Messaging/              # Conversations, messages
│   │   ├── Community/              # Posts, comments, groups
│   │   ├── Analytics/              # Metrics, reports
│   │   ├── Admin/                  # Admin operations
│   │   └── AI/                     # AI services (future)
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/
│   │   │   ├── Web/                # Blade controllers
│   │   │   ├── Api/V1/             # REST API
│   │   │   └── Admin/
│   │   ├── Middleware/             # CheckRole, CheckPermission, LogActivity
│   │   ├── Requests/               # Form validation
│   │   └── Resources/              # API transformers
│   ├── Models/User.php             # Core Eloquent models
│   └── Providers/                  # Service providers
├── config/
│   ├── cyra.php                    # Platform config
│   └── permissions.php             # Permission definitions
├── database/
│   ├── factories/
│   ├── migrations/
│   └── seeders/
├── docs/                           # Documentation (this folder)
├── resources/
│   ├── css/app.css                 # Tailwind
│   ├── js/
│   │   ├── app.js
│   │   └── components/             # map.js, charts.js, dark-mode.js
│   └── views/
│       ├── components/
│       │   ├── layouts/            # app, guest, admin, portal
│       │   ├── ui/                 # button, card, input, modal, etc.
│       │   └── shared/             # navbar, sidebar, footer
│       ├── auth/
│       ├── onboarding/
│       ├── student/ startup/ hub/ investor/
│       ├── jobs/ grants/ events/ map/
│       ├── admin/
│       └── emails/
├── routes/
│   ├── web.php
│   ├── api.php
│   └── admin.php
└── tests/
    ├── Feature/
    └── Unit/
```

---

## Naming Conventions

| Element | Convention | Example |
|---------|-----------|---------|
| Models | Singular PascalCase | `Startup` |
| Controllers | PascalCase + Controller | `JobController` |
| Tables | snake_case plural | `job_applications` |
| Routes | kebab-case | `/startup/pitch-deck` |
| Blade components | kebab-case | `<x-ui.button>` |
| Actions | PascalCase + Action | `CreateStartupAction` |
| DTOs | PascalCase + DTO | `CreateStartupDTO` |
| Policies | PascalCase + Policy | `StartupPolicy` |

---

## Route Organization

```php
// Public: /, /explore, /jobs, /grants, /events, /map
// Auth: /login, /register, /forgot-password
// Authenticated: /dashboard, /student/*, /startup/*, /hub/*, /investor/*
// Admin: /admin/* (role: admin, super_admin)
// API: /api/v1/*
```

---

## Domain Module Structure

Each domain follows:
```
Domain/
├── Actions/          # Single-responsibility commands
├── DTOs/             # Data transfer objects
├── Enums/            # Type-safe constants
├── Events/           # Domain events
├── Listeners/        # Event handlers
├── Models/           # Eloquent models
├── Observers/        # Model lifecycle hooks
├── Policies/         # Authorization
├── Repositories/     # Data access abstraction
└── Services/         # Business logic
```
