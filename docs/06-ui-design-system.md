# 06 — UI Design System

**Project:** Cyra Nexus  
**Version:** 1.0.0  
**Date:** July 10, 2026

---

## 1. Design Philosophy

Premium enterprise aesthetic inspired by LinkedIn, Stripe, Linear, and Vercel. Clean, professional, accessible, with subtle animations and glassmorphism accents.

**Principles:** Clarity, Consistency, Hierarchy, Accessibility, Performance

---

## 2. Color System

### 2.1 Brand Colors

| Token | Light Mode | Dark Mode | Usage |
|-------|-----------|-----------|-------|
| `--color-primary` | `#2563EB` (Cyra Blue) | `#3B82F6` | CTAs, links, active states |
| `--color-primary-dark` | `#1D4ED8` | `#2563EB` | Hover states |
| `--color-secondary` | `#7C3AED` (Purple) | `#8B5CF6` | Accents, badges |
| `--color-accent` | `#10B981` (Emerald) | `#34D399` | Success, growth metrics |
| `--color-warning` | `#F59E0B` | `#FBBF24` | Warnings, pending |
| `--color-danger` | `#EF4444` | `#F87171` | Errors, destructive |
| `--color-info` | `#06B6D4` | `#22D3EE` | Informational |

### 2.2 Neutral Scale

| Token | Light | Dark | Usage |
|-------|-------|------|-------|
| `--color-bg` | `#FFFFFF` | `#0F172A` (Slate 900) | Page background |
| `--color-bg-secondary` | `#F8FAFC` | `#1E293B` (Slate 800) | Card backgrounds |
| `--color-bg-tertiary` | `#F1F5F9` | `#334155` (Slate 700) | Input backgrounds |
| `--color-border` | `#E2E8F0` | `#475569` (Slate 600) | Borders, dividers |
| `--color-text` | `#0F172A` | `#F8FAFC` | Primary text |
| `--color-text-secondary` | `#64748B` | `#94A3B8` | Secondary text |
| `--color-text-muted` | `#94A3B8` | `#64748B` | Placeholder, hints |

### 2.3 Tailwind Config

```javascript
// tailwind.config.js
colors: {
  cyra: {
    50: '#EFF6FF', 100: '#DBEAFE', 200: '#BFDBFE',
    300: '#93C5FD', 400: '#60A5FA', 500: '#3B82F6',
    600: '#2563EB', 700: '#1D4ED8', 800: '#1E40AF', 900: '#1E3A8A',
  },
  purple: { /* standard Tailwind purple */ },
  emerald: { /* standard Tailwind emerald */ },
}
```

---

## 3. Typography

### 3.1 Font Stack

```css
--font-sans: 'Inter', system-ui, -apple-system, sans-serif;
--font-display: 'Inter', system-ui, sans-serif;
--font-mono: 'JetBrains Mono', 'Fira Code', monospace;
```

### 3.2 Type Scale

| Name | Size | Weight | Line Height | Usage |
|------|------|--------|-------------|-------|
| `display-xl` | 48px / 3rem | 700 | 1.1 | Hero headings |
| `display-lg` | 36px / 2.25rem | 700 | 1.2 | Page titles |
| `heading-1` | 30px / 1.875rem | 600 | 1.3 | Section headings |
| `heading-2` | 24px / 1.5rem | 600 | 1.35 | Card titles |
| `heading-3` | 20px / 1.25rem | 600 | 1.4 | Subsections |
| `body-lg` | 18px / 1.125rem | 400 | 1.6 | Lead paragraphs |
| `body` | 16px / 1rem | 400 | 1.6 | Body text |
| `body-sm` | 14px / 0.875rem | 400 | 1.5 | Secondary text |
| `caption` | 12px / 0.75rem | 500 | 1.4 | Labels, badges |

---

## 4. Spacing System

Base unit: **4px**. Use Tailwind spacing scale.

| Token | Value | Usage |
|-------|-------|-------|
| `space-1` | 4px | Tight gaps |
| `space-2` | 8px | Icon gaps, inline spacing |
| `space-3` | 12px | Form field gaps |
| `space-4` | 16px | Card padding (mobile) |
| `space-6` | 24px | Card padding (desktop) |
| `space-8` | 32px | Section gaps |
| `space-12` | 48px | Large section gaps |
| `space-16` | 64px | Page section padding |

---

## 5. Border Radius

| Token | Value | Usage |
|-------|-------|-------|
| `rounded-sm` | 4px | Badges, tags |
| `rounded` | 6px | Buttons, inputs |
| `rounded-md` | 8px | Cards, dropdowns |
| `rounded-lg` | 12px | Modals, large cards |
| `rounded-xl` | 16px | Feature cards |
| `rounded-full` | 9999px | Avatars, pills |

---

## 6. Shadows

| Token | Value | Usage |
|-------|-------|-------|
| `shadow-sm` | subtle | Cards at rest |
| `shadow` | medium | Elevated cards |
| `shadow-md` | pronounced | Dropdowns, popovers |
| `shadow-lg` | strong | Modals |
| `shadow-glow` | `0 0 20px rgba(37,99,235,0.15)` | Primary CTAs |

---

## 7. Components

### 7.1 Buttons

```
Primary:   bg-cyra-600 text-white hover:bg-cyra-700 shadow-sm
Secondary: bg-white border border-slate-200 text-slate-700 hover:bg-slate-50
Ghost:     text-cyra-600 hover:bg-cyra-50
Danger:    bg-red-600 text-white hover:bg-red-700
Sizes:     sm (px-3 py-1.5 text-sm) | md (px-4 py-2) | lg (px-6 py-3 text-lg)
```

### 7.2 Form Inputs

```
Base: w-full rounded-md border border-slate-200 bg-white px-3 py-2
Focus: ring-2 ring-cyra-500 border-cyra-500
Error: border-red-500 ring-red-500
Disabled: bg-slate-100 cursor-not-allowed
```

### 7.3 Cards

```
Base: bg-white rounded-lg border border-slate-200 shadow-sm
Hover: shadow-md transition-shadow duration-200
Glass: bg-white/80 backdrop-blur-lg border border-white/20 (hero sections)
```

### 7.4 Badges

```
Verified: bg-emerald-100 text-emerald-700 rounded-full px-2.5 py-0.5 text-xs font-medium
Stage: bg-purple-100 text-purple-700
Status: bg-slate-100 text-slate-600
Featured: bg-cyra-100 text-cyra-700
```

### 7.5 Avatars

```
Sizes: xs (24px) | sm (32px) | md (40px) | lg (48px) | xl (64px) | 2xl (96px)
Fallback: Initials on gradient background
Online indicator: Green dot bottom-right
```

---

## 8. Dark Mode

Toggle via `class="dark"` on `<html>`. All components use CSS variables or Tailwind `dark:` variants.

```html
<div class="bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100">
```

Preference stored in localStorage + user settings.

---

## 9. Animations

| Name | Duration | Easing | Usage |
|------|----------|--------|-------|
| `fade-in` | 200ms | ease-out | Page transitions |
| `slide-up` | 300ms | ease-out | Modal entry |
| `scale-in` | 200ms | ease-out | Dropdown open |
| `pulse` | 2s infinite | ease-in-out | Loading skeleton |
| `spin` | 1s infinite | linear | Loading spinner |

Use `transition-all duration-200` for hover states. Respect `prefers-reduced-motion`.

---

## 10. Icons

**Library:** Heroicons (outline for navigation, solid for status indicators)

Common icons:
- Navigation: home, search, bell, user, cog
- Actions: plus, pencil, trash, share, bookmark
- Status: check-circle, x-circle, exclamation-triangle
- Entities: building-office, academic-cap, briefcase, map-pin

---

## 11. Responsive Breakpoints

| Breakpoint | Min Width | Target |
|------------|-----------|--------|
| `sm` | 640px | Large phones |
| `md` | 768px | Tablets |
| `lg` | 1024px | Laptops |
| `xl` | 1280px | Desktops |
| `2xl` | 1536px | Large monitors |

**Mobile-first:** Design for 320px minimum, enhance progressively.

---

## 12. Accessibility

- Color contrast ≥ 4.5:1 (WCAG AA)
- Focus rings on all interactive elements
- `aria-label` on icon-only buttons
- Skip-to-content link
- Semantic HTML (`nav`, `main`, `article`, `section`)
- Form labels associated with inputs
- Error messages linked via `aria-describedby`
- Keyboard navigation for modals, dropdowns, tabs

---

## 13. Loading States

- **Skeleton screens** for content areas (pulse animation)
- **Spinner** for button actions (inline, replaces text)
- **Progress bar** for multi-step wizards and file uploads
- **Empty states** with illustration + CTA

---

## 14. Toast Notifications

```
Position: top-right (desktop), top-center (mobile)
Types: success (emerald), error (red), warning (amber), info (cyan)
Auto-dismiss: 5 seconds
Action: optional undo/dismiss button
```
