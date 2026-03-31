**Date:** 2026-03-31
**Status:** Draft
**Topic:** SAAS Feature

## 1. Overview
sfSaas is a complete SaaS starter kit that includes everything you need to start your SaaS business. It comes ready with a huge list of reusable components, a complete admin panel, user dashboard, user authentication, user & role management, plans & pricing, subscriptions, payments, emails, and more.


Designing a robust billing engine for a SaaS requires a balance between flexibility (for marketing) and strict data integrity (for accounting). Since you are managing subscriptions with feature-based limits and multi-tenancy, the database schema needs to be decoupled to allow for plan changes without breaking historical invoices.

Here is a conceptual model for your billing system.
## Design  
###1. Database Schema Overview
To handle upgrades, downgrades, and trials effectively, you should separate the Catalog (what you sell) from the Subscription (what the user actually has).

1. Plans Table: Stores the "Blueprints" (e.g., Starter, Pro, Enterprise).

name, slug, trial_days, is_active.

2. Prices Table: Plans should have multiple prices (Monthly vs. Yearly).

plan_id, amount, currency, billing_interval (month/year).

3. Features Table: A master list of all possible features (e.g., "Orders", "Team Members").

4. Plan_Feature Table (Pivot): Maps features to plans with their specific limits.

plan_id, feature_id, limit_value (e.g., 500 orders).

5. Subscriptions Table: Tracks the user's current state.

user_id (or tenant_id), plan_id, price_id, status (active, trialing, cancelled), trial_ends_at, cycle_ends_at.

2. Handling Key Logic
Trials and Discounts
Trials: When a user signs up, set the status to trialing and calculate trial_ends_at based on the plan's trial_days. Block payment processing until the trial expires.

Yearly % Off: This is handled at the Price level. You define a monthly price of $50 and a yearly price of $500 (which is ~17% off). The system simply subscribes the user to the yearly price ID.

Feature Limits (The "Orders" Example)
To enforce limits without hitting the database for every single request, you can use a "Usage" table or cache.

Usage Tracking: Every time an order is created, increment a usage_count for that tenant.

Validation: Before allowing an action, compare the usage_count against the limit_value defined in the Plan_Feature table for their current plan.

Upgrades and Downgrades
Proration: If a user upgrades mid-month, you must calculate the unused portion of the old plan and credit it toward the new plan.

Formula: (New Price - Old Price) * (Days Remaining / Total Days in Cycle).

Downgrade Logic: Usually, it is safer to set the downgrade to happen at the end of the current billing cycle to avoid complex refund calculations.

## 2. Data Model


1. The Eloquent Models
Here is the core structure for your billing engine:

PHP
// Plan.php - The high-level package (e.g., "Pro")
class Plan extends Model {
    protected $fillable = ['name', 'slug', 'description', 'is_active'];

    public function prices() { return $this->hasMany(Price::class); }
    public function features() { return $this->belongsToMany(Feature::class)->withPivot('limit_value'); }
}

// Price.php - The specific cost (e.g., "$50/mo" or "$500/yr")
class Price extends Model {
    protected $fillable = ['plan_id', 'amount', 'currency', 'billing_interval', 'is_yearly'];
    
    public function plan() { return $this->belongsTo(Plan::class); }
}

// Feature.php - The logic gate (e.g., "Orders")
class Feature extends Model {
    protected $fillable = ['name', 'slug', 'type']; // type: 'limit' or 'boolean'
}

// Subscription.php - The bridge to the Tenant/User
class Subscription extends Model {
    protected $fillable = ['tenant_id', 'price_id', 'status', 'trial_ends_at', 'ends_at'];

    public function price() { return $this->belongsTo(Price::class); }
    
    // Helper for MRR calculation
    public function scopeActive($query) {
        return $query->where('status', 'active');
    }
}


2. Calculating MRR & ARR via Eloquent
With this design, calculating your metrics becomes a clean collection operation. We pull the amount from the Price model to ensure we account for the yearly % off correctly.

PHP
public function getMetrics()
{
    // 1. Get all active subscriptions with their price data
    $activeSubscriptions = Subscription::active()->with('price')->get();

    $totalMrr = $activeSubscriptions->sum(function ($subscription) {
        $price = $subscription->price;

        if ($price->billing_interval === 'year') {
            // Normalize yearly price to 1 month
            return $price->amount / 12;
        }

        return $price->amount;
    });

    return [
        'mrr' => $totalMrr,
        'arr' => $totalMrr * 12,
        'customer_count' => $activeSubscriptions->count(),
    ];
}


3. Handling Feature Limits (The "Orders" Example)
To enforce the "Orders" limit you mentioned, you can create a helper method on your Tenant or User model. This checks the current plan's limit against the actual usage.

PHP
// Inside Tenant.php or User.php
public function hasReachedLimit($featureSlug)
{
    $subscription = $this->subscription()->with('price.plan.features')->first();
    
    $feature = $subscription->price->plan->features
        ->where('slug', $featureSlug)
        ->first();

    if (!$feature) return true; // Feature not in plan

    $limit = $feature->pivot->limit_value;
    
    // Assuming you have an 'orders_count' or similar tracking
    return $this->orders()->count() >= $limit;
}

### `Plan`
- `id`: primary key


## 3. Architecture

### Provider-Agnostic Bank Sync

### Logic Layers

## 4. User Interface (Livewire & Flux UI)

### Dashboard (`SaaS Admin/Dashboard`)
- **Cards**: Total Tenant, Total User. MRR, ARR, Churn Rate.
- **Charts**: Interactive suscrptions by plan (Pie/Doughnut) and monthly trends (Bar/Line) using Chart.js.
- **Recent Susciptions**: Flux UI Table with filtering and sorting.

### Management Pages
- **Plans**: cards of plans.
- **Pricing**: list of prices.
- **Features**: cards of features.

## 5. Implementation Strategy
1.  **Phase 1: Database & Models**: Migrations, Models, Factories, and basic CRUD tests (Pest).
2.  **Phase 2: Core Logic**: Actions for tenant registration, plan management, subscription management, payment management, email management.
3.  **Phase 3: Tenant Admin**  
   
4.  **Phase 4: UI Development**:Landing page,Pricing Table, Registration Page, Tenant onboarding, Dashboard components, Charts, and management forms using Flux UI.

5.  **Phase 5: Notifications**: Real-time budget alerts via Laravel Notifications.

## 6. Testing Plan
- **Unit Tests**: Tenats, Plans, Prices, Features, Subscriptions, Payments.
- **Feature Tests**: registartion workflow, plan management, subscription management, payment management, email management.
- **Browser Tests**: Interactive charts, drag-and-drop category management (if applicable).
- **Performance Tests**:     