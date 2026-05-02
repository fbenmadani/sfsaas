# Admin Plan Management Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Implement a comprehensive Admin interface to manage Plans, Prices, and Features with Quota support.

**Architecture:** Use Plan-centric management (Option A). Standard class-based Livewire components with Flux UI.

**Tech Stack:** Laravel 13, Livewire 4, Flux UI, SQLite.

---

### Task 1: Database Schema & Model Updates

**Files:**
- Modify: `database/migrations/2026_05_01_174830_add_type_to_features_table.php`
- Modify: `app/Models/Feature.php`
- Modify: `app/Models/Price.php`
- Modify: `app/Models/Plan.php`

- [ ] **Step 1: Update migration for features table**

```php
// database/migrations/2026_05_01_174830_add_type_to_features_table.php
public function up(): void
{
    Schema::table('features', function (Blueprint $table) {
        $table->string('type')->default('boolean'); // 'boolean' or 'limit'
    });
}
```

- [ ] **Step 2: Run migration**

Run: `php artisan migrate`

- [ ] **Step 3: Update Feature Model**

```php
// app/Models/Feature.php
protected $fillable = ['name', 'slug', 'type'];
```

- [ ] **Step 4: Update Price Model**

Remove `is_yearly` from fillable as it's not in migration, or add it to migration. For now, let's keep it simple and stick to migration.

```php
// app/Models/Price.php
protected $fillable = ['plan_id', 'amount', 'currency', 'billing_interval'];
```

- [ ] **Step 5: Verify Plan Model relations**

Ensure `prices()` and `features()` are correctly defined.

- [ ] **Step 6: Commit**

```bash
git add database/migrations/2026_05_01_174830_add_type_to_features_table.php app/Models/Feature.php app/Models/Price.php app/Models/Plan.php
git commit -m "database: add type to features and update models"
```

---

### Task 2: Admin Feature Management (CRUD)

**Files:**
- Create: `app/Livewire/Admin/Features/Index.php`
- Create: `resources/views/livewire/admin/features/index.blade.php`

- [ ] **Step 1: Create Feature Index component**

```php
// app/Livewire/Admin/Features/Index.php
namespace App\Livewire\Admin\Features;

use App\Models\Feature;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $name = '';
    public $slug = '';
    public $type = 'boolean';
    public $editingFeature = null;

    protected $rules = [
        'name' => 'required|string|max:255',
        'slug' => 'required|string|unique:features,slug',
        'type' => 'required|in:boolean,limit',
    ];

    public function save()
    {
        if ($this->editingFeature) {
            $this->validate([
                'name' => 'required|string|max:255',
                'slug' => 'required|string|unique:features,slug,' . $this->editingFeature->id,
                'type' => 'required|in:boolean,limit',
            ]);
            $this->editingFeature->update([
                'name' => $this->name,
                'slug' => $this->slug,
                'type' => $this->type,
            ]);
        } else {
            $this->validate();
            Feature::create([
                'name' => $this->name,
                'slug' => $this->slug,
                'type' => $this->type,
            ]);
        }
        $this->reset(['name', 'slug', 'type', 'editingFeature']);
    }

    public function edit(Feature $feature)
    {
        $this->editingFeature = $feature;
        $this->name = $feature->name;
        $this->slug = $feature->slug;
        $this->type = $feature->type;
    }

    public function delete(Feature $feature)
    {
        $feature->delete();
    }

    public function render()
    {
        return view('livewire.admin.features.index', [
            'features' => Feature::paginate(10),
        ]);
    }
}
```

- [ ] **Step 2: Create Feature Index view**

Use Flux Table and Modal.

- [ ] **Step 3: Test Feature CRUD manually or via Pest**

- [ ] **Step 4: Commit**

---

### Task 3: Admin Plan Management - List

**Files:**
- Create: `app/Livewire/Admin/Plans/Index.php`
- Create: `resources/views/livewire/admin/plans/index.blade.php`

- [ ] **Step 1: Create Plan Index component**

List plans with Price count and Status toggle.

- [ ] **Step 2: Create Plan Index view**

Use Flux Table.

- [ ] **Step 3: Commit**

---

### Task 4: Admin Plan Management - Edit (General & Prices)

**Files:**
- Create: `app/Livewire/Admin/Plans/Edit.php`
- Create: `resources/views/livewire/admin/plans/edit.blade.php`

- [ ] **Step 1: Create Plan Edit component**

Handle basic Plan info and Price management (Modals for prices).

- [ ] **Step 2: Create Plan Edit view**

Use Flux Tabs or distinct sections.

- [ ] **Step 3: Commit**

---

### Task 5: Admin Plan Management - Edit (Features & Quotas)

**Files:**
- Modify: `app/Livewire/Admin/Plans/Edit.php`
- Modify: `resources/views/livewire/admin/plans/edit.blade.php`

- [ ] **Step 1: Implement Feature assignment logic**

Fetch all global features. For each, show a switch. If enabled and type is 'limit', show numeric input.
Sync to `plan_feature` pivot table.

- [ ] **Step 2: Commit**

---

### Task 6: Navigation & Sidebar

**Files:**
- Modify: `routes/web.php`
- Modify: `resources/views/layouts/app/sidebar.blade.php`

- [ ] **Step 1: Register routes**

```php
Route::middleware('admin')->group(function () {
    Route::get('admin/plans', \App\Livewire\Admin\Plans\Index::class)->name('admin.plans.index');
    Route::get('admin/plans/{plan}/edit', \App\Livewire\Admin\Plans\Edit::class)->name('admin.plans.edit');
    Route::get('admin/features', \App\Livewire\Admin\Features\Index::class)->name('admin.features.index');
});
```

- [ ] **Step 2: Update Sidebar**

Add links to Plans and Features.

- [ ] **Step 3: Commit**
