**Date:** 2026-03-31
**Status:** Draft
**Topic:** SAAS Feature

## 1. Overview
sfSaas is a complete SaaS starter kit that includes everything you need to start your SaaS business. It comes ready with a huge list of reusable components, a complete admin panel, user dashboard, user authentication, user & role management, plans & pricing, subscriptions, payments, emails, and more.


Designing a robust billing engine for a SaaS requires a balance between flexibility (for marketing) and strict data integrity (for accounting). Since you are managing subscriptions with feature-based limits and multi-tenancy, the database schema needs to be decoupled to allow for plan changes without breaking historical invoices.

Here is a conceptual model for your billing system.


sFsaas Kit is a free and open-source, batteries-included multitenant SaaS starter kit built on Laravel 13, Livewire, Tailwind CSS, Alpine.js, and Flux UI, with multi-database tenancy powered by Tenancy for Laravel (stancl/tenancy). Laravel 13’s official starter kits provide a supported Livewire-based baseline with Flux UI, authentication flows, and team-aware foundations that can be extended into a production-grade SaaS architecture. Tenancy for Laravel supports both multi-database tenancy and subdomain-based tenant identification, which aligns with the chosen architecture for SaaS Kit.

The product goal is to provide an opinionated but extensible repository that gives developers a production-ready starting point for tenant provisioning, central identity, tenant-scoped applications, billing foundations, REST APIs, onboarding, and testing. Tenancy for Laravel’s quickstart recommends a Tenant model using TenantWithDatabase, HasDatabase, and HasDomains, with tenant databases created and migrated through the package lifecycle. The tenancy package also documents subdomain identification through InitializeTenancyBySubdomain, where domains are stored as subdomain values instead of full hostnames.

Product goals
The repository should reduce the time required to launch a modern Laravel SaaS by solving the difficult parts up front: central user identity, many-to-many tenant membership, per-tenant databases, tenant provisioning, plan-aware billing foundations, and application boundaries that preserve isolation. Laravel’s own multi-tenant case study emphasizes resolving tenant context early in the request lifecycle, binding the current tenant into the container, and carrying tenant context into cache, config, queues, and authorization layers.

Primary goals:

Central identity with globally unique users by email.

Multi-database tenancy with one application database per tenant.

Subdomain-based tenant identification via tenant middleware.

A central control plane for signup, tenant creation, invitations, tenant switching, and billing administration.

A tenant application layer that runs on tenant subdomains with strict per-database isolation.

A billing foundation that separates mutable catalog entities from immutable invoice history and proration outcomes.

A repository structure that supports maintainability, testability, and incremental feature growth.

Non-goals
The initial repository should not try to solve every SaaS variation. It should not include custom ERP-grade accounting, marketplace billing, per-seat revenue recognition, or white-label multi-region infrastructure in v1. Stripe and Paddle both support complex proration and billing policies, but those provider-specific capabilities should be abstracted rather than deeply coupled into the first release.

Target users
The primary users are Laravel teams and indie founders who want to launch a serious multitenant SaaS quickly without stitching together tenancy, auth, billing, and dashboards from scratch. Laravel 13’s starter kits are positioned as the official starting point for Livewire + Flux UI applications, making them a natural developer-facing foundation for this audience.

Architecture decisions
The repository adopts the following architecture as fixed design decisions:

Decision	Chosen approach	Rationale
Frontend stack	Laravel 13 + Livewire + Tailwind + Alpine + Flux UI	Official Laravel starter path with supported UI primitives and auth workflows.
Tenancy model	Multi-database	Strong tenant isolation and cleaner enterprise path.
Tenant identification	Subdomain	First-class support in Tenancy for Laravel via subdomain middleware.
Identity source of truth	Central users table	Supports unique user emails and many-to-many tenant membership.
Membership model	Central pivot between users and tenants	Prevents users.tenant_id coupling and supports one user across many tenants.
Tenant data	Per-tenant databases	Isolates operational data and business records per workspace.
Billing owner	Tenant, not user	Billing belongs to the workspace in a multitenant SaaS.
Billing engine	Provider-agnostic abstraction with Stripe-first implementation	Stripe and Paddle differ in proration mechanics, so provider-neutral contracts are required.
Functional requirements
Central app
The central app acts as the control plane. It lives on one or more configured central domains and is responsible for signup, authentication, tenant registry, invitations, memberships, billing management, and tenant switching. Tenancy for Laravel’s quickstart requires central domains to be defined explicitly in config/tenancy.php, and central routes should be bound to those domains.

Required capabilities:

User registration and authentication on the central domain.

Unique email enforcement in the central users table.

Tenant creation wizard.

Subdomain availability check and reservation.

Membership invitations by email.

Membership acceptance and tenant access management.

Tenant switcher for users with access to multiple tenants.

Billing account management.

Admin console for central operators.

Tenant app
The tenant app runs on {tenant}.domain.tld and is initialized by tenancy middleware. Tenancy for Laravel documents InitializeTenancyBySubdomain as the mechanism for resolving tenant context from the subdomain and bootstrapping tenant-aware application behavior.

Required capabilities:

Tenant-scoped authentication session after central handoff.

Tenant-local dashboard and settings.

Tenant-scoped roles and permissions.

Access to business modules stored in the tenant database.

REST API with tenant-aware access control.

Tenant-local notifications, jobs, storage, and usage counters.

Billing engine
The billing engine must support plan catalogs, prices, subscription state, trials, discounts, invoices, and entitlements without mutating historical billing records. Stripe’s documentation shows that subscription changes can generate proration adjustments and invoice items based on the state of the subscription at change time, while Paddle allows multiple proration billing policies when updating a subscription.

Required capabilities:

Catalog management for plans, versions, prices, and features.

Trial and paid subscription phases.

Upgrade preview and execution.

Scheduled downgrades at period end.

Invoice synchronization from billing provider.

Entitlement derivation and usage limit enforcement.

Billing webhooks with idempotent processing.

Domain model
Central database entities
The central database stores identity, tenancy registry, membership, and cross-tenant billing control data.

Entity	Purpose
users	Global identity, unique email, password/MFA, profile, account state.
tenants	Tenant registry, UUID, slug, status, metadata, provisioning state.
domains	Maps tenants to subdomain identifiers for routing.
tenant_user	Many-to-many membership between users and tenants, with role and status.
tenant_invitations	Invitation records by email and role.
tenant_billing_accounts	Billing owner metadata and provider customer identifiers.
plans	Commercial plan group.
plan_versions	Versioned commercial definition of a plan.
prices	Billing interval and amount records per plan version.
features	Feature catalog.
plan_feature_values	Entitlement values per plan version.
subscriptions	Tenant-owned subscription state.
subscription_items	Per-price or per-feature components of a subscription.
subscription_phases	Trial, paid, grace, paused phases.
subscription_changes	Upgrade, downgrade, renewal, cancellation, and proration policy log.
subscription_discounts	Coupons and discounts applied to subscriptions.
invoices	Immutable invoice headers.
invoice_line_items	Immutable line items with catalog snapshots.
credit_notes	Credit adjustments.
payments	Provider payment transactions.
billing_webhook_events	Raw provider events for idempotent processing.
billing_sync_logs	Diagnostics for billing sync.
Tenant database entities
Each tenant database stores operational tenant-local records and synchronized identity projections where needed.

Entity	Purpose
users	Tenant-local projection of central users for local policies and joins.
roles, permissions, model_has_roles	Tenant-local authorization tables.
settings	Tenant application settings.
audit_logs	Tenant-local audit trail.
notifications	Tenant-local notification state.
tenant_entitlements	Active feature entitlements derived from subscription.
tenant_usage_counters	Fast counters by feature and period.
tenant_usage_events	Metered event log.
projects, customers, timesheets, etc.	Business modules shipped by the starter or installed later.
Identity and authentication design
Global identity is central and based on a unique email address. Community guidance around Tenancy for Laravel repeatedly uses a central user model, synced or mirrored into tenant databases, to support cross-tenant membership while preserving tenant database isolation.

Authentication design:

Central login happens on the central domain.

After login, the user sees the list of tenant memberships from tenant_user.

On tenant selection, the app redirects to the tenant subdomain.

Tenant middleware resolves the tenant from the subdomain and boots tenancy.

The tenant app establishes a tenant-local session for the corresponding tenant user projection.

Password, MFA, email verification, and account recovery remain central responsibilities. Laravel’s Livewire starter kit uses Fortify-based authentication flows and can be extended for these central account features.

Tenant lifecycle
Tenancy for Laravel’s quickstart describes tenant creation as an event-driven lifecycle where tenant database creation and migrations are part of the onboarding flow. The repository should implement tenant onboarding as a provisioning pipeline instead of a single controller action.

Provisioning flow:

Register or identify the central user.

Create tenant registry record.

Reserve and attach subdomain in domains.

Create tenant membership with owner role.

Trigger tenant database creation through Tenancy for Laravel.

Run tenant migrations and seed baseline data.

Create tenant-local user projection for the owner.

Provision default roles, settings, and starter records.

Redirect to tenant app.

Billing architecture
The billing engine must separate catalog, subscription state, entitlements, usage, and accounting records. Stripe documents that prorations create invoice adjustments tied to the current subscription context, and Paddle documents multiple proration modes when changing subscriptions, so the repository must preserve the billing decision and resulting records without rewriting history.

Billing design rules:

Catalog records are versioned and mutable only forward.

Invoice and payment records are immutable after issuance, except through explicit credit notes.

Entitlements are derived snapshots, not computed from live plan tables on every request.

Upgrades can be previewed before execution when the provider supports preview APIs.

Downgrades default to period-end execution.

Subscription ownership belongs to tenant_id, never user_id.

API design
The repository should expose a tenant-aware REST API for first-party and third-party integrations. The API should exist in two surfaces:

Central API for account, tenant membership, invitations, billing management, and tenant selection.

Tenant API for tenant-scoped resources and operations.

API design principles:

Use token-based auth appropriate for central and tenant contexts.

Never allow a central token to read tenant DB data unless explicitly exchanged for tenant context.

Scope rate limits and policies by tenant for tenant APIs.

Include idempotency support for billing and provisioning endpoints.


## Design  
###1. Database Schema Overview
To handle upgrades, downgrades, and trials effectively, you should separate the Catalog (what you sell) from the Subscription (what the user actually has).

1. Plans Table: Stores the "Blueprints" (e.g., Starter, Pro, Enterprise).

name, slug, trial_days, is_active.

2. Prices Table: Plans should have multiple prices (Monthly vs. Yearly).

plan_id, amount, currency, billing_interval (month/year).

3. Features Table: A master list of all possible features (e.g., "Orders", "Team Members").

4. Plan_Feature Table (Pivot): Maps features to plans with their specific limits.

plan_id, feature_id, limit_value (e.g., 500 orders).

5. Subscriptions Table: Tracks the user's current state.

user_id (or tenant_id), plan_id, price_id, status (active, trialing, cancelled), trial_ends_at, cycle_ends_at.

2. Handling Key Logic
Trials and Discounts
Trials: When a user signs up, set the status to trialing and calculate trial_ends_at based on the plan's trial_days. Block payment processing until the trial expires.

Yearly % Off: This is handled at the Price level. You define a monthly price of $50 and a yearly price of $500 (which is ~17% off). The system simply subscribes the user to the yearly price ID.

Feature Limits (The "Orders" Example)
To enforce limits without hitting the database for every single request, you can use a "Usage" table or cache.

Usage Tracking: Every time an order is created, increment a usage_count for that tenant.

Validation: Before allowing an action, compare the usage_count against the limit_value defined in the Plan_Feature table for their current plan.

Upgrades and Downgrades
Proration: If a user upgrades mid-month, you must calculate the unused portion of the old plan and credit it toward the new plan.

Formula: (New Price - Old Price) * (Days Remaining / Total Days in Cycle).

Downgrade Logic: Usually, it is safer to set the downgrade to happen at the end of the current billing cycle to avoid complex refund calculations.

## 2. Data Model


1. The Eloquent Models
Here is the core structure for your billing engine:

PHP
// Plan.php - The high-level package (e.g., "Pro")
class Plan extends Model {
    protected $fillable = ['name', 'slug', 'description', 'is_active'];

    public function prices() { return $this->hasMany(Price::class); }
    public function features() { return $this->belongsToMany(Feature::class)->withPivot('limit_value'); }
}

// Price.php - The specific cost (e.g., "$50/mo" or "$500/yr")
class Price extends Model {
    protected $fillable = ['plan_id', 'amount', 'currency', 'billing_interval', 'is_yearly'];
    
    public function plan() { return $this->belongsTo(Plan::class); }
}

// Feature.php - The logic gate (e.g., "Orders")
class Feature extends Model {
    protected $fillable = ['name', 'slug', 'type']; // type: 'limit' or 'boolean'
}

// Subscription.php - The bridge to the Tenant/User
class Subscription extends Model {
    protected $fillable = ['tenant_id', 'price_id', 'status', 'trial_ends_at', 'ends_at'];

    public function price() { return $this->belongsTo(Price::class); }
    
    // Helper for MRR calculation
    public function scopeActive($query) {
        return $query->where('status', 'active');
    }
}


2. Calculating MRR & ARR via Eloquent
With this design, calculating your metrics becomes a clean collection operation. We pull the amount from the Price model to ensure we account for the yearly % off correctly.

PHP
public function getMetrics()
{
    // 1. Get all active subscriptions with their price data
    $activeSubscriptions = Subscription::active()->with('price')->get();

    $totalMrr = $activeSubscriptions->sum(function ($subscription) {
        $price = $subscription->price;

        if ($price->billing_interval === 'year') {
            // Normalize yearly price to 1 month
            return $price->amount / 12;
        }

        return $price->amount;
    });

    return [
        'mrr' => $totalMrr,
        'arr' => $totalMrr * 12,
        'customer_count' => $activeSubscriptions->count(),
    ];
}


3. Handling Feature Limits (The "Orders" Example)
To enforce the "Orders" limit you mentioned, you can create a helper method on your Tenant or User model. This checks the current plan's limit against the actual usage.

PHP
// Inside Tenant.php or User.php
public function hasReachedLimit($featureSlug)
{
    $subscription = $this->subscription()->with('price.plan.features')->first();
    
    $feature = $subscription->price->plan->features
        ->where('slug', $featureSlug)
        ->first();

    if (!$feature) return true; // Feature not in plan

    $limit = $feature->pivot->limit_value;
    
    // Assuming you have an 'orders_count' or similar tracking
    return $this->orders()->count() >= $limit;
}

### `Plan`
- `id`: primary key


## 3. Architecture

### Provider-Agnostic Bank Sync

### Logic Layers

## 4. User Interface (Livewire & Flux UI)

### Dashboard (`SaaS Admin/Dashboard`)
- **Cards**: Total Tenant, Total User. MRR, ARR, Churn Rate.
- **Charts**: Interactive suscrptions by plan (Pie/Doughnut) and monthly trends (Bar/Line) using Chart.js.
- **Recent Susciptions**: Flux UI Table with filtering and sorting.

### Management Pages
- **Plans**: cards of plans.
- **Pricing**: list of prices.
- **Features**: cards of features.

## 5. Implementation Strategy
1.  **Phase 1: Database & Models**: Migrations, Models, Factories, and basic CRUD tests (Pest).
2.  **Phase 2: Core Logic**: Actions for tenant registration, plan management, subscription management, payment management, email management.
3.  **Phase 3: Tenant Admin**  
   
4.  **Phase 4: UI Development**:Landing page,Pricing Table, Registration Page, Tenant onboarding, Dashboard components, Charts, and management forms using Flux UI.

5.  **Phase 5: Notifications**: Real-time budget alerts via Laravel Notifications.

## 6. Testing Plan
- **Unit Tests**: Tenats, Plans, Prices, Features, Subscriptions, Payments.
- **Feature Tests**: registartion workflow, plan management, subscription management, payment management, email management.
- **Browser Tests**: Interactive charts, drag-and-drop category management (if applicable).
- **Performance Tests**:     