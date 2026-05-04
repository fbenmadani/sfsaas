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

test('plan model has relationships', function () {
    $plan = Plan::create(['name' => 'Pro Plan', 'slug' => 'pro-plan']);
    $feature = Feature::create(['name' => 'Test Feature', 'slug' => 'test-feature', 'type' => 'boolean']);

    $plan->features()->attach($feature, ['limit_value' => 10]);

    $price = Price::create([
        'plan_id' => $plan->id,
        'amount' => 1000,
        'currency' => 'USD',
        'billing_interval' => 'month',
    ]);

    expect($plan->features)->toHaveCount(1)
        ->and($plan->features->first()->pivot->limit_value)->toBe(10)
        ->and($plan->prices)->toHaveCount(1);
});
