<?php

namespace App\Service;

use App\Models\Subscription;
use App\Models\Tenant;

class BillingService
{
    public function getMetrics(): array
    {
        $activeSubscriptions = Subscription::active()->with('price')->get();

        $totalMrr = $activeSubscriptions->sum(function ($subscription) {
            $price = $subscription->price;
            if ($price->billing_interval === 'year') {
                // Normalize yearly price to 1 month
                return $price->amount / 12;
            }

            return $price->amount;
        });

        return [
            'mrr' => $totalMrr,
            'arr' => $totalMrr * 12,
            'customer_count' => $activeSubscriptions->count(),
            'tenant_count' => Tenant::count(),
        ];
    }
}
