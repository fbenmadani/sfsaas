<?php

namespace App\Http\Livewire;

use App\Models\Plan;
use Livewire\Component;

class PricingList extends Component
{
    public $plans;

    public function mount()
    {
        $this->plans = Plan::with('prices')->where('is_active', true)->get();
    }

    public function render()
    {
        return view('livewire.pricing-list');
    }
}
