<?php

use App\Models\Plan;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Tests\TestCase;

uses(TestCase::class);

it('has the correct fillable properties', function () {
    $plan = new Plan;

    expect($plan->getFillable())->toBe(['name', 'slug', 'description', 'is_active']);
});

it('has prices relationship', function () {
    $plan = new Plan;

    expect(method_exists($plan, 'prices'))->toBeTrue();
    expect($plan->prices())->toBeInstanceOf(HasMany::class);
});

it('has features relationship', function () {
    $plan = new Plan;

    expect(method_exists($plan, 'features'))->toBeTrue();
    expect($plan->features())->toBeInstanceOf(BelongsToMany::class);
});
