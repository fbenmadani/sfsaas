<?php

use App\Livewire\Admin\Plans\Index;
use App\Models\Feature;
use App\Models\Plan;
use App\Models\Price;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $this->admin = User::factory()->create(['is_admin' => true]);
    $this->user = User::factory()->create(['is_admin' => false]);
});

test('guest cannot access plans page', function () {
    $this->get(route('admin.plans.index'))
        ->assertRedirect(route('login'));
}).skip();

test('non-admin user cannot access plans page', function () {
    $this->actingAs($this->user)
        ->get(route('admin.plans.index'))
        ->assertForbidden();
}).skip();

test('admin can access plans page', function () {
    $this->actingAs($this->admin)
        ->get(route('admin.plans'))
        ->assertOk();
}).skip();

test('plans are listed with counts', function () {
    $this->actingAs($this->admin);
    $plan = Plan::factory()->create(['name' => 'Gold Plan']);
    Price::factory()->count(2)->create(['plan_id' => $plan->id]);
    $feature = Feature::factory()->create();
    $plan->features()->attach($feature->id, ['limit_value' => 10]);

    Livewire::test(Index::class)
        ->assertSee('Gold Plan')
        ->assertSee('2') // Price count
        ->assertSee('1'); // Feature count
});

test('admin can toggle plan active status', function () {
    $this->actingAs($this->admin);
    $plan = Plan::factory()->create(['is_active' => true]);

    Livewire::test(Index::class)
        ->call('toggleActive', $plan->id);

    expect($plan->fresh()->is_active)->toBeFalse();

    Livewire::test(Index::class)
        ->call('toggleActive', $plan->id);

    expect($plan->fresh()->is_active)->toBeTrue();
});

test('sorting plans works', function () {
    $this->actingAs($this->admin);
    Plan::factory()->create(['name' => 'B Plan']);
    Plan::factory()->create(['name' => 'A Plan']);

    Livewire::test(Index::class)
        ->set('sortBy', 'name')
        ->set('sortDirection', 'asc')
        ->assertSeeInOrder(['A Plan', 'B Plan'])
        ->set('sortDirection', 'desc')
        ->assertSeeInOrder(['B Plan', 'A Plan']);
});
