# 12 — Testing Strategy

**Project:** CyraConnect  
**Version:** 1.0.0  
**Date:** July 10, 2026

---

## 1. Testing Philosophy

- Test behavior, not implementation
- Every P0 feature has feature tests
- Every service/action has unit tests
- Tests run on every PR via CI
- Target: 80%+ code coverage for business logic

---

## 2. Testing Pyramid

```
        ┌─────────┐
        │   E2E   │  5%  — Critical user journeys
        ├─────────┤
        │ Feature │  25% — HTTP/API endpoint tests
        ├─────────┤
        │  Unit   │  70% — Services, actions, models
        └─────────┘
```

---

## 3. Unit Tests

**Framework:** PHPUnit / Pest  
**Location:** `tests/Unit/`

### What to Test
- Action classes (business logic)
- Service classes
- DTO validation
- Model scopes and accessors
- Repository methods
- Helper functions
- Enum methods

### Example
```php
// tests/Unit/Domains/Startup/CreateStartupActionTest.php
it('creates a startup with valid data', function () {
    $user = User::factory()->create();
    $dto = new CreateStartupDTO(name: 'TechCo', ...);

    $startup = app(CreateStartupAction::class)->execute($dto, $user);

    expect($startup)->toBeInstanceOf(Startup::class)
        ->and($startup->name)->toBe('TechCo');
});
```

---

## 4. Feature Tests

**Location:** `tests/Feature/`

### What to Test
- HTTP endpoints (web + API)
- Authentication flows
- Authorization (policy enforcement)
- Form validation
- Database state after actions
- Email/notification dispatch
- File uploads

### Critical Flows to Test

| Flow | Test File |
|------|-----------|
| Registration → Verify → Role Select → Profile | `Auth/RegistrationFlowTest.php` |
| Login → Dashboard redirect by role | `Auth/LoginFlowTest.php` |
| Create startup → View public profile | `Startup/StartupCrudTest.php` |
| Post job → Student applies → Status update | `Job/JobApplicationFlowTest.php` |
| Book mentor session → Confirm → Review | `Mentorship/SessionFlowTest.php` |
| Admin suspend user → User can't login | `Admin/UserManagementTest.php` |
| API auth → CRUD operations | `Api/V1/AuthTest.php` |

### Example
```php
// tests/Feature/Auth/RegistrationFlowTest.php
public function test_user_can_register_and_verify_email(): void
{
    $response = $this->post('/register', [
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => 'john@test.com',
        'password' => 'Password123!',
        'password_confirmation' => 'Password123!',
    ]);

    $response->assertRedirect('/email/verify');
    $this->assertDatabaseHas('users', ['email' => 'john@test.com']);
}
```

---

## 5. Browser Tests (E2E)

**Framework:** Laravel Dusk (optional, for critical paths)  
**Location:** `tests/Browser/`

### Critical Paths
1. Landing page → Register → Complete onboarding
2. Startup founder creates profile and posts job
3. Student searches jobs and submits application
4. Admin logs in and manages users

---

## 6. API Tests

**Location:** `tests/Feature/Api/V1/`

### Coverage
- All API endpoints documented in Doc 08
- Authentication (token generation, revocation)
- Authorization (403 for unauthorized access)
- Validation (422 responses)
- Pagination
- Rate limiting (429 responses)

### Example
```php
public function test_api_returns_paginated_startups(): void
{
    Startup::factory()->count(25)->create();

    $response = $this->getJson('/api/v1/startups?per_page=10');

    $response->assertOk()
        ->assertJsonCount(10, 'data')
        ->assertJsonPath('meta.pagination.total', 25);
}
```

---

## 7. Database Tests

- Use `RefreshDatabase` trait for test isolation
- Factory definitions for all models
- Seed minimal reference data in test setup
- Test migration rollback works

---

## 8. Security Tests

| Test | Description |
|------|-------------|
| CSRF | Forms reject requests without token |
| XSS | User input escaped in output |
| SQL Injection | Parameterized queries only |
| Authorization | Users can't access others' data |
| Rate Limiting | Auth endpoints throttle after 5 attempts |
| File Upload | Reject executable files, validate MIME |
| Mass Assignment | Protected fields not fillable |

---

## 9. Performance Tests

**Tool:** Laravel Telescope (dev), k6/Artillery (load)

| Test | Target |
|------|--------|
| Homepage load | < 1.5s FCP |
| Job board with 1000 jobs | < 2s |
| Startup directory with filters | < 1.5s |
| API endpoint p95 | < 200ms |
| Database query count per page | < 20 queries |

---

## 10. Accessibility Tests

**Tools:** axe-core, Lighthouse, manual keyboard testing

| Check | Standard |
|-------|----------|
| Color contrast | WCAG 2.1 AA (4.5:1) |
| Keyboard navigation | All interactive elements reachable |
| Screen reader | Proper ARIA labels and roles |
| Focus indicators | Visible on all focusable elements |
| Form labels | All inputs have associated labels |

---

## 11. CI Pipeline

```yaml
# .github/workflows/tests.yml
on: [push, pull_request]
jobs:
  tests:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v4
      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with: { php-version: '8.2' }
      - run: composer install
      - run: cp .env.testing .env
      - run: php artisan key:generate
      - run: php artisan migrate
      - run: php artisan test --coverage-min=80
      - run: ./vendor/bin/pint --test
```

---

## 12. Test Data Management

- **Factories:** One factory per model with realistic fake data
- **Seeders:** Reference data only (roles, countries, sectors)
- **Fixtures:** JSON files for complex test scenarios
- **Cleanup:** `RefreshDatabase` ensures isolation

---

## 13. Coverage Requirements

| Layer | Minimum Coverage |
|-------|-----------------|
| Actions | 90% |
| Services | 85% |
| Controllers | 80% |
| Models | 70% |
| Overall | 80% |

Report generated via: `php artisan test --coverage-html=reports/`
