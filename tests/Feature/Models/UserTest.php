<?php

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

afterEach(function() {
    Tenant::all()->each->delete();
});

it('calculates the correct initials', function () {
    $user = User::factory()->make(['name' => 'John Doe']);
    expect($user->initials())->toBe('JD');

    $user = User::factory()->make(['name' => 'Alice']);
    expect($user->initials())->toBe('A');

    $user = User::factory()->make(['name' => 'Mary Jane Watson']);
    expect($user->initials())->toBe('MJ');
});

it('belongs to a tenant', function () {
    $tenantId = Str::random(10);
    $tenant = Tenant::create(['id' => $tenantId]);
    
    $user = User::factory()->create(['tenant_id' => $tenant->id]);

    expect($user->tenant())->toBeInstanceOf(BelongsTo::class)
        ->and($user->tenant->id)->toBe($tenant->id);
});

it('correctly casts attributes', function () {
    $user = User::factory()->make([
        'is_admin' => 1,
        'tenant_id' => 12345,
    ]);

    expect($user->is_admin)->toBeTrue()
        ->and($user->tenant_id)->toBeString()->toBe('12345');
});
