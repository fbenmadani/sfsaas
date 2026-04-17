# Project Tech Stack Audit

## Core Framework & Language
- **PHP:** 8.3
- **Laravel:** 13.2.0
- **Database:** SQLite (Default)

## Multi-Tenancy
- **Package:** `stancl/tenancy` (v3.10)
- **Strategy:** Multi-database tenancy (using SQLite databases per tenant).
- **Configuration:** 
    - Tenant Model: `App\Models\Tenant`
    - Domain Model: `Stancl\Tenancy\Database\Models\Domain`
    - Central Domains: `127.0.0.1`, `localhost`, `saas.test`
    - Bootstrappers: Database, Cache, Filesystem, Queue.

## Frontend Stack
- **Reactivity:** `livewire/livewire` (v4.2.3)
- **UI Components:** `livewire/flux` (v2.13.1 - Free version)
- **Styling:** `tailwindcss` (v4.0.7)
- **Bundler:** `vite` (v8.0.0) with `@tailwindcss/vite`
- **HTTP Client:** `axios` (v1.7.4)
- **Client-side Scripting:** `alpinejs` (integrated with Livewire/Flux)

## Authentication & Authorization
- **Authentication Backend:** `laravel/fortify` (v1.36.2)
- **Features:** Login, Registration, Password Reset, Email Verification, Two-Factor Authentication (seen in migrations).

## Testing & Quality Assurance
- **Testing Framework:** `pestphp/pest` (v4.4.3)
- **Unit Testing:** `phpunit/phpunit` (v12.5.14)
- **Code Style:** `laravel/pint` (v1.29.0)

## Development & Tooling
- **Environment:** `laravel/sail` (v1.55.0)
- **Logging:** `laravel/pail` (v1.2.6)
- **Assistant/MCP:** `laravel/boost` (v2.4.1), `laravel/mcp` (v0.6.4)
- **CLI Interaction:** `laravel/prompts` (v0.3.16)
- **REPL:** `laravel/tinker` (v3.0)

## Dependencies (Summary)
- `fakerphp/faker`: ^1.24
- `mockery/mockery`: ^1.6
- `nunomaduro/collision`: ^8.9
- `stancl/tenancy`: ^3.10
