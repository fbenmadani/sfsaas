<?php

use App\Models\Plan;
use App\Models\Price;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

it('has the correct fillable properties', function () {
    $price = new Price;

    expect($price->getFillable())->toBe(['plan_id', 'amount', 'currency', 'billing_interval', 'is_yearly']);
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

it('has plan relationship', function () {
    $price = new Price;

    expect(method_exists($price, 'plan'))->toBeTrue();
    expect($price->plan())->toBeInstanceOf(BelongsTo::class);
});
