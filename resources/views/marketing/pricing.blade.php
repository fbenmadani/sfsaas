<x-layouts.marketing>
    <x-slot:title>Pricing Plans</x-slot:title>

    <div class="bg-white py-24 sm:py-32">
        <div class="mx-auto max-w-7xl px-6 lg:px-8 text-center">
            <flux:heading level="1" class="text-3xl font-bold tracking-tight text-zinc-900 sm:text-6xl">Simple, Cheerful Pricing</flux:heading>
            <flux:text class="mt-6 text-zinc-600">
                Choose the plan that fits your business stage. No hidden fees, just pure growth.
            </flux:text>

            <div class="mt-16 grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
                <!-- Starter Plan -->
                <div class="flex flex-col justify-between rounded-3xl bg-white p-8 ring-1 ring-zinc-200 xl:p-10 shadow-sm hover:shadow-md transition-all border border-transparent hover:border-brand-accent/30">
                    <div>
                        <div class="flex items-center justify-between gap-x-4">
                            <flux:heading level="3" class="text-lg font-semibold leading-8 text-zinc-900">Starter</flux:heading>
                        </div>
                        <p class="mt-4 text-sm leading-6 text-zinc-500">Perfect for solo entrepreneurs ready to start.</p>
                        <p class="mt-6 flex items-baseline gap-x-1">
                            <span class="text-4xl font-bold tracking-tight text-zinc-900">$29</span>
                            <span class="text-sm font-semibold leading-6 text-zinc-500">/month</span>
                        </p>
                        <ul role="list" class="mt-8 space-y-3 text-sm leading-6 text-zinc-600">
                            <li class="flex gap-x-3">
                                <flux:icon icon="check" class="h-6 w-5 flex-none text-brand-secondary" />
                                1,000 Marketing Contacts
                            </li>
                            <li class="flex gap-x-3">
                                <flux:icon icon="check" class="h-6 w-5 flex-none text-brand-secondary" />
                                Basic CRM Pipeline
                            </li>
                            <li class="flex gap-x-3">
                                <flux:icon icon="check" class="h-6 w-5 flex-none text-brand-secondary" />
                                Friendly Email Support
                            </li>
                        </ul>
                    </div>
                    <flux:button href="/register" variant="ghost" class="mt-8 text-brand-secondary">Choose Starter</flux:button>
                </div>

                <!-- Pro Plan (Most Popular) -->
                <div class="relative flex flex-col justify-between rounded-3xl bg-white p-8 ring-1 ring-brand-secondary/30 xl:p-10 shadow-2xl scale-105 z-10 border-2 border-brand-secondary">
                    <div class="absolute -top-4 left-1/2 -translate-x-1/2">
                        <flux:badge class="bg-brand-secondary text-white border-transparent">Most Popular</flux:badge>
                    </div>
                    <div>
                        <div class="flex items-center justify-between gap-x-4">
                            <flux:heading level="3" class="text-lg font-semibold leading-8 text-zinc-900">Pro</flux:heading>
                        </div>
                        <p class="mt-4 text-sm leading-6 text-zinc-600 font-medium">Best for growing teams and innovative businesses.</p>
                        <p class="mt-6 flex items-baseline gap-x-1">
                            <span class="text-4xl font-bold tracking-tight text-brand-secondary">$99</span>
                            <span class="text-sm font-semibold leading-6 text-zinc-500">/month</span>
                        </p>
                        <ul role="list" class="mt-8 space-y-3 text-sm leading-6 text-zinc-600">
                            <li class="flex gap-x-3">
                                <flux:icon icon="check" class="h-6 w-5 flex-none text-brand-secondary" />
                                10,000 Marketing Contacts
                            </li>
                            <li class="flex gap-x-3">
                                <flux:icon icon="check" class="h-6 w-5 flex-none text-brand-secondary" />
                                Advanced Automation Tools
                            </li>
                            <li class="flex gap-x-3">
                                <flux:icon icon="check" class="h-6 w-5 flex-none text-brand-secondary" />
                                Priority Support
                            </li>
                            <li class="flex gap-x-3">
                                <flux:icon icon="check" class="h-6 w-5 flex-none text-brand-secondary" />
                                Innovative Real-time Analytics
                            </li>
                        </ul>
                    </div>
                    <flux:button href="/register" variant="primary" class="mt-8 bg-brand-secondary">Choose Pro</flux:button>
                </div>

                <!-- Enterprise Plan -->
                <div class="flex flex-col justify-between rounded-3xl bg-white p-8 ring-1 ring-zinc-200 xl:p-10 shadow-sm hover:shadow-md transition-all border border-transparent hover:border-brand-accent/30">
                    <div>
                        <div class="flex items-center justify-between gap-x-4">
                            <flux:heading level="3" class="text-lg font-semibold leading-8 text-zinc-900">Enterprise</flux:heading>
                        </div>
                        <p class="mt-4 text-sm leading-6 text-zinc-500">For scaling organizations and custom needs.</p>
                        <p class="mt-6 flex items-baseline gap-x-1">
                            <span class="text-4xl font-bold tracking-tight text-zinc-900">Custom</span>
                        </p>
                        <ul role="list" class="mt-8 space-y-3 text-sm leading-6 text-zinc-600">
                            <li class="flex gap-x-3">
                                <flux:icon icon="check" class="h-6 w-5 flex-none text-brand-secondary" />
                                Unlimited Contacts
                            </li>
                            <li class="flex gap-x-3">
                                <flux:icon icon="check" class="h-6 w-5 flex-none text-brand-secondary" />
                                Dedicated Success Manager
                            </li>
                        </ul>
                    </div>
                    <flux:button href="/about" variant="ghost" class="mt-8 text-brand-secondary">Contact Sales</flux:button>
                </div>
            </div>
        </div>
    </div>
</x-layouts.marketing>
