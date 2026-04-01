# Changelog

All notable changes to this project will be documented in this file.

## [Unreleased]

### Added
- **Initial Setup**: Initialized project with Laravel 13 and Livewire.
- **Superpowers**: Integrated Superpowers skills and configuration.
- **Documentation**:
    - Created `Software-Requirement-Specification.md`.
    - Created `2026-03-31-saas-management-design.md` for SaaS management architecture.
    - Drafted `readme.md` with installation instructions.
- **Multi-tenancy (Stancl/Tenancy)**:
    - Installed `stancl/tenancy` package.
    - Registered `TenancyServiceProvider` in `bootstrap/providers.php`.
    - Configured central domains in `tenancy.php`.
    - Restricted central routes to central domains.
    - Created `App\Models\Tenant` extending `Stancl\Tenancy\Database\Models\Tenant`.
    - Configured `tenant_model` in `config/tenancy.php`.
    - Implemented `InitializeTenancyBySubDomain` middleware for subdomain handling.
    - Added tenant-specific migrations for `users`, `sessions`, and `jobs`.
- **Livewire Configuration**: Published Livewire configuration using `php artisan livewire:config`.
- **Infrastructure**:
    - Initialized `package.json` and generated node dependencies.
    - Configured dev server for `sfsaas.test` domain.
