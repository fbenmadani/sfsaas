# SpeedForge SaaS — AGENTS.md

## Stack

- **Laravel 13** + PHP 8.3+ · **Livewire 4** (class components in `app/Livewire/`) · **Flux 2** · **Tailwind CSS 4** · **Vite 8**
- **Multi-tenancy**: `stancl/tenancy` v3, subdomain-based (`InitializeTenancyBySubdomain`), multi-database (SQLite per tenant)
- **Auth**: Laravel Fortify (login, registration, password reset, email verification, 2FA)
- **Testing**: Pest 4 + SQLite `:memory:` · Browser tests via Pest Browser plugin + Playwright

## Key architecture

| Layer | Location |
|---|---|
| Central routes | `routes/web.php` (loops over `config('tenancy.central_domains')`) |
| Tenant routes | `routes/tenant.php` (`InitializeTenancyBySubdomain`, `PreventAccessFromCentralDomains`) |
| Settings routes | `routes/settings.php` |
| Livewire components | `app/Livewire/` (class) + `resources/views/components/` (SFC views) |
| Auth views | `resources/views/livewire/auth/` (registered in `FortifyServiceProvider`) |
| Admin middleware | `App\Http\Middleware\IsAdmin` — checks `$user->is_admin`, returns 403 |
| Tenant migrations | `database/migrations/tenant/` (users, cache, jobs) |
| Service layer | `App\Service\BillingService` (MRR/ARR) |

### Central domains (config/tenancy.php)
`127.0.0.1`, `localhost`, `saas.test`, `sfsaas.test`

### Models
- **User**: `is_admin` flag, `tenant_id` FK, uses `#[Fillable]` / `#[Hidden]` PHP attributes
- **Tenant**: extends `Stancl\Tenancy\Database\Models\Tenant`, has users, domains
- **Domain**: extends `Stancl\Tenancy\Database\Models\Domain`, has `type` (`subdomain`|`tld`) and `is_primary`
- **Plan** → hasMany `Price`, belongsToMany `Feature` (pivot `plan_feature` with `limit_value`)
- **Feature**: `type` column (`limit`|`boolean`)
- **Price**: `amount`, `currency`, `billing_interval`, `is_yearly`
- **Subscription**: `status`, belongsTo `Price`

## Commands

```bash
composer setup          # Full bootstrap (install + .env + key + migrate + npm build)
composer dev            # concurrently: artisan serve, queue:listen, pail, vite
composer lint           # pint --parallel (auto-fix)
composer lint:check     # pint --parallel --test (check only)
composer test           # config:clear → lint:check → php artisan test
./vendor/bin/pest       # Direct Pest runner (also used in CI)
npx playwright install --with-deps   # Browser test dependencies (CI)
```

## Testing conventions

- Uses `pest()->extend(TestCase::class)->use(RefreshDatabase::class)->in('Feature', 'Browser', '../resources/views')`
- `TestCase` provides `skipUnlessFortifyHas(string $feature)` helper
- Livewire component tests use string syntax: `Livewire::test('admin.plans.index')`
- Factory namespace: `Database\Factories\*` (e.g. `PlanFactory`, `UserFactory`)
- Admin tests create admin user via `User::factory()->create(['is_admin' => true])` + `actingAs()`
- CI tests against PHP 8.3/8.4/8.5, CI lint on 8.4
- Flux components require `Tests\Support\TestFluxServiceProvider` in test env (set via phpunit.xml `APP_PROVIDERS`)
- In CI: Flux credentials from secrets (`FLUX_USERNAME`, `FLUX_LICENSE_KEY`)

## Livewire specifics

- Default make type: `sfc` (Single File Component) — uses ⚡ prefix in filenames
- `Route::livewire()` syntax for page components, e.g. `Route::livewire('/path', 'component.name')`
- Layout: `layouts::app` (from `resources/views/layouts/`)
- Computed properties use `#[Computed]` attribute
- View references follow `components.admin.plans.⚡index` pattern (with `components.` prefix)

## Quirks & gotchas

- **`.gitignore` has unresolved merge conflict markers** — clean it up before committing
- **`database/database.sqlite` is committed** along with many stale tenant SQLite files (test artifacts) in `database/`
- Fortify relies on an admin middleware alias `admin` (registered in `bootstrap/app.php`), not route groups
- All central routes iterate `config('tenancy.central_domains')` to scope by domain
- SFC component views use `⚡` prefix but class components reference them without it in `render()`
- `boost.json` enables `laravel-boost` MCP server (configured in `.gemini/settings.json`)
