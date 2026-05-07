<?php

use App\Models\Domain;
use App\Models\Tenant;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $this->admin = User::factory()->create(['is_admin' => true]);
    $this->user = User::factory()->create(['is_admin' => false]);
    $this->tenant = Tenant::create(['id' => 'test-tenant-'.uniqid()]);
});

// --- Access Control ---

test('guest cannot access domains page', function () {
    $this->get(route('admin.domains.index'))
        ->assertRedirect(route('login'));
});

test('non-admin user cannot access domains page', function () {
    $this->actingAs($this->user)
        ->get(route('admin.domains.index'))
        ->assertForbidden();
});

test('admin can access domains page', function () {
    $this->actingAs($this->admin)
        ->get(route('admin.domains.index'))
        ->assertOk();
});

// --- Listing ---

test('domains are listed', function () {
    $this->actingAs($this->admin);
    Domain::create([
        'domain' => 'test.example.com',
        'tenant_id' => $this->tenant->id,
        'type' => Domain::TYPE_SUBDOMAIN,
        'is_primary' => true,
    ]);

    Livewire::test('admin.domains.index')
        ->assertSee('test.example.com');
});

test('domains can be filtered by type', function () {
    $this->actingAs($this->admin);
    Domain::create([
        'domain' => 'sub.example.com',
        'tenant_id' => $this->tenant->id,
        'type' => Domain::TYPE_SUBDOMAIN,
    ]);
    Domain::create([
        'domain' => 'custom.com',
        'tenant_id' => $this->tenant->id,
        'type' => Domain::TYPE_TLD,
    ]);

    $component = Livewire::test('admin.domains.index')
        ->set('typeFilter', 'subdomain');

    $component->assertSee('sub.example.com');
    $component->assertDontSee('custom.com');
});

test('domains can be searched', function () {
    $this->actingAs($this->admin);
    Domain::create([
        'domain' => 'myapp.example.com',
        'tenant_id' => $this->tenant->id,
        'type' => Domain::TYPE_SUBDOMAIN,
    ]);
    Domain::create([
        'domain' => 'other.example.com',
        'tenant_id' => $this->tenant->id,
        'type' => Domain::TYPE_SUBDOMAIN,
    ]);

    Livewire::test('admin.domains.index')
        ->set('search', 'myapp')
        ->assertSee('myapp.example.com')
        ->assertDontSee('other.example.com');
});

// --- Sorting ---

test('domains can be sorted by domain name', function () {
    $this->actingAs($this->admin);
    Domain::create([
        'domain' => 'b-domain.example.com',
        'tenant_id' => $this->tenant->id,
        'type' => Domain::TYPE_SUBDOMAIN,
    ]);
    Domain::create([
        'domain' => 'a-domain.example.com',
        'tenant_id' => $this->tenant->id,
        'type' => Domain::TYPE_SUBDOMAIN,
    ]);

    Livewire::test('admin.domains.index')
        ->set('sortBy', 'domain')
        ->set('sortDirection', 'asc')
        ->assertSeeInOrder(['a-domain.example.com', 'b-domain.example.com']);
});

// --- Create ---

test('admin can access create domain page', function () {
    $this->actingAs($this->admin)
        ->get(route('admin.domains.create'))
        ->assertOk();
});

test('admin can create a subdomain', function () {
    $this->actingAs($this->admin);

    Livewire::test('admin.domains.create')
        ->set('domain', 'myapp')
        ->set('tenant_id', $this->tenant->id)
        ->set('type', 'subdomain')
        ->set('is_primary', false)
        ->call('save')
        ->assertHasNoErrors();

    expect(Domain::where('domain', 'myapp')->exists())->toBeTrue();
    expect(Domain::where('domain', 'myapp')->first()->type)->toBe('subdomain');
});

test('admin can create a tld domain', function () {
    $this->actingAs($this->admin);

    Livewire::test('admin.domains.create')
        ->set('domain', 'myapp.com')
        ->set('tenant_id', $this->tenant->id)
        ->set('type', 'tld')
        ->set('is_primary', true)
        ->call('save')
        ->assertHasNoErrors();

    expect(Domain::where('domain', 'myapp.com')->exists())->toBeTrue();
    expect(Domain::where('domain', 'myapp.com')->first()->is_primary)->toBeTrue();
});

test('domain creation validates required fields', function () {
    $this->actingAs($this->admin);

    Livewire::test('admin.domains.create')
        ->set('domain', '')
        ->set('tenant_id', '')
        ->call('save')
        ->assertHasErrors(['domain' => 'required', 'tenant_id' => 'required']);
});

test('domain must be unique', function () {
    $this->actingAs($this->admin);
    Domain::create([
        'domain' => 'existing.com',
        'tenant_id' => $this->tenant->id,
        'type' => Domain::TYPE_TLD,
    ]);

    Livewire::test('admin.domains.create')
        ->set('domain', 'existing.com')
        ->set('tenant_id', $this->tenant->id)
        ->set('type', 'tld')
        ->call('save')
        ->assertHasErrors(['domain' => 'unique']);
});

// --- Edit ---

test('admin can access edit domain page', function () {
    $this->actingAs($this->admin);
    $domain = Domain::create([
        'domain' => 'edit-test.example.com',
        'tenant_id' => $this->tenant->id,
        'type' => Domain::TYPE_SUBDOMAIN,
    ]);

    $this->get(route('admin.domains.edit', $domain))
        ->assertOk();
});

test('admin can edit a domain', function () {
    $this->actingAs($this->admin);
    $domain = Domain::create([
        'domain' => 'old.example.com',
        'tenant_id' => $this->tenant->id,
        'type' => Domain::TYPE_SUBDOMAIN,
    ]);

    Livewire::test('admin.domains.edit', ['domain' => $domain->id])
        ->assertSet('domain_name', 'old.example.com')
        ->set('domain_name', 'new.example.com')
        ->set('type', 'tld')
        ->call('save')
        ->assertHasNoErrors();

    expect($domain->fresh()->domain)->toBe('new.example.com');
    expect($domain->fresh()->type)->toBe('tld');
});

test('edit domain validates required fields', function () {
    $this->actingAs($this->admin);
    $domain = Domain::create([
        'domain' => 'test.example.com',
        'tenant_id' => $this->tenant->id,
        'type' => Domain::TYPE_SUBDOMAIN,
    ]);

    Livewire::test('admin.domains.edit', ['domain' => (string) $domain->id])
        ->set('domain_name', '')
        ->call('save')
        ->assertHasErrors(['domain_name' => 'required']);
});

// --- Delete ---

test('admin can delete a domain', function () {
    $this->actingAs($this->admin);
    $domain = Domain::create([
        'domain' => 'delete-test.example.com',
        'tenant_id' => $this->tenant->id,
        'type' => Domain::TYPE_SUBDOMAIN,
    ]);

    Livewire::test('admin.domains.index')
        ->call('deleteDomain', $domain->id);

    expect(Domain::where('id', $domain->id)->exists())->toBeFalse();
});

// --- Primary Domain ---

test('admin can set primary domain', function () {
    $this->actingAs($this->admin);
    $domain1 = Domain::create([
        'domain' => 'primary1.example.com',
        'tenant_id' => $this->tenant->id,
        'type' => Domain::TYPE_SUBDOMAIN,
        'is_primary' => true,
    ]);
    $domain2 = Domain::create([
        'domain' => 'primary2.example.com',
        'tenant_id' => $this->tenant->id,
        'type' => Domain::TYPE_SUBDOMAIN,
        'is_primary' => false,
    ]);

    Livewire::test('admin.domains.index')
        ->call('setPrimary', $domain2->id);

    expect($domain1->fresh()->is_primary)->toBeFalse();
    expect($domain2->fresh()->is_primary)->toBeTrue();
});

test('setting primary domain unsets others for same tenant', function () {
    $this->actingAs($this->admin);
    $domain1 = Domain::create([
        'domain' => 'old-primary.example.com',
        'tenant_id' => $this->tenant->id,
        'type' => Domain::TYPE_SUBDOMAIN,
        'is_primary' => true,
    ]);
    $domain2 = Domain::create([
        'domain' => 'new-primary.example.com',
        'tenant_id' => $this->tenant->id,
        'type' => Domain::TYPE_SUBDOMAIN,
        'is_primary' => false,
    ]);

    Livewire::test('admin.domains.index')
        ->call('setPrimary', $domain2->id);

    expect(Domain::where('tenant_id', $this->tenant->id)->where('is_primary', true)->count())->toBe(1);
});
