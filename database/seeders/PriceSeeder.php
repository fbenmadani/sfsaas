<?php

namespace Database\Seeders;

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
            'name' => 'Free',
            'amount' => 0,
            'currency' => 'USD',
            'interval' => 'month',
            'interval_count' => 1,
            'trial_days' => 0,
            'type' => 'one_time',
            'active' => true,
            'plan_id' => 1,
        ]);
    }
}
