<?php

use App\Models\Price;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Tests\TestCase;

uses(TestCase::class);

it('has the correct fillable properties', function () {
    $price = new Price;

    expect($price->getFillable())->toBe(['plan_id', 'amount', 'currency', 'billing_interval', 'is_yearly']);
});

it('has plan relationship', function () {
    $price = new Price;

    expect(method_exists($price, 'plan'))->toBeTrue();
    expect($price->plan())->toBeInstanceOf(BelongsTo::class);
});
