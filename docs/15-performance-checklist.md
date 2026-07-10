# 15 — Performance Checklist

**Project:** Cyra Nexus  
**Version:** 1.0.0  
**Date:** July 10, 2026

---

## 1. Performance Targets

| Metric | Target | Measurement |
|--------|--------|-------------|
| First Contentful Paint (FCP) | < 1.5s | Lighthouse |
| Largest Contentful Paint (LCP) | < 2.5s | Lighthouse |
| Time to Interactive (TTI) | < 3.5s | Lighthouse |
| Cumulative Layout Shift (CLS) | < 0.1 | Lighthouse |
| API Response (p95) | < 200ms | APM |
| Database Query (p95) | < 50ms | Slow query log |
| Search Results | < 500ms | Manual test |
| Lighthouse Performance Score | > 90 | Lighthouse |

---

## 2. Frontend Performance

| # | Optimization | Implementation |
|---|-------------|---------------|
| 1 | Minified CSS/JS | Vite production build |
| 2 | Code splitting | Vite dynamic imports for heavy modules (map, charts) |
| 3 | Lazy loading images | `loading="lazy"` on all images below fold |
| 4 | Responsive images | srcset with WebP format |
| 5 | Font optimization | Preload Inter, font-display: swap |
| 6 | Critical CSS | Inline above-fold styles |
| 7 | Defer non-critical JS | Async/defer script loading |
| 8 | Skeleton screens | Show layout while content loads |
| 9 | Pagination | Never load > 50 items at once |
| 10 | Infinite scroll | Alternative to pagination for feeds |

---

## 3. Backend Performance

| # | Optimization | Implementation |
|---|-------------|---------------|
| 11 | Route caching | `php artisan route:cache` in production |
| 12 | Config caching | `php artisan config:cache` in production |
| 13 | View caching | `php artisan view:cache` in production |
| 14 | Event caching | `php artisan event:cache` in production |
| 15 | OPcache | Enabled with optimized settings |
| 16 | Composer autoloader | `--optimize-autoloader` in production |
| 17 | Eager loading | `with()` on all list queries |
| 18 | Query optimization | Select only needed columns |
| 19 | Database indexes | All FK, search, and filter columns |
| 20 | Chunk processing | `chunk()` for bulk operations |

---

## 4. Caching Strategy

| # | Cache | Driver | TTL | Invalidation |
|---|-------|--------|-----|-------------|
| 21 | Config | File/Redis | Permanent | Deploy |
| 22 | Routes | File/Redis | Permanent | Deploy |
| 23 | User permissions | Redis | 1 hour | Role change |
| 24 | Startup listings | Redis | 5 min | CRUD event |
| 25 | Job listings | Redis | 5 min | CRUD event |
| 26 | Grant listings | Redis | 15 min | CRUD event |
| 27 | Analytics aggregates | Redis | 1 hour | Scheduled job |
| 28 | Reference data | Redis | 24 hours | Seeder run |
| 29 | Public profiles | HTTP/CDN | 1 hour | Profile update |
| 30 | Static assets | CDN | 1 year | Hash-based |

---

## 5. Database Optimization

| # | Optimization | Details |
|---|-------------|---------|
| 31 | Connection pooling | Persistent connections in production |
| 32 | Read replicas | Route read queries to replica |
| 33 | Query monitoring | Log queries > 100ms |
| 34 | N+1 prevention | Eager load relationships |
| 35 | Pagination | Always paginate list queries |
| 36 | Full-text search | MySQL FULLTEXT or Scout index |
| 37 | Soft delete index | Index on deleted_at column |
| 38 | Composite indexes | Multi-column for common filters |
| 39 | Archive old data | Move inactive records to archive tables |
| 40 | Regular ANALYZE | Weekly table statistics update |

---

## 6. Queue & Async Processing

| # | Task | Queue | Priority |
|---|------|-------|----------|
| 41 | Email sending | emails | Normal |
| 42 | Notification dispatch | notifications | Normal |
| 43 | Image processing | media | Low |
| 44 | Search indexing | search | Low |
| 45 | Analytics aggregation | analytics | Low |
| 46 | Report generation | default | Low |
| 47 | AI processing | ai | Low |

**Never block HTTP response for:**
- Email sending
- Image thumbnail generation
- Search index updates
- Analytics calculations
- Notification delivery

---

## 7. File & Media Optimization

| # | Optimization | Details |
|---|-------------|---------|
| 48 | Image compression | Intervention Image, quality 85% |
| 49 | Thumbnail generation | 150px, 300px, 600px variants |
| 50 | WebP conversion | Serve WebP with JPEG fallback |
| 51 | CDN delivery | All media via Cloudflare/S3 CDN |
| 52 | Lazy upload processing | Queue thumbnail generation |
| 53 | Max upload size | 20MB enforced server-side |

---

## 8. Network Optimization

| # | Optimization | Details |
|---|-------------|---------|
| 54 | Gzip/Brotli compression | Nginx compression enabled |
| 55 | HTTP/2 | Enabled on Nginx |
| 56 | CDN for static assets | Cloudflare edge caching |
| 57 | DNS prefetch | `<link rel="dns-prefetch">` for external domains |
| 58 | Preconnect | For API and CDN origins |
| 59 | Asset versioning | Vite hash-based filenames |

---

## 9. Mobile & Low-Bandwidth

| # | Optimization | Details |
|---|-------------|---------|
| 60 | Mobile-first CSS | Tailwind responsive utilities |
| 61 | Reduced payload | Smaller images on mobile |
| 62 | Offline-ready | Service worker for critical pages (PWA) |
| 63 | Progressive enhancement | Core content without JS |
| 64 | Touch optimization | 44px minimum touch targets |

---

## 10. Monitoring & Benchmarks

### Tools
- **Laravel Telescope** — Query monitoring (dev)
- **Laravel Debugbar** — Request profiling (dev)
- **Sentry Performance** — APM in production
- **k6 / Artillery** — Load testing
- **Lighthouse CI** — Automated performance audits

### Load Test Scenarios
| Scenario | Users | Duration | Target |
|----------|-------|----------|--------|
| Homepage browse | 1,000 | 5 min | p95 < 1.5s |
| Job search | 500 | 5 min | p95 < 2s |
| Login/register | 200 | 5 min | p95 < 500ms |
| API endpoints | 1,000 | 5 min | p95 < 200ms |
| Mixed workload | 10,000 | 30 min | p95 < 2s, 0 errors |

### Performance Review Schedule
- Weekly: Slow query review
- Monthly: Lighthouse audit on key pages
- Quarterly: Full load test
- Pre-launch: Comprehensive performance audit

---

## 11. Pre-Launch Performance Checklist

- [ ] Lighthouse score > 90 on homepage, job board, startup directory
- [ ] All list pages paginated
- [ ] No N+1 queries (Telescope verification)
- [ ] Redis caching configured and tested
- [ ] Queue workers running for all async tasks
- [ ] CDN configured for static assets and media
- [ ] Gzip/Brotli compression enabled
- [ ] Database indexes verified
- [ ] Load test passed (10K concurrent users)
- [ ] Mobile performance verified on 3G throttling
