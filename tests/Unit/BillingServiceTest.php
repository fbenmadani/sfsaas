<?php

use App\Models\Price;
use App\Models\Subscription;
use App\Models\Tenant;
use App\Service\BillingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

it('calculates metrics correctly', function () {
    $tenant1 = Tenant::factory()->create();
    $tenant2 = Tenant::factory()->create();

    $monthlyPrice = Price::factory()->create([
        'billing_interval' => 'month',
        'amount' => 100,
    ]);

    $yearlyPrice = Price::factory()->create([
        'billing_interval' => 'year',
        'amount' => 1200, // 100 per month
    ]);

    Subscription::factory()->create([
        'tenant_id' => $tenant1->id,
        'price_id' => $monthlyPrice->id,
        'status' => 'active',
    ]);

    Subscription::factory()->create([
        'tenant_id' => $tenant2->id,
        'price_id' => $yearlyPrice->id,
        'status' => 'active',
    ]);

    // A canceled subscription for tenant1 should not be counted
    Subscription::factory()->create([
        'tenant_id' => $tenant1->id,
        'price_id' => $monthlyPrice->id,
        'status' => 'canceled',
    ]);

    $service = new BillingService;
    $metrics = $service->getMetrics();

    expect($metrics['mrr'])->toEqual(200); // 100 + (1200/12)
    expect($metrics['arr'])->toEqual(2400); // 200 * 12
    expect($metrics['customer_count'])->toEqual(2);
    expect($metrics['tenant_count'])->toEqual(2);
});
