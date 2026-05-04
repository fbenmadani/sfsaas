## Goal
Create a premium landing page (Home), Features, Pricing, and About pages for sfSaas using Flux UI and Tailwind CSS 4.

## Assumptions
- Using Flux UI Free components.
- Using Tailwind CSS 4 (Vite plugin).
- Application uses `stancl/tenancy` for domain-based routing (central routes).
- Pages should be visually stunning, responsive, and follow SEO best practices.

## Plan

### 1. Create Marketing Layout
- **Files**: `resources/views/layouts/marketing.blade.php`
- **Change**: Define a base layout with a premium Flux-based navbar (using `flux:navbar`, `flux:brand`, `flux:button`) and a comprehensive footer.
- **Verify**: Create a temporary route to view the empty layout.

### 2. Define Marketing Routes
- **Files**: `routes/web.php`
- **Change**: Register routes for `/` (home), `/features`, `/pricing`, and `/about` within the central domain group.
- **Verify**: Run `php artisan route:list` to ensure routes are correctly mapped to views.

### 3. Implement Home Page
- **Files**: `resources/views/marketing/home.blade.php`
- **Change**: Implement a high-impact Hero section with a generated illustration, a summary of Sales/Marketing/Service features, and a clear CTA.
- **Verify**: Navigate to root URL and check for "wow" factor and responsiveness.

### 4. Implement Features Page
- **Files**: `resources/views/marketing/features.blade.php`
- **Change**: Detailed breakdown of the three core modules (Sales, Marketing, Customer Service) using Flux cards and icons.
- **Verify**: Navigate to `/features` and ensure clear value proposition.

### 5. Implement Pricing Page
- **Files**: `resources/views/marketing/pricing.blade.php`
- **Change**: Create a multi-tier pricing table (e.g., Starter, Pro, Enterprise) using Flux's clean aesthetic and interactive buttons.
- **Verify**: Navigate to `/pricing` and check layout on mobile.

### 6. Implement About Page
- **Files**: `resources/views/marketing/about.blade.php`
- **Change**: Design an "About Us" page detailing the mission to help SMBs, following the same premium design system.
- **Verify**: Navigate to `/about`.

### 7. Automated Testing & Verification
- **Files**: `tests/Feature/MarketingPagesTest.php`
- **Change**: Write Pest tests to assert that all four pages return a 200 OK status.
- **Verify**: Run `php artisan test --filter MarketingPagesTest`.

## Risks & mitigations
- **Risk**: Flux UI Pro components might be accidentally used.
- **Mitigation**: Strictly use components listed in the Free edition documentation.
- **Risk**: Tailwind 4 breaking changes or config issues.
- **Mitigation**: Verify compilation with `npm run build` early.

## Rollback plan
- Revert `routes/web.php` to its previous state.
- Delete the created layout and marketing view directory.
- `git checkout routes/web.php` and `rm -rf resources/views/marketing resources/views/layouts/marketing.blade.php`.
