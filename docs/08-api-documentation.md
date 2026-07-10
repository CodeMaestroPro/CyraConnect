# 08 — API Documentation

**Project:** CyraConnect  
**Version:** 1.0.0  
**Base URL:** `/api/v1`  
**Authentication:** Bearer Token (Laravel Sanctum)

---

## 1. Conventions

### Request Headers
```
Accept: application/json
Content-Type: application/json
Authorization: Bearer {token}
```

### Success Response
```json
{
  "success": true,
  "data": { },
  "meta": { "pagination": { "current_page": 1, "per_page": 20, "total": 150 } }
}
```

### Error Response
```json
{
  "success": false,
  "error": {
    "code": "VALIDATION_ERROR",
    "message": "The given data was invalid.",
    "details": { "email": ["Required."] }
  }
}
```

### Status Codes
200 Success | 201 Created | 204 No Content | 401 Unauthenticated | 403 Forbidden | 422 Validation | 429 Rate Limited

---

## 2. Authentication Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/auth/register` | Register user |
| POST | `/auth/login` | Login, returns token |
| POST | `/auth/logout` | Revoke token |
| POST | `/auth/forgot-password` | Send reset link |
| POST | `/auth/reset-password` | Reset password |
| GET | `/auth/user` | Current user + roles |
| POST | `/auth/verify-email/{id}/{hash}` | Verify email |

---

## 3. Core Resource Endpoints

### Users & Profiles
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/users/{uuid}` | Public profile |
| PUT | `/profile` | Update personal profile |
| PUT | `/profile/{type}` | Update role profile (student/investor/mentor/freelancer) |
| GET/POST/DELETE | `/profile/skills` | Manage skills |

### Organizations
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/organizations` | List with filters |
| POST | `/organizations` | Create organization |
| GET | `/organizations/{slug}` | Detail by slug |
| PUT | `/organizations/{uuid}` | Update |
| DELETE | `/organizations/{uuid}` | Soft delete |
| GET/POST/DELETE | `/organizations/{uuid}/members` | Member management |

### Startups
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/startups` | List with filters |
| GET | `/startups/{slug}` | Detail |
| PUT | `/startups/{uuid}` | Update startup fields |
| POST | `/startups/{uuid}/pitch-deck` | Upload pitch deck |
| GET/POST | `/startups/{uuid}/milestones` | Milestones |

### Jobs
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/jobs` | List published jobs |
| POST | `/jobs` | Create job |
| GET | `/jobs/{slug}` | Job detail |
| PUT/DELETE | `/jobs/{uuid}` | Update/delete |
| POST | `/jobs/{uuid}/apply` | Submit application |
| GET/PUT | `/jobs/{uuid}/applications` | Manage applications |

### Grants
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/grants` | List with filters |
| GET | `/grants/{slug}` | Detail |
| POST/PUT | `/grants` | Admin CRUD |

### Events
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/events` | List events |
| POST | `/events` | Create event |
| GET | `/events/{slug}` | Detail |
| POST/DELETE | `/events/{uuid}/register` | Register/cancel |

### Courses & Hubs
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/hubs` | List tech hubs |
| GET | `/hubs/{slug}/courses` | Hub courses |
| POST | `/hubs/{uuid}/courses` | Create course |
| POST | `/courses/{uuid}/enroll` | Enroll |

### Mentorship
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/mentors` | List mentors |
| POST | `/mentors/{uuid}/sessions` | Book session |
| GET/PUT | `/sessions` | Manage sessions |
| POST | `/sessions/{uuid}/review` | Submit review |

### Messaging
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET/POST | `/conversations` | List/create |
| GET/POST | `/conversations/{uuid}/messages` | Messages |

### Community
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET/POST | `/posts` | Feed/create |
| POST | `/posts/{uuid}/comments` | Comment |
| POST | `/posts/{uuid}/react` | React |
| GET/POST | `/communities` | Groups |

### Other
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET/POST/DELETE | `/bookmarks` | Saved items |
| GET | `/map/entities` | GeoJSON map data |
| GET | `/search?q=` | Global search |
| GET | `/notifications` | User notifications |
| GET | `/countries`, `/sectors`, `/skills` | Reference data |

---

## 4. Admin Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/admin/users` | User management |
| PUT | `/admin/users/{uuid}/suspend` | Suspend user |
| GET | `/admin/verification-queue` | Pending verifications |
| PUT | `/admin/verify/{uuid}` | Approve/reject |
| GET | `/admin/audit-logs` | Audit trail |

---

## 5. Rate Limits

| Group | Limit |
|-------|-------|
| Auth | 5/min |
| General API | 60/min |
| Uploads | 10/min |
| Search | 30/min |
| Admin | 120/min |
