<?php

namespace Database\Seeders;

use App\Models\Plan;
use App\Models\Price;
use Illuminate\Database\Seeder;

class PriceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $price = Price::create([

            'amount' => 0,
            'currency' => 'USD',
            'billing_interval' => 'month',

            'plan_id' => 1,

        ]);

        //
        $price2 = Price::create([

            'amount' => 10,
            'currency' => 'USD',
            'billing_interval' => 'month',

            'plan_id' => 2,

        ]);

        $plan = Plan::find(1);
        $plan->prices()->save($price);
        // $plan->prices()->save($price);

        $plan2 = Plan::find(2);
        $plan2->prices()->save($price2);

        // $plan = Plan::find(3);
        // $plan->prices()->save($price);
    }
}
