<div>
    @if($plans->isEmpty())
        <p class="text-center py-8 text-zinc-500">No pricing plans available.</p>
    @else
        <div class="mt-16 grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($plans as $plan)
                @php
                    $price = $plan->prices->first();
                @endphp
                @if($price)
                    {{-- Starter/Pro Plan Style --}}
                    <div class="flex flex-col justify-between rounded-3xl bg-white p-8 ring-1 ring-zinc-200 xl:p-10 shadow-sm hover:shadow-md transition-all border border-transparent hover:border-brand-accent/30 @if($loop->iteration === 2) relative flex-col justify-between rounded-3xl bg-white p-8 ring-1 ring-brand-secondary/30 xl:p-10 shadow-2xl scale-105 z-10 border-2 border-brand-secondary @endif">
                        <div>
                            <div class="flex items-center justify-between gap-x-4">
                                @if($loop->iteration === 2)
                                    <div class="absolute -top-4 left-1/2 -translate-x-1/2">
                                        <flux:badge class="bg-brand-secondary text-white border-transparent">Most Popular</flux:badge>
                                    </div>
                                @endif
                                <flux:heading level="3" class="text-lg font-semibold leading-8 text-zinc-900">{{ $plan->name }}</flux:heading>
                            </div>
                            <p class="mt-4 text-sm leading-6 text-zinc-500">{{ $plan->description }}</p>
                            <p class="mt-6 flex items-baseline gap-x-1">
                                <span class="text-4xl font-bold tracking-tight text-zinc-900 @if($loop->iteration === 2) text-brand-secondary @endif">{{ $price->amount }}</span>
                                <span class="text-sm font-semibold leading-6 text-zinc-500">/{{ $price->billing_interval }}</span>
                            </p>
                            <ul role="list" class="mt-8 space-y-3 text-sm leading-6 text-zinc-600">
                                @foreach($plan->features as $feature)
                                    <li class="flex gap-x-3">
                                        <flux:icon icon="check" class="h-6 w-5 flex-none text-brand-secondary" />
                                        {{ $feature->name }} ({{ $feature->pivot->limit_value }})
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <flux:button href="/register" variant="@if($loop->iteration === 2) primary @else ghost @endif" class="mt-8 text-brand-secondary @if($loop->iteration === 2) bg-brand-secondary @endif">
                            Choose {{ $plan->name }}
                        </flux:button>
                    </div>
                @else
                    {{-- Enterprise Plan Style --}}
                    <div class="flex flex-col justify-between rounded-3xl bg-white p-8 ring-1 ring-zinc-200 xl:p-10 shadow-sm hover:shadow-md transition-all border border-transparent hover:border-brand-accent/30">
                        <div>
                            <div class="flex items-center justify-between gap-x-4">
                                <flux:heading level="3" class="text-lg font-semibold leading-8 text-zinc-900">{{ $plan->name }}</flux:heading>
                            </div>
                            <p class="mt-4 text-sm leading-6 text-zinc-500">{{ $plan->description }}</p>
                            <p class="mt-6 flex items-baseline gap-x-1">
                                <span class="text-4xl font-bold tracking-tight text-zinc-900">Custom</span>
                            </p>
                            <ul role="list" class="mt-8 space-y-3 text-sm leading-6 text-zinc-600">
                                @foreach($plan->features as $feature)
                                    <li class="flex gap-x-3">
                                        <flux:icon icon="check" class="h-6 w-5 flex-none text-brand-secondary" />
                                        {{ $feature->name }} ({{ $feature->pivot->limit_value }})
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <flux:button href="/about" variant="ghost" class="mt-8 text-brand-secondary">Contact Sales</flux:button>
                    </div>
                @endif
            @endforeach
        </div>
    @endif
</div>