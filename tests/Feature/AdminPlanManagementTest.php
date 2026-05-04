<?php

use App\Models\Feature;
use App\Models\Plan;
use App\Models\Price;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('feature model has type attribute', function () {
    $feature = Feature::create([
        'name' => 'Test Feature',
        'slug' => 'test-feature',
        'type' => 'limit',
    ]);

    expect($feature->type)->toBe('limit');
});

test('price model has correct fillable attributes', function () {
    $plan = Plan::create(['name' => 'Pro Plan', 'slug' => 'pro-plan']);

    $price = Price::create([
        'plan_id' => $plan->id,
        'amount' => 1000,
        'currency' => 'USD',
        'billing_interval' => 'month',
    ]);

    expect($price->plan_id)->toBe($plan->id)
        ->and($price->amount)->toBe(1000)
        ->and($price->currency)->toBe('USD')
        ->and($price->billing_interval)->toBe('month');
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
