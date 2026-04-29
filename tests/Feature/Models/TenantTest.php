<?php

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

afterEach(function() {
    Tenant::all()->each->delete();
});

it('can create a tenant', function () {
    $id = Str::random(10);
    $tenant = Tenant::create(['id' => $id]);

    expect($tenant)->toBeInstanceOf(Tenant::class)
        ->and($tenant->id)->toBe($id);
});

it('has many users', function () {
    $id = Str::random(10);
    $tenant = Tenant::create(['id' => $id]);

    expect($tenant->users())->toBeInstanceOf(HasMany::class);

    $user = User::factory()->create(['tenant_id' => $tenant->id]);

    expect($tenant->users)->toHaveCount(1)
        ->and($tenant->users->first()->id)->toBe($user->id);
});
