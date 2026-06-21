## Goal
Clean up the application structure, resolve PSR-4 namespace mismatches, remove duplicate legacy code, fix Livewire v4 component shadow-property bugs, remove HTML/Blade syntax typos, and standardize coding practices across the models and services.

## Assumptions
- The active namespace for Livewire components is `App\Livewire` (mapped in `config/livewire.php`).
- The directory `app/Http/Livewire` is completely legacy and its components are duplicated or unused.
- Central domain suffixes (like `.sfsaas.test`) should be dynamic based on configuration instead of hardcoded.
- Existing tests cover core functionalities and can run successfully locally when the test environment is correctly configured.

## Plan

### Step 1: Remove Legacy and Duplicate Livewire Directory
- **Files**:
  - `app/Http/Livewire/` (Directory)
  - `composer.json`
- **Change**:
  - Delete `app/Http/Livewire/` directory and all its files (including `PricingList.php`, and the `Admin` subdirectories containing `Create.php`, `Edit.php`, `Index.php` duplicates).
  - Remove `"App\\Http\\Livewire\\": "app/Http/Livewire/"` from the `autoload.psr-4` section in `composer.json`.
  - Run `composer dump-autoload` to update the autoloader.
- **Verify**:
  - Run `composer lint:check` to ensure composer and pint config checks pass.

### Step 2: Remove Duplicate and Unused Views
- **Files**:
  - [DELETE] [index.blade.php](file:///g:/laragon/www/sfsaas/resources/views/livewire/admin/plans/index.blade.php)
- **Change**:
  - Delete `resources/views/livewire/admin/plans/index.blade.php` since the plan index component uses `resources/views/components/admin/plans/⚡index.blade.php` exclusively.
- **Verify**:
  - Run the test suite or search for references to `livewire.admin.plans.index` to ensure it is not referenced anywhere.

### Step 3: Remove Typo Attributes from Flux Table Rows
- **Files**:
  - [MODIFY] [⚡index.blade.php](file:///g:/laragon/www/sfsaas/resources/views/components/admin/tenants/⚡index.blade.php)
  - [MODIFY] [⚡index.blade.php](file:///g:/laragon/www/sfsaas/resources/views/components/admin/users/⚡index.blade.php)
- **Change**:
  - In `components/admin/tenants/⚡index.blade.php`, remove `:table.row` from `<flux:table.row :table.row :key="$tenant->id">`.
  - In `components/admin/users/⚡index.blade.php`, remove `:table.row` from `<flux:table.row :table.row :key="$user->id">`.
- **Verify**:
  - Ensure standard rendering continues to function without console/view errors.

### Step 4: Fix Shadowing Property Bug in Tenant Show Component
- **Files**:
  - [MODIFY] [⚡show.blade.php](file:///g:/laragon/www/sfsaas/resources/views/components/admin/tenants/⚡show.blade.php)
- **Change**:
  - Remove public properties `public $subscription;`, `public $subscription_plan;`, and `public $users;` from the Livewire component class definition. This resolves the Livewire v4 bug where public properties shadow `#[Computed]` methods (returning `null`).
- **Verify**:
  - Verify that the tenant show details page renders the correct user counts, subscription names, and other metrics instead of empty/null values.

### Step 5: Consolidate Plan Creation Flow
- **Files**:
  - [MODIFY] [⚡index.blade.php](file:///g:/laragon/www/sfsaas/resources/views/components/admin/plans/⚡index.blade.php)
  - [DELETE] [Create.php](file:///g:/laragon/www/sfsaas/app/Livewire/Admin/Plans/Create.php)
  - [DELETE] [⚡create.blade.php](file:///g:/laragon/www/sfsaas/resources/views/components/admin/plans/⚡create.blade.php)
  - [MODIFY] [web.php](file:///g:/laragon/www/sfsaas/routes/web.php)
- **Change**:
  - In `resources/views/components/admin/plans/⚡index.blade.php`, change the "Create Plan" button to open the `plan-modal` directly (`wire:click="createNewPlan" x-on:click="$flux.modal('plan-modal').open()"`).
  - Delete `app/Livewire/Admin/Plans/Create.php` and `resources/views/components/admin/plans/⚡create.blade.php`.
  - Remove the `/admin/plans/create` route from `routes/web.php`.
- **Verify**:
  - Verify that plan creation now happens successfully inside the modal from the plans list page.

### Step 6: Fix Hardcoded Domain Suffixes
- **Files**:
  - [MODIFY] [sign-up.blade.php](file:///g:/laragon/www/sfsaas/resources/views/pages/account/sign-up.blade.php)
  - [MODIFY] [⚡index.blade.php](file:///g:/laragon/www/sfsaas/resources/views/components/admin/tenants/⚡index.blade.php)
  - [MODIFY] [⚡show.blade.php](file:///g:/laragon/views/components/admin/tenants/⚡show.blade.php)
- **Change**:
  - Define a helper or dynamic variable in the component/view to resolve the tenant domain suffix from config instead of hardcoding `.sfsaas.test`.
- **Verify**:
  - Inspect the sign-up page and the tenant list/show URLs to confirm the host suffix is dynamic.

### Step 7: Apply PHP/Laravel Best Practices
- **Files**:
  - [MODIFY] [User.php](file:///g:/laragon/www/sfsaas/app/Models/User.php)
  - [MODIFY] [Tenant.php](file:///g:/laragon/www/sfsaas/app/Models/Tenant.php)
  - [MODIFY] [Subscription.php](file:///g:/laragon/www/sfsaas/app/Models/Subscription.php)
  - [MODIFY] [Price.php](file:///g:/laragon/www/sfsaas/app/Models/Price.php)
  - [MODIFY] [Plan.php](file:///g:/laragon/www/sfsaas/app/Models/Plan.php)
  - [MODIFY] [Domain.php](file:///g:/laragon/www/sfsaas/app/Models/Domain.php)
  - [MODIFY] [BillingService.php](file:///g:/laragon/www/sfsaas/app/Service/BillingService.php)
- **Change**:
  - Add explicit return type hints to all relationship methods on the models.
  - In `BillingService.php`, remove the empty constructor and add type hint `: array` for `getMetrics()`.
  - Run `vendor/bin/pint --dirty --format agent` to format code.
- **Verify**:
  - Run the test suite to ensure all relationships and billing metrics continue to behave correctly.

## Risks & mitigations
- **Risk**: Deleting `app/Http/Livewire` might cause errors if any component refers to it.
  - *Mitigation*: We searched for all references to `App\Http\Livewire` namespace and found none in the active code.
- **Risk**: Modal-based creation for plans may hit validation or styling issues.
  - *Mitigation*: We will verify the validation errors map correctly within the modal layout.

## Rollback plan
- In case of issues, run `git checkout -- .` and restore deleted files using Git (`git checkout HEAD -- <file_path>`).
