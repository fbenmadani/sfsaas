<?php

use App\Models\Feature;
use App\Models\Plan;
use App\Models\Price;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $this->admin = User::factory()->create(['is_admin' => true]);
    $this->user = User::factory()->create(['is_admin' => false]);
});

// --- Access Control ---

test('guest cannot access plans page', function () {
    $this->get(route('admin.plans.index'))
        ->assertRedirect(route('login'));
});

test('non-admin user cannot access plans page', function () {
    $this->actingAs($this->user)
        ->get(route('admin.plans.index'))
        ->assertForbidden();
});

test('admin can access plans page', function () {
    $this->actingAs($this->admin)
        ->get(route('admin.plans.index'))
        ->assertOk();
});

// --- Listing ---

test('plans are listed with counts', function () {
    $this->actingAs($this->admin);
    $plan = Plan::factory()->create(['name' => 'Gold Plan']);
    Price::factory()->count(2)->create(['plan_id' => $plan->id]);
    $feature = Feature::factory()->create();
    $plan->features()->attach($feature->id, ['limit_value' => 10]);

    Livewire::test('admin.plans.index')
        ->assertSee('Gold Plan')
        ->assertSee('2')
        ->assertSee('1');
});

// --- Sorting ---

test('sorting plans works', function () {
    $this->actingAs($this->admin);
    Plan::factory()->create(['name' => 'B Plan']);
    Plan::factory()->create(['name' => 'A Plan']);

    Livewire::test('admin.plans.index')
        ->set('sortBy', 'name')
        ->set('sortDirection', 'asc')
        ->assertSeeInOrder(['A Plan', 'B Plan'])
        ->set('sortDirection', 'desc')
        ->assertSeeInOrder(['B Plan', 'A Plan']);
});

// --- Create ---

test('admin can create a plan', function () {
    $this->actingAs($this->admin);

    Livewire::test('admin.plans.index')
        ->set('name', 'Starter Plan')
        ->set('slug', 'starter')
        ->set('description', 'A starter plan')
        ->set('is_active', true)
        ->call('savePlan')
        ->assertHasNoErrors()
        ->assertDispatched('plan-saved');

    expect(Plan::where('slug', 'starter')->exists())->toBeTrue();
    expect(Plan::where('slug', 'starter')->first()->is_active)->toBeTrue();
});

test('admin can create a plan through the modal', function () {
    $this->actingAs($this->admin);

    Livewire::test('admin.plans.index')
        ->call('createNewPlan')
        ->assertSet('showingCreateModal', true)
        ->set('name', 'Modal Plan')
        ->set('slug', 'modal-plan')
        ->set('description', 'A plan created via modal')
        ->set('trial_days', 7)
        ->set('is_active', true)
        ->call('savePlan')
        ->assertHasNoErrors()
        ->assertSet('showingCreateModal', false)
        ->assertDispatched('plan-saved');

    expect(Plan::where('slug', 'modal-plan')->exists())->toBeTrue();
    expect(Plan::where('slug', 'modal-plan')->first()->is_active)->toBeTrue();
    expect(Plan::where('slug', 'modal-plan')->first()->trial_days)->toBe(7);
});

test('plan creation validates required fields', function () {
    $this->actingAs($this->admin);

    Livewire::test('admin.plans.index')
        ->set('name', '')
        ->set('slug', '')
        ->call('savePlan')
        ->assertHasErrors(['name' => 'required', 'slug' => 'required']);
});

test('plan slug must be unique', function () {
    $this->actingAs($this->admin);
    Plan::factory()->create(['slug' => 'existing-slug']);

    Livewire::test('admin.plans.index')
        ->set('name', 'New Plan')
        ->set('slug', 'existing-slug')
        ->call('savePlan')
        ->assertHasErrors(['slug' => 'unique']);
});

// --- Edit ---

test('admin can edit a plan', function () {
    $this->actingAs($this->admin);
    $plan = Plan::factory()->create(['name' => 'Original', 'slug' => 'original']);

    Livewire::test('admin.plans.index')
        ->call('editPlan', $plan->id)
        ->assertSet('name', 'Original')
        ->set('name', 'Updated Plan')
        ->set('slug', 'updated-plan')
        ->call('savePlan')
        ->assertHasNoErrors()
        ->assertDispatched('plan-saved');

    $updated = $plan->fresh();
    expect($updated->name)->toBe('Updated Plan');
    expect($updated->slug)->toBe('updated-plan');
});

test('slug unique validation ignores current plan during edit', function () {
    $this->actingAs($this->admin);
    $plan = Plan::factory()->create(['slug' => 'my-slug']);

    Livewire::test('admin.plans.index')
        ->call('editPlan', $plan->id)
        ->set('name', 'Renamed Plan')
        ->call('savePlan')
        ->assertHasNoErrors();

    expect($plan->fresh()->name)->toBe('Renamed Plan');
});

// --- Delete ---

test('admin can delete a plan', function () {
    $this->actingAs($this->admin);
    $plan = Plan::factory()->create();

    Livewire::test('admin.plans.index')
        ->call('deletePlan', $plan->id)
        ->assertDispatched('plan-deleted');

    expect(Plan::where('id', $plan->id)->exists())->toBeFalse();
});

// --- Toggle Active ---

test('admin can toggle plan active status', function () {
    $this->actingAs($this->admin);
    $plan = Plan::factory()->create(['is_active' => true]);

    Livewire::test('admin.plans.index')
        ->call('toggleActive', $plan->id);

    expect($plan->fresh()->is_active)->toBeFalse();

    Livewire::test('admin.plans.index')
        ->call('toggleActive', $plan->id);

    expect($plan->fresh()->is_active)->toBeTrue();
});

// --- Feature Management ---

test('admin can attach features to a plan', function () {
    $this->actingAs($this->admin);
    $plan = Plan::factory()->create();
    $feature = Feature::factory()->create(['type' => 'boolean']);

    Livewire::test('admin.plans.index')
        ->call('manageFeatures', $plan->id)
        ->assertSet('managingFeaturesPlan.id', $plan->id)
        ->call('toggleFeature', $feature->id)
        ->call('saveFeatures')
        ->assertDispatched('features-updated');

    expect($plan->fresh()->features)->toHaveCount(1);
    expect($plan->fresh()->features->first()->id)->toBe($feature->id);
});

test('admin can attach a limit feature with limit value', function () {
    $this->actingAs($this->admin);
    $plan = Plan::factory()->create();
    $feature = Feature::factory()->create(['type' => 'limit']);

    Livewire::test('admin.plans.index')
        ->call('manageFeatures', $plan->id)
        ->call('toggleFeature', $feature->id)
        ->set("selectedFeatures.{$feature->id}", 500)
        ->call('saveFeatures')
        ->assertDispatched('features-updated');

    $attached = $plan->fresh()->features->first();
    expect($attached)->not->toBeNull();
    expect($attached->pivot->limit_value)->toBe(500);
});

test('admin can detach features from a plan', function () {
    $this->actingAs($this->admin);
    $plan = Plan::factory()->create();
    $feature = Feature::factory()->create();
    $plan->features()->attach($feature->id, ['limit_value' => 100]);

    Livewire::test('admin.plans.index')
        ->call('manageFeatures', $plan->id)
        // ->not->toBeEmpty('selectedFeatures')
        ->call('toggleFeature', $feature->id)
        ->call('saveFeatures')
        ->assertDispatched('features-updated');

    expect($plan->fresh()->features)->toHaveCount(0);
});

test('manage features loads existing associations', function () {
    $this->actingAs($this->admin);
    $plan = Plan::factory()->create();
    $feature = Feature::factory()->create(['type' => 'limit']);
    $plan->features()->attach($feature->id, ['limit_value' => 250]);

    $component = Livewire::test('admin.plans.index')
        ->call('manageFeatures', $plan->id);

    expect($component->get('selectedFeatures'))->toHaveKey($feature->id);
    expect($component->get("selectedFeatures.{$feature->id}"))->toBe(250);
});
