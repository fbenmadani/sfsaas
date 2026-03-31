## Goal
Implement the core multi-tenant database infrastructure, central SaaS billing models, and the primary actions for tenant registration and limits as outlined geographically in `Software-Requirement-Specification.md` and `2026-03-31-saas-management-design.md`.

## Assumptions
- The application uses Laravel 11.x, PHP 8.3, Pest, and the TALL stack (Livewire 4 / Flux UI).
- Tenancy architecture uses a multi-database approach: Mysql for the global central database, and Mysql for isolated tenant databases (FR-1).
- We are implementing a custom schema for Plans, Pricing, and Subscriptions rather than heavily opinionated packages, to achieve granular control over feature tracking (`limit_value`).
- A multi-database tenancy package (e.g., `stancl/tenancy`) may be utilized or a custom database connection switcher will be built. This plan establishes the foundational model schema first.

## Plan
1. **Configure Multi-Database Connections**
   - Files: `config/database.php`, `.env.example`
   - Change: Define a `central` connection (pgsql) and a dynamically configurable `tenant` connection (mysql). Set up the required environment variables.
   - Verify: Run `php artisan config:show database.connections.central` and `php artisan config:show database.connections.tenant`.
2. **Core Central Models & SaaS Billing Schema (Phase 1)**
   - Files: `database/migrations/xxxx_create_plans_prices_features_tables.php`, `app/Models/Plan.php`, `app/Models/Price.php`, `app/Models/Feature.php`
   - Change: Create the "Catalog" schema (Plans, Prices, Features) and the `feature_plan` pivot table within the central database migration path.
   - Verify: Run `php artisan migrate --database=central` and executing Pest model verification tests.
3. **Tenant & Subscription Schema (Phase 1)**
   - Files: `database/migrations/xxxx_create_tenants_and_subscriptions_tables.php`, `app/Models/Tenant.php`, `app/Models/Subscription.php`
   - Change: Create the `tenants` table (tracking the subdomain) and `subscriptions` table (tracking the `price_id` and status) in the central DB.
   - Verify: Execute `php artisan migrate --database=central` and verify relations with `tests/Unit/TenantSchemaTest.php`.
4. **Tenant Registration Action (Phase 2)**
   - Files: `app/Actions/Tenant/RegisterTenantAction.php`, `app/Jobs/ProvisionTenantDatabase.php`
   - Change: Implement the action to create a Tenant, attach a standard Subscription, and dispatch a job to provision their MySQL database (FR-1) and subdomain resolving logic (FR-2).
   - Verify: Write test `tests/Feature/RegisterTenantActionTest.php` and execute `vendor/bin/pest`.
5. **Tenant Usage Limits & Sync (Phase 2)**
   - Files: `app/Services/Billing/UsageLimitService.php`
   - Change: Implement a service to verify a tenant's current usage against their plan's limits directly and sync data between the Tenant and Central DB (FR-3).
   - Verify: Write test `tests/Feature/UsageLimitServiceTest.php` and execute `vendor/bin/pest`.

## Risks & mitigations
- **Multi-database Testing Complexity:** Running tests against Postgres and MySQL concurrently can be fragile in CI locally.
  - *Mitigation:* Configure Pest to utilize `sqlite` in-memory for both `central` and `tenant` schemas during the initial test suite phase, explicitly verifying raw schema creation separately.
- **Provisioning Failures (FR-1):** Database creation logic might throw exceptions, leaving orphaned records or half-booted tenants.
  - *Mitigation:* Ensure `RegisterTenantAction` utilizes strict database transactions internally, and the initial DB provisioning job has clearly defined retry policies.

## Rollback plan
- Wipe external schemas: `php artisan db:wipe --database=central`
- Revert code structure: `git reset --hard HEAD` and `git clean -fd` to sweep out all auto-generated classes and migrations.
