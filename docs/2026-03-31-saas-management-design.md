**Date:** 2026-03-31
**Last Updated:** 2026-05-05
**Status:** In Progress
**Topic:** SaaS Management — Billing, Plans & Admin
**Project:** sfSaas

---

## 1. Overview

sfSaas is a free and open-source, batteries-included multitenant SaaS starter kit built on Laravel 13, Livewire 4, Tailwind CSS, Alpine.js, and Flux UI, with multi-database tenancy powered by Tenancy for Laravel (stancl/tenancy). The goal is to reduce the time required to launch a modern Laravel SaaS by solving the difficult parts up front: central user identity, subdomain-based tenant identification, per-tenant databases, plan-aware billing foundations, and admin control surfaces.

---

## 2. Architecture Decisions

| Decision | Chosen Approach | Status |
|---|---|---|
| Frontend stack | Laravel 13 + Livewire 4 + Tailwind + Alpine.js + Flux UI | ✅ Implemented |
| Tenancy model | Multi-database (stancl/tenancy) | ✅ Implemented |
| Tenant identification | Subdomain via `InitializeTenancyBySubdomain` | ✅ Implemented |
| Identity source of truth | Central `users` table | ✅ Implemented |
| Admin role | `is_admin` boolean flag on `users` | ✅ Implemented |
| Auth backend | Laravel Fortify (login, register, 2FA, password reset, email verification) | ✅ Implemented |
| Billing owner | Tenant (not individual user) | 🔲 Planned |
| Billing engine | Provider-agnostic abstraction (Stripe-first) | 🔲 Planned |
| API layer | Central + Tenant REST API | 🔲 Planned |

---

## 3. Domain Model

### 3.1 Central Database — Current State

These tables exist and are migrated:

| Entity | Purpose | Status |
|---|---|---|
| `users` | Global identity: name, email, password, is_admin, 2FA columns, tenant_id | ✅ Migrated |
| `tenants` | Tenant registry (stancl/tenancy default — id, tenancy_db_name, data, etc.) | ✅ Migrated |
| `domains` | Maps tenants to subdomain identifiers for routing | ✅ Migrated |
| `plans` | Commercial plan blueprints: name, slug, description, is_active | ✅ Migrated |
| `prices` | Per-plan billing amounts & intervals: plan_id, amount, currency, billing_interval | ✅ Migrated |
| `features` | Feature catalog: name, slug, type (`boolean`\|`limit`) | ✅ Migrated |
| `plan_feature` | Pivot: plan ↔ feature entitlements with `limit_value` | ✅ Migrated |
| `subscriptions` | Tenant subscription state: tenant_id, price_id, status, trial_ends_at, ends_at | ✅ Migrated |

### 3.2 Central Database — Planned (Not Yet Migrated)

| Entity | Purpose |
|---|---|
| `tenant_user` | Many-to-many membership (currently users have a direct `tenant_id` FK) |
| `tenant_invitations` | Email-based membership invitations |
| `plan_versions` | Versioned commercial definitions (forward-only mutations) |
| `subscription_items` | Per-price/per-feature subscription components |
| `subscription_phases` | Trial, paid, grace, paused phase records |
| `subscription_changes` | Audit log of upgrades/downgrades/renewals |
| `subscription_discounts` | Coupons and discounts |
| `invoices` / `invoice_line_items` | Immutable billing records |
| `credit_notes` | Credit adjustments |
| `payments` | Provider payment transactions |
| `billing_webhook_events` | Raw provider events for idempotent processing |

### 3.3 Tenant Database

Tenant migrations live in `database/migrations/tenant/`. Current state is the stancl/tenancy default structure (users, sessions, etc.). Additional tables (roles, permissions, entitlements, usage counters) are planned.

---

## 4. Eloquent Models — Current State

### `Plan` (`app/Models/Plan.php`)
```php
protected $fillable = ['name', 'slug', 'description', 'is_active'];
protected $casts = ['is_active' => 'boolean'];

public function prices(): HasMany       // → Price
public function features(): BelongsToMany  // → Feature via plan_feature (with limit_value pivot)
```

### `Price` (`app/Models/Price.php`)
```php
protected $fillable = ['plan_id', 'amount', 'currency', 'billing_interval'];

public function plan(): BelongsTo  // → Plan
```

### `Feature` (`app/Models/Feature.php`)
```php
protected $fillable = ['name', 'slug', 'type'];  // type: 'boolean' | 'limit'
```

### `Subscription` (`app/Models/Subscription.php`)
```php
protected $fillable = ['tenant_id', 'price_id', 'status', 'trial_ends_at', 'ends_at'];

public function price(): BelongsTo   // → Price
public function scopeActive($query)  // filters status = 'active'
```

### `Tenant` (`app/Models/Tenant.php`)
```php
// Extends stancl/tenancy BaseTenant, implements TenantWithDatabase
use HasDatabase, HasDomains;

public function users(): HasMany  // → User
```

### `User` (`app/Models/User.php`)
```php
// Attributes: name, email, password, is_admin
// Uses: HasFactory, Notifiable, TwoFactorAuthenticatable (Fortify)
// Casts: email_verified_at → datetime, is_admin → boolean, tenant_id → string

public function tenant(): BelongsTo  // → Tenant
public function initials(): string   // helper: first letters of name words
```

---

## 5. Factories

All core billing models have factories with sensible defaults:

| Factory | States / Notes |
|---|---|
| `PlanFactory` | name, slug, description, is_active |
| `PriceFactory` | plan_id, amount, currency, billing_interval |
| `FeatureFactory` | name, slug, type |
| `SubscriptionFactory` | tenant_id, price_id, status |
| `UserFactory` | name, email, password; supports `is_admin` override |

---

## 6. Routing & Middleware

All central routes are bound to configured `central_domains` (from `config/tenancy.php`). Route structure in `routes/web.php`:

```
/                       → marketing.home              (guest)
/features               → marketing.features          (guest)
/pricing                → marketing.pricing           (guest)
/about                  → marketing.about             (guest)
/sign-up                → pages::account.sign-up      (Livewire Volt, guest)

/dashboard              → dashboard                   (auth + verified)
/users                  → users.index                 (auth + verified, Livewire)

/admin/users            → admin.users.index           (auth + verified + admin)
/admin/tenants          → admin.tenants.index         (auth + verified + admin)
/admin/features         → admin.features.index        (auth + verified + admin)
/admin/plans            → admin.plans.index           (auth + verified + admin)
```

Auth/settings routes live in `routes/settings.php` (Fortify-backed).

**Middleware:**
- `admin` → `App\Http\Middleware\IsAdmin` (checks `is_admin` flag, returns 403 if false)
- Fortify middleware handles `auth`, `verified`, `password.confirm`, etc.

---

## 7. Livewire Components — Current State

### Class-based Components (`app/Livewire/`)

| Component | Location | Type | Description |
|---|---|---|---|
| `Admin\Features\Index` | `Admin/Features/Index.php` | Class-based | Full CRUD for features: create, edit, delete, sort, paginate |
| `Admin\Plans\Index` | `Admin/Plans/Index.php` | Class-based | List plans with price/feature counts, sort, toggle active |

> **Note:** `Admin\Plans\Index` renders `livewire.admin.plans.index` — this view file is **missing**. The plans page currently raises a `View not found` error. The view logic exists as a Volt SFC at `resources/views/components/admin/plans/⚡index.blade.php` but the class-based component renders it via the `livewire.` namespace. This conflict needs to be resolved (see §10 Known Issues).

### Volt SFC Components (`resources/views/components/`)

| Component | Path | Notes |
|---|---|---|
| `admin.users.index` | `components/admin/users/⚡index.blade.php` | SFC with inline class: user table, sortable columns, pagination |
| `admin.tenants.index` | `components/admin/tenants/⚡index.blade.php` | SFC |
| `admin.plans.index` | `components/admin/plans/⚡index.blade.php` | SFC (conflicts with class-based Plans\Index — see §10) |
| `users.index` | `components/users/⚡index.blade.php` | SFC for the non-admin user list page |
| `pricing.list` | `components/pricing/⚡list.blade.php` | Marketing pricing display component |

---

## 8. Views & Layouts

### Layouts

| Layout | Path | Description |
|---|---|---|
| `layouts.app` | `resources/views/layouts/app.blade.php` | Main app shell (sidebar + content) |
| `layouts.auth` | `resources/views/layouts/auth.blade.php` | Centered auth layout |

The `layouts/app/` directory contains partials: `sidebar.blade.php`, etc.

### Marketing Pages

All static marketing pages live in `resources/views/marketing/`:
- `home.blade.php` — landing page
- `features.blade.php` — feature highlights
- `pricing.blade.php` — pricing table (uses `<livewire:pricing.list>`)
- `about.blade.php` — about page

### Dashboard

`resources/views/dashboard.blade.php` — authenticated user dashboard (central domain).

---

## 9. Authentication — Current State

Authentication is handled by **Laravel Fortify** with the following capabilities implemented:

| Feature | Status |
|---|---|
| Login | ✅ |
| Registration | ✅ |
| Password Reset | ✅ |
| Email Verification | ✅ |
| Two-Factor Authentication (TOTP + recovery codes) | ✅ |
| Password Confirmation | ✅ |

Auth views live in `resources/views/livewire/auth/` and `resources/views/livewire/settings/`.

The sign-up flow (`/sign-up`) is a Livewire Volt page at `resources/views/pages/account/` and handles tenant creation after registration.

---

## 10. Known Issues & Open Work

### 🔴 Blocker: Plans Index View Not Found

- `App\Livewire\Admin\Plans\Index::render()` calls `view('livewire.admin.plans.index')`.
- The file `resources/views/livewire/admin/plans/index.blade.php` does not exist.
- A Volt SFC exists at `resources/views/components/admin/plans/⚡index.blade.php` but it cannot be used by the class-based component directly.
- **Resolution options:**
  1. Create the missing Blade view at `resources/views/livewire/admin/plans/index.blade.php` and extract the HTML from the Volt SFC into it.
  2. Delete `app/Livewire/Admin/Plans/Index.php` and rely entirely on the Volt SFC (which is resolved by `Route::livewire('admin/plans', 'admin.plans.index')`).

### 🟡 Inconsistency: Mixed Component Patterns

- Features uses a class-based component (`Admin\Features\Index`) with a proper view in `resources/views/components/admin/features/⚡index.blade.php`.
- Plans has both a class-based component (incomplete) and a Volt SFC.
- Users/Tenants admin pages use Volt SFCs only.
- The project should settle on one pattern per section.

### 🟡 Membership Model Simplification

- Currently `users` has a direct `tenant_id` FK.
- The original design calls for a `tenant_user` pivot to support many-to-many membership (one user, multiple tenants).
- Tenant switching UI and invitation flows are not yet implemented.

### 🔲 Billing Engine Not Yet Implemented

- `Subscription` model exists with basic fields but no payment provider integration.
- No Stripe/Paddle service, webhook handling, or entitlement derivation.
- MRR/ARR metrics, churn calculation, and invoice records are all planned.

---

## 11. Testing — Current State

### Feature Tests (`tests/Feature/`)

| Test File | Coverage |
|---|---|
| `Auth/AuthenticationTest.php` | Login, logout flows |
| `Auth/RegistrationTest.php` | User registration |
| `Auth/PasswordResetTest.php` | Password reset flow |
| `Auth/EmailVerificationTest.php` | Email verification |
| `Auth/TwoFactorChallengeTest.php` | 2FA challenge flow |
| `Auth/PasswordConfirmationTest.php` | Password confirmation |
| `Admin/FeatureIndexTest.php` | Full CRUD for features (admin gate, create, edit, delete, validation, unique slug) |
| `Admin/PlanTest.php` | Plans index access, listing with counts, toggle active, sorting |
| `Central/Admin/UserManagementTest.php` | Admin user management |
| `MarketingPagesTest.php` | All marketing pages return 200 |
| `DashboardTest.php` | Dashboard access (auth required) |
| `HomePageTest.php` | Home page accessible |
| `TenantIdentificationTest.php` | Subdomain-based tenant resolution |
| `PlanCrudTest.php` | Plan CRUD via HTTP |

### Unit Tests (`tests/Unit/`)

| Test File | Coverage |
|---|---|
| `Models/PlanTest.php` | fillable, casts, relationships (prices, features) |
| `Models/PriceTest.php` | fillable, plan relationship |
| `Models/FeatureTest.php` | fillable |

---

## 12. Implementation Phases — Updated Status

### Phase 1: Database & Models ✅ Complete
- Migrations for `plans`, `prices`, `features`, `plan_feature`, `subscriptions`
- Eloquent models with relationships, casts, and `scopeActive`
- Factories for all billing models

### Phase 2: Core Admin UI 🔄 In Progress
- Admin console routes protected by `IsAdmin` middleware
- Class-based Livewire component for **Features** (full CRUD, sort, paginate) ✅
- Class-based Livewire component for **Plans** (list, toggle active, sort) ✅
- Plans admin view missing — renders error 🔴
- Admin users list (Volt SFC) ✅
- Admin tenants list (Volt SFC) ✅

### Phase 3: Marketing & Auth ✅ Complete
- Landing page, features, pricing, about pages
- Full Fortify auth: login, register, 2FA, password reset, email verification
- Sign-up with tenant onboarding flow

### Phase 4: Billing Engine 🔲 Not Started
- Payment provider integration (Stripe-first)
- Subscription lifecycle: trials, upgrades, downgrades, cancellations
- Proration calculation and invoice generation
- Webhook handling (idempotent)
- Entitlement derivation from active subscription

### Phase 5: Tenant Application 🔲 Not Started
- Tenant-scoped dashboard and settings
- Tenant-local roles & permissions
- Usage counters and feature limit enforcement
- Tenant-local notifications

### Phase 6: Notifications & Metrics 🔲 Not Started
- MRR / ARR calculation from active subscriptions
- Churn tracking
- Budget alerts via Laravel Notifications
- Admin dashboard charts (Chart.js)

---

## 13. MRR / ARR Calculation Design (Planned)

```php
public function getMetrics(): array
{
    $activeSubscriptions = Subscription::active()->with('price')->get();

    $totalMrr = $activeSubscriptions->sum(function ($subscription) {
        $price = $subscription->price;
        return $price->billing_interval === 'year'
            ? $price->amount / 12
            : $price->amount;
    });

    return [
        'mrr'            => $totalMrr,
        'arr'            => $totalMrr * 12,
        'customer_count' => $activeSubscriptions->count(),
    ];
}
```

---

## 14. Feature Limit Enforcement Design (Planned)

```php
// On Tenant model (or via a dedicated EntitlementService)
public function hasReachedLimit(string $featureSlug): bool
{
    $subscription = $this->subscription()->with('price.plan.features')->first();

    $feature = $subscription?->price?->plan?->features
        ->firstWhere('slug', $featureSlug);

    if (! $feature) {
        return true; // Feature not in plan — deny
    }

    $limit = $feature->pivot->limit_value;

    return $this->orders()->count() >= $limit;
}
```