<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $free_plan = Plan::create([
            'name' => 'Free',
            'slug' => 'free',
            'trial_days' => 0,
            'is_active' => true,
        ]);

        $pro_plan = Plan::create([
            'name' => 'Pro',
            'slug' => 'pro',
            'trial_days' => 0,
            'is_active' => true,
        ]);

        $enterprise_plan = Plan::create([
            'name' => 'Enterprise',
            'slug' => 'enterprise',
            'trial_days' => 0,
            'is_active' => true,
        ]);

        $feature_ids = Feature::where('slug', 'projects')->first()->id;

        $free_plan->features()->attach($feature_ids, ['limit_value' => 1]);
        $pro_plan->features()->attach($feature_ids, ['limit_value' => 10]);
        $enterprise_plan->features()->attach($feature_ids, ['limit_value' => 100]);

    }
}
