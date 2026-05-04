<?php

use App\Livewire\Admin\Features\Index;
use App\Models\Feature;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $this->admin = User::factory()->create(['is_admin' => true]);
    $this->user = User::factory()->create(['is_admin' => false]);
});

test('guest cannot access features page', function () {
    $this->get(route('admin.features.index'))
        ->assertRedirect(route('login'));
});

test('non-admin user cannot access features page', function () {
    $this->actingAs($this->user)
        ->get(route('admin.features.index'))
        ->assertForbidden();
});

test('admin can access features page', function () {
    $this->actingAs($this->admin)
        ->get(route('admin.features.index'))
        ->assertOk();
});

test('admin can create a feature', function () {
    $this->actingAs($this->admin);

    Livewire::test(Index::class)
        ->set('name', 'Test Feature')
        ->set('slug', 'test-feature')
        ->set('type', 'boolean')
        ->call('save')
        ->assertHasNoErrors()
        ->assertDispatched('feature-saved');

    expect(Feature::where('slug', 'test-feature')->exists())->toBeTrue();
});

test('admin can edit a feature', function () {
    $this->actingAs($this->admin);
    $feature = Feature::factory()->create(['name' => 'Original Name', 'slug' => 'original-slug', 'type' => 'boolean']);

    Livewire::test(Index::class)
        ->call('edit', $feature->id)
        ->assertSet('name', 'Original Name')
        ->set('name', 'Updated Feature')
        ->set('slug', 'updated-slug')
        ->set('type', 'limit')
        ->call('save')
        ->assertHasNoErrors()
        ->assertDispatched('feature-saved');

    $updatedFeature = $feature->fresh();
    expect($updatedFeature->name)->toBe('Updated Feature');
    expect($updatedFeature->slug)->toBe('updated-slug');
    expect($updatedFeature->type)->toBe('limit');
});

test('admin can delete a feature', function () {
    $this->actingAs($this->admin);
    $feature = Feature::factory()->create();

    Livewire::test(Index::class)
        ->call('delete', $feature->id)
        ->assertDispatched('feature-deleted');

    expect(Feature::where('id', $feature->id)->exists())->toBeFalse();
});

test('validation works for features', function () {
    $this->actingAs($this->admin);

    Livewire::test(Index::class)
        ->set('name', '')
        ->set('slug', '')
        ->call('save')
        ->assertHasErrors(['name' => 'required', 'slug' => 'required']);
});

test('slug must be unique', function () {
    $this->actingAs($this->admin);
    Feature::factory()->create(['slug' => 'existing-slug']);

    Livewire::test(Index::class)
        ->set('name', 'New Feature')
        ->set('slug', 'existing-slug')
        ->call('save')
        ->assertHasErrors(['slug' => 'unique']);
});

test('slug unique validation ignores current feature during edit', function () {
    $this->actingAs($this->admin);
    $feature = Feature::factory()->create(['slug' => 'existing-slug']);

    Livewire::test(Index::class)
        ->call('edit', $feature->id)
        ->set('name', 'Updated Name')
        ->call('save')
        ->assertHasNoErrors();

    expect($feature->fresh()->name)->toBe('Updated Name');
});
