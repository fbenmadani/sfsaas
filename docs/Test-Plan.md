Test plan
The repository needs a layered test plan because multitenancy and billing bugs are often cross-cutting. Laravel’s multitenant architecture guidance highlights tenant context propagation into middleware, queues, and configuration, while billing providers document non-trivial proration behavior that must be verified around subscription changes.

Test strategy
Unit tests for pure business rules and value objects.

Feature tests for HTTP/UI flows.

Integration tests for tenancy bootstrap, database switching, billing provider mapping, and webhook ingestion.

End-to-end tests for tenant signup, login, provisioning, invite acceptance, upgrade, downgrade, and cancellation.

Core test areas
1. Central identity tests
User email must be globally unique.

Login, password reset, MFA, and email verification work on the central domain.

One user can belong to multiple tenants.

Tenant owner and non-owner permissions are enforced in central membership management.

2. Tenancy bootstrap tests
Request to acme.example.test initializes tenant acme by subdomain.

Request to central domain never initializes tenancy.

Unknown subdomain returns the expected error response.

Tenant DB connection switches correctly after middleware initialization.

Tenant queues and scheduled jobs restore tenant context before execution, consistent with Laravel’s recommended architecture principles.

3. Provisioning tests
Creating a tenant inserts central records and creates the tenant DB.

Tenant migrations and seeders run successfully during provisioning.

Failed provisioning leaves the tenant in a recoverable state.

Owner user projection is created in the tenant DB.

Duplicate subdomain is rejected.

4. Membership and invitation tests
Invitation can be sent to a new email.

Invitation can attach an existing central user to a tenant.

Accepting an invitation creates central membership and tenant projection.

Removing membership blocks tenant access but preserves central identity.

5. Billing tests
Catalog version changes do not mutate historical invoice snapshots.

Trial phase sets correct dates and blocks charge attempts until conversion.

Upgrade preview returns proration details when supported by the provider.

Upgrade execution creates a subscription change record and invoice line items.

Downgrade is scheduled to period end by default.

Webhooks are idempotent and safe to replay.

Discount application appears as separate invoice effects, not overwritten base pricing.

6. Entitlements and usage tests
Subscription activation derives tenant entitlements.

Feature checks read from active entitlements, not live catalog joins.

Usage counters increment correctly on business events.

Over-limit actions are rejected with deterministic behavior.

7. API tests
Central token cannot access tenant data without tenant context.

Tenant token cannot access another tenant’s resources.

Rate limiting and policy checks are enforced per tenant.

Idempotency keys prevent duplicate provisioning or billing changes.

8. Regression and smoke tests
Basic central app pages render.

Basic tenant app pages render after tenancy bootstrap.

Tenant switcher redirects to the right subdomain.

Billing pages load for active and trialing tenants.

Recommended tooling
Pest or PHPUnit for core tests.

Database factories for central and tenant entities.

Fake billing provider adapters for unit and integration tests.

Tenant test helpers to create tenant DBs quickly.

CI matrix running central-only, tenant-only, and full integration suites.

Example CI stages
Static analysis and coding standards.

Unit tests.

Central feature tests.

Tenancy integration tests.

Billing integration tests.

End-to-end smoke suite.

Acceptance criteria
The initial release is complete when the repository can:

Register a unique central user and sign them in.

Create a tenant, reserve a subdomain, provision its database, and redirect into the tenant app.

Allow the same central user to belong to multiple tenants.

Maintain separate operational data in separate tenant databases.

Create and manage a tenant-owned subscription with immutable invoice history and provider sync.

Derive feature entitlements and enforce usage limits.

Pass the automated test suite covering central, tenant, billing, and provisioning scenarios.

Risks and mitigations
Risk	Impact	Mitigation
Tenant provisioning partially fails	Orphaned tenant registry or unusable tenant DB	Use explicit provisioning states, retryable jobs, and compensating cleanup.
Central and tenant auth drift apart	Users cannot access tenants reliably	Keep central identity authoritative and sync tenant projections deterministically.
Billing provider differences leak into domain model	Provider lock-in and brittle code	Keep provider-neutral interfaces and snapshot provider payloads separately.
Historical invoices become inaccurate after plan edits	Accounting integrity failure	Use plan versions and invoice line snapshots.
Cross-tenant access due to routing or middleware mistakes	Severe security issue	Separate central and tenant routes clearly and test tenancy initialization exhaustively.
Delivery artifacts
The repository should ultimately ship with:

The full Laravel application.

Example central and tenant migrations.

Seed data for plans and starter roles.

A provider abstraction with at least one working billing adapter.

Documentation for local setup with wildcard subdomains.

A test suite covering central, tenant, billing, and provisioning paths.