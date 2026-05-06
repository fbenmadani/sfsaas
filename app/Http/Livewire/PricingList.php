<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Plan;

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