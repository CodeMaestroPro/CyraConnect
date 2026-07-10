# 02 — System Architecture

**Project:** Cyra Nexus  
**Version:** 1.0.0  
**Date:** July 10, 2026

---

## 1. Architecture Overview

Cyra Nexus follows a **modular monolith** architecture with clear domain boundaries, designed for future microservices extraction if needed.

```
┌─────────────────────────────────────────────────────────────────────────┐
│                           CLIENT LAYER                                   │
│  Browser (Blade) │ Mobile (PWA/API) │ Third-Party Apps │ Admin Panel    │
└─────────────────────────────────┬───────────────────────────────────────┘
                                  ▼
┌─────────────────────────────────────────────────────────────────────────┐
│                         PRESENTATION LAYER                               │
│  Routes → Middleware → Controllers → Blade/API Resources                  │
└─────────────────────────────────┬───────────────────────────────────────┘
                                  ▼
┌─────────────────────────────────────────────────────────────────────────┐
│                         APPLICATION LAYER                                │
│  Actions │ Services │ Repositories │ DTOs │ Events │ Policies            │
└─────────────────────────────────┬───────────────────────────────────────┘
                                  ▼
┌─────────────────────────────────────────────────────────────────────────┐
│                           DOMAIN LAYER                                   │
│  Models │ Enums │ Value Objects │ Domain Events                          │
└─────────────────────────────────┬───────────────────────────────────────┘
                                  ▼
┌─────────────────────────────────────────────────────────────────────────┐
│                       INFRASTRUCTURE LAYER                               │
│  MySQL │ Redis │ S3 Storage │ Queue Workers │ Mail │ Search │ External   │
└─────────────────────────────────────────────────────────────────────────┘
```

---

## 2. Architectural Patterns

### 2.1 Request Flow

```
Request → Route → Middleware → Controller → FormRequest → Policy
    → Service/Action → Repository → Model → Database
    → Event → Listener (async) → Response
```

### 2.2 Repository Pattern

Abstracts data access from business logic. Each major entity has an interface and Eloquent implementation.

### 2.3 Action Classes

Single-responsibility command objects for complex operations (e.g., `CreateStartupAction`, `SubmitJobApplicationAction`).

### 2.4 DTO Pattern

Type-safe readonly data transfer objects between layers.

---

## 3. Domain Modules

```
app/Domains/
├── Auth/           # Authentication, roles, permissions
├── User/           # User profiles, settings
├── Organization/   # Startups, hubs, corporates
├── Opportunity/    # Jobs, grants, events, projects
├── Application/    # Applications, enrollments, bookings
├── Messaging/      # Direct messages, notifications
├── Community/      # Posts, comments, groups
├── Analytics/      # Metrics, reports, dashboards
├── Admin/          # Platform administration
└── AI/             # AI assistant features
```

Each domain contains: Actions, DTOs, Enums, Events, Listeners, Models, Observers, Policies, Repositories, Services.

---

## 4. Authentication Architecture

```
Register → Verify Email → Select Role → Complete Profile → Dashboard (by role)
```

**RBAC:** User → Role → Permissions  
**Org RBAC:** User → Organization Membership → Org Role → Org Permissions

Permission format: `{module}.{action}` (e.g., `startup.create`, `job.apply`)

---

## 5. Multi-Tenancy Model

Organizations operate as tenants. Users can belong to multiple organizations with different roles.

---

## 6. Event-Driven Architecture

| Event | Listeners |
|-------|-----------|
| UserRegistered | SendWelcomeEmail, CreateDefaultSettings |
| StartupCreated | IndexForSearch, NotifyFollowers |
| JobApplicationSubmitted | NotifyEmployer, LogActivity |
| MessageReceived | SendPushNotification, UpdateUnreadCount |

**Queues:** default, emails, notifications, search, analytics, media, ai

---

## 7. Caching Strategy

| Layer | Content | TTL |
|-------|---------|-----|
| L1 Application | Sessions, permissions, config | Session |
| L2 Query | Listings, aggregates | 5–60 min |
| L3 HTTP/CDN | Static assets, public pages | 1 hr – 1 yr |

---

## 8. File Storage

```
avatars/ │ logos/ │ documents/ │ media/ │ posts/ │ exports/
```

Pipeline: Upload → Validation → Storage → Thumbnail → CDN Sync

---

## 9. Search (Future-Ready)

Laravel Scout → Meilisearch/Algolia  
Indexed: Startups, Jobs, Grants, Events, Tech Hubs, Users (privacy-controlled)

---

## 10. Real-Time (Future)

Laravel Reverb + Redis Pub/Sub for messaging and notifications.

---

## 11. API Architecture

- Versioning: `/api/v1/`
- Sanctum token authentication
- Consistent JSON response/error format
- Rate limiting per endpoint

---

## 12. Deployment Architecture

```
Cloudflare (CDN/WAF) → Load Balancer → App Servers (auto-scale)
    → MySQL (primary + replica) │ Redis Cluster │ S3 Storage
    → Queue Workers (Horizon) │ Scheduler (Cron)
```

---

## 13. Technology Stack

| Layer | Technology |
|-------|------------|
| Backend | Laravel 12, PHP 8.2+ |
| Database | MySQL 8+ |
| Cache/Queue | Redis 7+ |
| Frontend | Blade, Tailwind CSS, Alpine.js, ES6+ |
| Build | Vite |
| Auth | Laravel Sanctum |
| Charts/Maps | Chart.js, Leaflet.js |
| Icons | Heroicons |
| Images | Intervention Image |
| Search | Laravel Scout |
| Storage | S3-compatible |
| Testing | PHPUnit/Pest |
| CI/CD | GitHub Actions |
| Monitoring | Telescope, Sentry |

---

## 14. Scalability Roadmap

| Phase | Users | Architecture |
|-------|-------|--------------|
| MVP | 0–10K | Single server |
| Growth | 10K–100K | LB, Redis, read replicas |
| Scale | 100K–1M | CDN, workers, search |
| Enterprise | 1M+ | Service extraction, sharding |

See also: [14-security-checklist.md](./14-security-checklist.md), [15-performance-checklist.md](./15-performance-checklist.md)
