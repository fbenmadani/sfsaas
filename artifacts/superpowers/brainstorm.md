## Goal
To architect and design a comprehensive SaaS layer (Admin Panel and User Dashboard) for `sfSaas`, providing all foundational requirements for a typical modern SaaS—including modular reusable components, user/role administration, subscription & pricing plans, billing/payments, and email notifications—making it a cohesive, easily customizable starter kit.

## Constraints
* Must seamlessly integrate with the existing technology stack (PHP 8.3, Laravel 11.x, Livewire 4, Flux UI, Pest, Fortify, etc. per the `laravel-boost-guidelines`).
* The system must remain flexible enough for different types of SaaS businesses while providing strong, opinionated defaults for billing and tenant access.
* Reusable view components must be strictly maintained and easily overridable by developers building their apps on top of the kit.

## Known context
* We are building a "complete SaaS starter kit" named `sfSaas`.
* Existing capabilities (or requirements) include: a huge list of reusable UI components, complete Admin Panel, User Dashboard, User Authentication, User & Role Management, Plans & Pricing, Subscriptions, Payments, and Emails.
* You are currently working on a design doc (`docs/2026-03-31-saas-management-design.md`) where these concepts are being formulated.
* The local environment enforces Laravel Boost guidelines, which favors testing with Pest, strict frontend bundling, and idiomatic Laravel conventions.

## Risks
* **Over-engineering:** Building too many disparate features too early rather than focusing on a robust modular core.
* **Tight component coupling:** Tightly coupling UI components to business logic makes it very difficult for end developers to customize logic without breaking the frontend.
* **Billing edge cases:** Customizing subscription states (grandfathering, custom prorations, upgrades/downgrades) can quickly spiral out of scope if not properly aligned with a robust tool like Laravel Cashier.
* **Security configuration:** Misconfiguring RBAC (Role-Based Access Control) limits across tenant and admin boundaries.

## Options (2–4)
1. **Monolithic SaaS Kit:** Tightly couple everything to typical Laravel packages (e.g., `Laravel Cashier`, `Spatie Permission`, and native Livewire components). It's highly opinionated and extremely fast to bootstrap, but rigid to extend.
2. **Modular/Isolated Service Approach:** Package the billing, user management, and admin functionalities as isolated modules or internal composer packages. This heavily separates concerns but introduces a steep learning curve and more boilerplate for the end user.
3. **Headless Backend Actions + Thin Flux UI Frontend:** Extract core SaaS logic into purely headless Action classes or API resources (much like Laravel Fortify does for Auth). Let the frontend (Livewire + Flux UI) act solely as a thin presentation layer that dispatches these Actions. Best for long-term extendability and customizability.

## Recommendation
**Option 3 (Headless Backend Actions + Thin Flux UI Frontend)**
Since the project relies on Laravel Fortify (a headless authentication backend), following that same pattern for the rest of the SaaS kit is ideal. Build functionalities (billing, tenant creation, roles, plan management) via standalone Action classes (e.g., `CreateSubscriptionAction`, `AssignRoleAction`). Then, use Livewire 4 and Flux UI (v2) to build high-quality, reusable frontend components that simply consume these actions. This keeps `sfSaas` extremely powerful yet straightforward for end users to customize the UI without touching the core business logic.

## Acceptance criteria
* The base architecture for Authentication, Roles, Plans/Subscriptions, and Reusable UI Components is explicitly outlined.
* Standard user and admin workflows are mapped to discrete headless Action classes.
* Livewire + Flux UI components are identified to support the User and Admin dashboards.
* A clear database schema strategy (and potentially multitenancy approach) is defined that supports the requirements for tracking payments, roles, and emails.
