# Design Specification: Admin Plan Management (Option A)

## Overview
Implement a comprehensive Plan Management system for administrators to manage subscription plans, pricing, and features (including quotas). The system will use standard class-based Livewire components and Flux UI.

## Goals
- Manage a global list of Features (Boolean or Quota-based).
- Manage Plans with multiple Pricing options (Monthly, Yearly, One-time).
- Assign Features to Plans with specific limits (Quotas).
- Provide a modern, responsive UI using Flux.

## 1. Data Schema Updates

### 1.1 Features Table
Update the `features` table to include a `type` column.
- `type`: `string` (enum: `boolean`, `limit`). Default: `boolean`.

### 1.2 Model Adjustments
- **Feature.php**: Add `type` to `$fillable`.
- **Price.php**: Ensure `billing_interval` supports `month`, `year`, and `once`.
- **Plan.php**: Verify relationships with `Price` and `Feature`.

## 2. Global Feature Management

### 2.1 Feature List (`App\Livewire\Admin\Features\Index`)
- **Route**: `/admin/features`
- **Component**: Standard Livewire class-based component.
- **View**: Flux table listing Name, Slug, Type.
- **Actions**: "Create Feature" button.

### 2.2 Create/Edit Feature (`App\Livewire\Admin\Features\Form`)
- **UI**: Flux Modal or separate page (Modal preferred for simplicity).
- **Fields**:
    - `name`: String.
    - `slug`: String (Auto-generated from name if possible).
    - `type`: Flux Radio or Select (Boolean vs. Limit).

## 3. Plan Management

### 3.1 Plan List (`App\Livewire\Admin\Plans\Index`)
- **Route**: `/admin/plans`
- **View**: Flux table showing Plan Name, Status (Active/Inactive), Price Count.
- **Actions**: Edit, Delete, Toggle Status.

### 3.2 Plan Edit/Detail (`App\Livewire\Admin\Plans\Edit`)
- **Route**: `/admin/plans/{plan}/edit`
- **Sections**:
    1. **Plan Information**: Form for Name, Slug, Description, Trial Days, Is Active.
    2. **Prices**: List of prices belonging to this plan.
        - Button to "Add Price".
        - Modal to set Amount (stored in cents), Currency, and Interval (Month, Year, Once).
    3. **Features & Quotas**: 
        - List of ALL global features.
        - For each feature:
            - Toggle switch to "Enable" for this plan.
            - If feature type is `limit`: Number input for `limit_value` (visible only if enabled).

## 4. User Interface (Flux UI)
- Use `<flux:table>` for listings.
- Use `<flux:button>`, `<flux:input>`, `<flux:switch>`, and `<flux:modal>` for forms and actions.
- Sidebar integration:
    - Group: "Management"
    - Items: "Plans", "Features".

## 5. Security
- All routes protected by `auth` and `admin` middleware.
- Authorization checks in Livewire components.

## 6. Testing Strategy
- **Feature Tests**: 
    - Verify Admin can CRUD Features.
    - Verify Admin can CRUD Plans and assign Prices.
    - Verify Admin can assign Features to Plans with Quotas.
    - Verify Non-Admins are blocked from these routes.
