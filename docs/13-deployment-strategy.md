# 13 — Deployment Strategy

**Project:** Cyra Nexus  
**Version:** 1.0.0  
**Date:** July 10, 2026

---

## 1. Environment Strategy

| Environment | Purpose | URL |
|-------------|---------|-----|
| Local | Development | localhost |
| Staging | QA, UAT | staging.cyranexus.com |
| Production | Live platform | cyranexus.com |

---

## 2. Infrastructure

### Production Stack
```
Cloudflare (CDN + WAF + SSL)
    ↓
Load Balancer (AWS ALB / DigitalOcean LB)
    ↓
App Servers × 2+ (Ubuntu 22.04, PHP 8.2, Nginx)
    ↓
├── MySQL 8 (Primary + Read Replica)
├── Redis 7 (Cache + Sessions + Queue)
├── S3 / DigitalOcean Spaces (File Storage)
└── SendGrid (Email)
```

### Server Requirements (Per App Server)
- 4 vCPU, 8GB RAM minimum
- 50GB SSD
- Ubuntu 22.04 LTS
- PHP 8.2+, Nginx, Supervisor

---

## 3. CI/CD Pipeline

```yaml
# GitHub Actions Workflow
Trigger: Push to main (production), develop (staging)

Steps:
1. Checkout code
2. Install PHP dependencies
3. Run tests (unit + feature)
4. Run linter (Pint)
5. Build frontend assets (npm run build)
6. Deploy to server (SSH / Forge / Envoyer)
7. Run migrations
8. Clear and warm caches
9. Notify team (Slack)
```

---

## 4. Deployment Process

### Pre-Deploy Checklist
- [ ] All tests passing on CI
- [ ] Code reviewed and approved
- [ ] Database migrations tested on staging
- [ ] Environment variables updated
- [ ] Backup taken

### Deploy Steps
```bash
# 1. Enable maintenance mode
php artisan down --secret="cyra-deploy-2026"

# 2. Pull latest code
git pull origin main

# 3. Install dependencies
composer install --no-dev --optimize-autoloader
npm ci && npm run build

# 4. Run migrations
php artisan migrate --force

# 5. Clear and rebuild caches
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# 6. Restart queue workers
php artisan queue:restart

# 7. Disable maintenance mode
php artisan up
```

### Zero-Downtime Deployment
Use Laravel Envoyer or Deployer with atomic releases:
```
/releases/
  ├── 20260710_120000/  ← current
  └── 20260710_140000/  ← new (symlink swap)
/shared/
  ├── .env
  ├── storage/
  └── database/
```

---

## 5. Environment Variables

### Production .env
```env
APP_NAME="Cyra Nexus"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://cyranexus.com

DB_CONNECTION=mysql
DB_HOST=db-primary.internal
DB_PORT=3306
DB_DATABASE=cyra_nexus
DB_USERNAME=cyra_app
DB_PASSWORD=<secure>

CACHE_STORE=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

REDIS_HOST=redis.internal
REDIS_PASSWORD=<secure>

FILESYSTEM_DISK=s3
AWS_BUCKET=cyra-nexus-prod

MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_FROM_ADDRESS=noreply@cyranexus.com

SANCTUM_STATEFUL_DOMAINS=cyranexus.com
```

---

## 6. Queue Workers

### Supervisor Configuration
```ini
[program:cyra-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/cyra-nexus/artisan queue:work redis --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=4
redirect_stderr=true
stdout_logfile=/var/log/cyra-worker.log
```

### Scheduled Tasks
```php
// routes/console.php
Schedule::command('grants:check-deadlines')->daily();
Schedule::command('sessions:send-reminders')->hourly();
Schedule::command('analytics:aggregate')->dailyAt('02:00');
Schedule::command('backup:run')->dailyAt('03:00');
```

---

## 7. Monitoring & Alerting

| Tool | Purpose |
|------|---------|
| Laravel Telescope | Dev debugging (disabled in prod) |
| Sentry | Error tracking and alerting |
| Uptime Robot | Uptime monitoring |
| Cloudflare Analytics | Traffic and security |
| MySQL Slow Query Log | Database performance |
| Redis INFO | Cache/queue health |

### Alert Thresholds
- Error rate > 1% → Slack alert
- Response time p95 > 2s → Slack alert
- Queue depth > 1000 → Slack alert
- Disk usage > 80% → Email alert
- Failed jobs > 10/hour → Slack alert

---

## 8. Backup Strategy

| Data | Frequency | Retention | Method |
|------|-----------|-----------|--------|
| Database | Daily | 30 days | mysqldump → S3 |
| File storage | Daily | 30 days | S3 versioning |
| Redis | Hourly | 7 days | RDB snapshots |
| Code | Every deploy | Unlimited | Git |

### Recovery
- **RTO:** 4 hours
- **RPO:** 1 hour
- Monthly recovery drill on staging

---

## 9. Scaling Plan

| Users | Action |
|-------|--------|
| 0–10K | Single server |
| 10K–50K | Add load balancer + 2nd app server |
| 50K–100K | Add read replica, Redis cluster |
| 100K–500K | CDN, dedicated queue workers, search index |
| 500K+ | Database sharding, microservices extraction |

---

## 10. SSL & DNS

- SSL via Cloudflare (Full Strict)
- DNS managed in Cloudflare
- A record: cyranexus.com → Load Balancer IP
- CNAME: www → cyranexus.com
- CNAME: staging → staging server

---

## 11. Rollback Procedure

```bash
# 1. Enable maintenance mode
php artisan down

# 2. Rollback code
git checkout <previous-tag>
composer install --no-dev

# 3. Rollback migrations (if needed)
php artisan migrate:rollback --step=1

# 4. Rebuild caches
php artisan config:cache && php artisan route:cache

# 5. Restart workers and go live
php artisan queue:restart && php artisan up
```

Maximum rollback time target: 15 minutes.
