<?php

use Livewire\Component;
use App\Models\Plan;
use Livewire\Attributes\Computed;
new class extends Component
{
    //
    public $period = 'month';


    #[Computed]
    public function plans()
    {
        return Plan::with(['prices' => function ($query) {
            $query->where('is_active', true);
            }, 'features'])->get();
    }

};
?>

<div>
  <div class="py-12">
    <div class="flex justify-center items-center space-x-4 mb-8">
        <span class="text-sm" :class="!$wire.period == 'month' ? 'text-gray-500' : 'font-bold'">Monthly</span>
        <button 
            type="button" 
            wire:click="$set('period', '{{ $period === 'month' ? 'year' : 'month' }}')"
            class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none bg-indigo-600"
        >
            <span class="translate-x-{{ $period === 'year' ? '5' : '0' }} inline-block h-5 w-5 transform rounded-full bg-white shadow transition duration-200 ease-in-out"></span>
        </button>
        <span class="text-sm" :class="$wire.period == 'year' ? 'font-bold' : 'text-gray-500'">
            Yearly <span class="text-green-500 font-medium">(Save 20%)</span>
        </span>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-7xl mx-auto px-4">
        @foreach($this->plans as $plan)
            @php

            
                $currentPrice = $plan->prices->where('billing_interval', $period)->first();
            @endphp

            <div class="border rounded-lg p-6 shadow-sm bg-white flex flex-col">
                <h3 class="text-xl font-bold">{{ $plan->name }}</h3>
                <div class="mt-4">
                    <span class="text-4xl font-extrabold">${{ $currentPrice->amount }}</span>
                    <span class="text-gray-500">/{{ $period }}</span>
                </div>

                <ul class="mt-6 space-y-4 flex-grow">
                    @foreach($plan->features as $feature)
                        <li class="flex items-center text-sm text-gray-600">
                            <svg class="h-5 w-5 text-green-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            {{ $feature->name }}: 
                            <strong>{{ $feature->pivot->limit_value ?? 'Unlimited' }}</strong>
                        </li>
                    @endforeach
                </ul>

                <button class="mt-8 w-full py-2 bg-indigo-600 text-white rounded-md font-semibold hover:bg-indigo-700 transition">
                    {{ $plan->trial_days > 0 ? "Start {$plan->trial_days} Day Trial" : 'Subscribe Now' }}
                </button>
            </div>
        @endforeach
    </div>
</div>
</div>