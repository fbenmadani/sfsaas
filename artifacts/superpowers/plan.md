## Goal
Write comprehensive feature and unit tests for `Tenant` and `User` models using Pest PHP to ensure their relationships, database integration, and custom methods are functioning as expected.

## Assumptions
- The application uses `stancl/tenancy` for multi-tenancy.
- Pest PHP is the testing framework of choice configured in the application.
- Tests will follow `laravel-boost-guidelines` by favoring Feature tests when interacting with the database.
- `Tenant` model has a `users` HasMany relationship, and `User` model has a `tenant` BelongsTo relationship.
- `User` model has a custom `initials()` method and specific attribute casts (`is_admin`, `tenant_id`).

## Plan

### 1. Create Models Test Files
- **Files**: `tests/Feature/Models/TenantTest.php`, `tests/Feature/Models/UserTest.php`
- **Change**: Use `php artisan make:test Models/TenantTest --pest` and `php artisan make:test Models/UserTest --pest` to create the test files.
- **Verify**: Ensure files exist by checking `tests/Feature/Models` directory.

### 2. Implement Tenant Model Tests
- **Files**: `tests/Feature/Models/TenantTest.php`
- **Change**:
  - Add `RefreshDatabase` trait.
  - Write a test `it('can create a tenant')` to verify model creation properties.
  - Write a test `it('has many users')` to verify the `users` relationship successfully links to the User model.
- **Verify**: Run `php artisan test --compact tests/Feature/Models/TenantTest.php` to ensure the Tenant tests pass.

### 3. Implement User Model Tests
- **Files**: `tests/Feature/Models/UserTest.php`
- **Change**:
  - Add `RefreshDatabase` trait.
  - Write a test `it('calculates the correct initials')` to verify the `initials()` method. Test different name lengths.
  - Write a test `it('belongs to a tenant')` to verify the `tenant()` relationship resolves to the correct Tenant model.
  - Write a test `it('correctly casts attributes')` to check if `is_admin` is cast to boolean and `tenant_id` to string.
- **Verify**: Run `php artisan test --compact tests/Feature/Models/UserTest.php` to ensure the User tests pass.

## Risks & mitigations
- **Risk**: Creating a `Tenant` model might trigger multi-tenant database creation, which can be computationally expensive and slow down tests.
- **Mitigation**: Using `RefreshDatabase`. If database integration is strictly needed, rely on Laravel testing utilities.
- **Risk**: `stancl/tenancy` might encounter issues resolving default settings during testing.
- **Mitigation**: Rely on tenant/user factories or mock the creation safely without full database provisioning where unnecessary.

## Rollback plan
- Remove `tests/Feature/Models/TenantTest.php` and `tests/Feature/Models/UserTest.php`.
