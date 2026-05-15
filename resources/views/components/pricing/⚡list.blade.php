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
        return Plan::where('is_active', true)->with(['prices', 'features'])->get();
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

            <flux:card class="flex flex-col h-full">
                <div class="flex-grow">
                    <flux:heading size="xl" class="mb-4">{{ $plan->name }}</flux:heading>
                    
                    @php
                        $activePrice = $plan->prices->where('billing_interval', $period)->first();
                    @endphp

                    <div class="mt-4 flex items-baseline text-zinc-900 dark:text-white">
                        @if($activePrice)
                            <span class="text-4xl font-extrabold tracking-tight">
                                {{ strtoupper($activePrice->currency) === 'USD' ? '$' : '' }}{{ $activePrice->amount }}
                            </span>
                            <span class="text-zinc-500 ml-1 text-sm font-semibold">/{{ $period }}</span>
                        @else
                            <span class="text-4xl font-extrabold tracking-tight">N/A</span>
                        @endif
                    </div>

                    <flux:separator class="my-6" />

                    <ul class="space-y-4">
                        @foreach($plan->features as $feature)
                            <li class="flex items-center text-sm text-zinc-600 dark:text-zinc-400">
                                <flux:icon.check class="size-5 text-emerald-500 mr-3" />
                                <span>{{ $feature->name }}: <strong class="font-semibold text-zinc-900 dark:text-white">{{ $feature->pivot->limit_value ?? 'Unlimited' }}</strong></span>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <flux:button variant="primary" class="mt-8 w-full">
                    {{ $plan->trial_days > 0 ? "Start {$plan->trial_days} Day Trial" : 'Subscribe Now' }}
                </flux:button>
            </flux:card>
        @endforeach
    </div>
</div>
</div>