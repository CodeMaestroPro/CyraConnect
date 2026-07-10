# 05 — Wireframe Structure

**Project:** Cyra Nexus  
**Version:** 1.0.0  
**Date:** July 10, 2026

---

## 1. Global Layout

### 1.1 Public Layout (Unauthenticated)

```
┌─────────────────────────────────────────────────────────────┐
│  [Logo] Cyra Nexus    Explore  Grants  Jobs  Events  Map   │
│                                    [Login] [Register]       │
├─────────────────────────────────────────────────────────────┤
│                                                             │
│                      MAIN CONTENT                           │
│                                                             │
├─────────────────────────────────────────────────────────────┤
│  Footer: About │ Contact │ Privacy │ Terms │ Social Links  │
└─────────────────────────────────────────────────────────────┘
```

### 1.2 Authenticated Layout

```
┌─────────────────────────────────────────────────────────────┐
│  [Logo]  [Search Bar........................]  🔔  [Avatar]│
├──────────┬──────────────────────────────────────────────────┤
│          │                                                  │
│ SIDEBAR  │              MAIN CONTENT AREA                   │
│          │                                                  │
│ Dashboard│  ┌────────────────────────────────────────────┐  │
│ Profile  │  │  Page Header + Breadcrumbs                 │  │
│ [Module  │  ├────────────────────────────────────────────┤  │
│  Nav]    │  │                                            │  │
│          │  │  Content Cards / Tables / Forms            │  │
│ Messages │  │                                            │  │
│ Settings │  │                                            │  │
│          │  └────────────────────────────────────────────┘  │
│          │                                                  │
└──────────┴──────────────────────────────────────────────────┘
```

### 1.3 Mobile Layout

```
┌─────────────────────┐
│ [☰] Logo    🔔 [👤]│
├─────────────────────┤
│                     │
│   MAIN CONTENT      │
│                     │
│                     │
├─────────────────────┤
│ 🏠  🔍  ➕  💬  👤 │  ← Bottom nav
└─────────────────────┘
```

---

## 2. Page Hierarchy

### 2.1 Public Pages

```
/                           Landing page
/about                      About Cyra Nexus
/explore                    Explore ecosystem
/explore/startups           Startup directory
/explore/hubs               Tech hub directory
/explore/investors          Investor directory
/grants                     Grant center
/grants/{slug}              Grant detail
/jobs                       Job board
/jobs/{slug}                Job detail
/events                     Event calendar
/events/{slug}              Event detail
/map                        Innovation map
/news                       News & stories
/news/{slug}                Article detail
/login                      Login
/register                   Registration
/forgot-password            Password reset
```

### 2.2 Student Portal

```
/student/dashboard          Activity feed, stats, quick actions
/student/profile            Skill profile editor
/student/portfolio          Projects showcase
/student/certificates       Certificate management
/student/applications       Job/internship applications
/student/courses            Enrolled courses + progress
/student/opportunities      Saved opportunities
/student/mentors            Find & book mentors
/student/sessions           Mentor session history
```

### 2.3 Startup Portal

```
/startup/dashboard          Metrics, activity, quick actions
/startup/profile            Company profile editor
/startup/team                 Founder/team management
/startup/pitch-deck           Pitch deck upload
/startup/traction             Metrics dashboard
/startup/jobs                   Job postings management
/startup/jobs/create          Create job posting
/startup/applications         Applicant pipeline
/startup/milestones           Milestone timeline
/startup/media                Media gallery
/startup/analytics            Profile analytics
/startup/verification         Verification application
```

### 2.4 Tech Hub Portal

```
/hub/dashboard              Hub overview
/hub/profile                Organization profile
/hub/courses                Course catalog
/hub/courses/create         Create course
/hub/programs               Program management
/hub/students               Student roster
/hub/enrollments            Enrollment management
/hub/events                 Event management
/hub/mentors                Mentor roster
/hub/certificates           Certificate issuance
/hub/analytics              Hub analytics
```

### 2.5 Investor Portal

```
/investor/dashboard         Deal flow overview
/investor/profile           Investor profile
/investor/discover          Startup discovery
/investor/watchlist           Saved startups
/investor/deal-flow           Pipeline Kanban
/investor/requests            Investment requests
/investor/portfolio           Portfolio companies
/investor/analytics           Investment analytics
```

### 2.6 Admin Portal

```
/admin/dashboard            Platform overview
/admin/users                User management
/admin/organizations        Organization management
/admin/verification         Verification queue
/admin/content              Content moderation
/admin/grants               Grant management
/admin/reports              Platform reports
/admin/settings             System settings
/admin/audit-logs           Audit log viewer
/admin/roles                Role & permission management
```

---

## 3. Key Page Wireframes

### 3.1 Landing Page

```
┌─────────────────────────────────────────────────────────────┐
│  HERO SECTION                                               │
│  "Africa's Innovation Operating System"                     │
│  [Get Started] [Explore Ecosystem]                          │
│  Background: African map visualization / gradient           │
├─────────────────────────────────────────────────────────────┤
│  STATS BAR: 10K+ Startups │ 500+ Hubs │ $2B+ Funding        │
├─────────────────────────────────────────────────────────────┤
│  FEATURED STARTUPS (carousel)                               │
│  [Card] [Card] [Card] [Card]                                │
├─────────────────────────────────────────────────────────────┤
│  EXPLORE BY ROLE (6 cards)                                  │
│  Student │ Startup │ Investor │ Hub │ Corporate │ Freelance │
├─────────────────────────────────────────────────────────────┤
│  UPCOMING EVENTS (list)          │  LATEST GRANTS (list)   │
├─────────────────────────────────────────────────────────────┤
│  INNOVATION MAP PREVIEW (interactive mini-map)              │
├─────────────────────────────────────────────────────────────┤
│  TESTIMONIALS + CTA: "Join Africa's Innovation Revolution"  │
└─────────────────────────────────────────────────────────────┘
```

### 3.2 Dashboard (Generic)

```
┌─────────────────────────────────────────────────────────────┐
│  Welcome back, {Name}                    [Quick Action +] │
├──────────────┬──────────────┬──────────────┬───────────────┤
│  Stat Card   │  Stat Card   │  Stat Card   │  Stat Card    │
│  (metric)    │  (metric)    │  (metric)    │  (metric)     │
├──────────────────────────────┬──────────────────────────────┤
│  ACTIVITY FEED               │  QUICK ACTIONS               │
│  • Notification 1            │  [Action Button]             │
│  • Notification 2            │  [Action Button]             │
│  • Activity item             │  [Action Button]             │
│                              │                              │
│  RECENT ITEMS (table/cards)  │  UPCOMING (calendar widget)  │
└──────────────────────────────┴──────────────────────────────┘
```

### 3.3 Startup Profile (Public)

```
┌─────────────────────────────────────────────────────────────┐
│  COVER IMAGE                                                │
│  ┌──────┐  Startup Name ✓ Verified                         │
│  │ LOGO │  Tagline here                                    │
│  └──────┘  📍 Lagos, Nigeria │ 🏢 Seed Stage │ 👥 11-50    │
│            [Follow] [Connect] [Share]                       │
├─────────────────────────────────────────────────────────────┤
│  About │ Team │ Products │ Jobs │ Milestones │ Media        │
├─────────────────────────────────────────────────────────────┤
│  DESCRIPTION                                                │
│  Full company description text...                           │
│                                                             │
│  SECTORS: [Fintech] [Healthtech]                            │
│                                                             │
│  TRACTION METRICS (if public)                               │
│  ┌─────────┐ ┌─────────┐ ┌─────────┐                      │
│  │ $500K   │ │ 10K     │ │ 50      │                      │
│  │ Revenue │ │ Users   │ │ Team    │                      │
│  └─────────┘ └─────────┘ └─────────┘                      │
└─────────────────────────────────────────────────────────────┘
```

### 3.4 Job Board

```
┌─────────────────────────────────────────────────────────────┐
│  Find Your Next Opportunity                                 │
│  [Search jobs...........................] [Search]          │
├──────────────────┬──────────────────────────────────────────┤
│  FILTERS         │  RESULTS (24 jobs)                     │
│                  │                                          │
│  Job Type        │  ┌────────────────────────────────────┐│
│  □ Full-time     │  │ Senior Developer @ StartupX        ││
│  □ Remote        │  │ Remote │ Full-time │ $60-80K       ││
│  □ Internship    │  │ Posted 2 days ago    [Apply] [♡]  ││
│                  │  └────────────────────────────────────┘│
│  Location        │  ┌────────────────────────────────────┐│
│  □ Remote        │  │ Product Manager @ CorpY            ││
│  □ Lagos         │  │ Hybrid │ Full-time │ Competitive   ││
│  □ Nairobi       │  │ Posted 1 week ago    [Apply] [♡]  ││
│                  │  └────────────────────────────────────┘│
│  Experience      │                                          │
│  □ Entry         │  [Load More]                             │
│  □ Mid           │                                          │
│  □ Senior        │                                          │
└──────────────────┴──────────────────────────────────────────┘
```

### 3.5 Innovation Map

```
┌─────────────────────────────────────────────────────────────┐
│  Innovation Map of Africa          [Filters ▼] [Search]     │
├──────────────────┬──────────────────────────────────────────┤
│  FILTER PANEL    │                                          │
│                  │         INTERACTIVE MAP                  │
│  Entity Type     │         (Leaflet.js)                     │
│  ☑ Tech Hubs     │                                          │
│  ☑ Startups      │         📍 📍                            │
│  ☑ Investors     │              📍    📍                    │
│  □ Universities  │     📍                   📍               │
│  □ Events        │                                          │
│                  │              📍  📍                       │
│  Sector          │                                          │
│  [All sectors ▼] │                                          │
│                  │                                          │
│  Country         │                                          │
│  [All countries▼]│                                          │
├──────────────────┴──────────────────────────────────────────┤
│  SELECTED: StartupX — Lagos, Nigeria — [View Profile →]     │
└─────────────────────────────────────────────────────────────┘
```

---

## 4. Component Library (Wireframe Level)

### 4.1 Reusable Components

| Component | Usage |
|-----------|-------|
| `<x-card>` | Content containers |
| `<x-stat-card>` | Dashboard metrics |
| `<x-data-table>` | List views with pagination |
| `<x-form-section>` | Grouped form fields |
| `<x-modal>` | Dialogs and confirmations |
| `<x-badge>` | Status, verification, tags |
| `<x-avatar>` | User/org avatars |
| `<x-empty-state>` | No data placeholders |
| `<x-pagination>` | Page navigation |
| `<x-breadcrumbs>` | Navigation trail |
| `<x-tabs>` | Tabbed content |
| `<x-dropdown>` | Action menus |
| `<x-alert>` | Success/error/warning messages |
| `<x-skeleton>` | Loading placeholders |

### 4.2 Form Patterns

- Single-column forms on mobile, two-column on desktop
- Inline validation with error messages
- Progress indicators for multi-step wizards
- Auto-save drafts for long forms

---

## 5. Navigation Structure

### 5.1 Primary Navigation (Role-Based)

Navigation items change based on user's primary role:

**Student:** Dashboard, Profile, Jobs, Courses, Mentors, Messages  
**Startup:** Dashboard, Profile, Jobs, Analytics, Team, Messages  
**Investor:** Dashboard, Discover, Deal Flow, Portfolio, Messages  
**Tech Hub:** Dashboard, Courses, Students, Events, Analytics  
**Admin:** Dashboard, Users, Organizations, Content, Settings

### 5.2 Secondary Navigation

Accessible from user menu (avatar dropdown):
- Profile Settings
- Account Settings
- Notifications
- Help & Support
- Switch Role (if multiple)
- Logout
