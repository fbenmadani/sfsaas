<x-layouts.marketing>
    <x-slot:title>All-in-One SMB Management</x-slot:title>
    
    <div class="relative isolate overflow-hidden bg-white">
        <div class="flex flex-rowpx-6 pt-10 pb-2 lg:px-8 lg:py-40">
            <div class="basis-1/2 mx-auto max-w-2xl flex-shrink-0 lg:mx-0 lg:max-w-xl lg:pt-8">
                <flux:badge class="mb-6 bg-brand-accent/20 text-brand-secondary border-brand-accent/30 font-semibold">New: AI-Powered Insights</flux:badge>
                <flux:heading level="1" class="text-4xl font-bold tracking-tight text-zinc-900 sm:text-6xl">
                    Grow your business with <span class="text-brand-secondary"> {{ config('app.name') }} </span>.
                </flux:heading>
                <flux:text class="mt-6 text-zinc-600 leading-8">
                    Simple, friendly, and innovative tools for SMBs to manage Sales, Marketing, and Customer Service in one delightful platform.
                </flux:text>
                <div class="mt-10 flex items-center gap-x-6">
                    <flux:button href="/register" variant="primary" class="bg-brand-secondary hover:bg-brand-secondary/90">Start Free Trial</flux:button>
                    <flux:button href="/features" variant="ghost" class="text-brand-secondary">See how it works &rarr;</flux:button>
                </div>
            </div>
            <div class="basis-1/2">
                <img class="object-contain md:object-cover" src="{{ asset('images/demo/screenshot-light.png') }}" alt="App screenshot">
                
            </div>
        </div>
    </div>

    <!-- Core Pillars -->
    <div class="bg-zinc-50 py-24 sm:py-32">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="mx-auto max-w-2xl lg:text-center">
                <flux:heading level="2" class="text-base font-semibold leading-7 text-brand-secondary uppercase tracking-wide">Everything you need</flux:heading>
                <flux:heading level="3" class="mt-2 text-3xl font-bold tracking-tight text-zinc-900 sm:text-4xl">Platform Features</flux:heading>
            </div>
            <div class="mx-auto mt-16 max-w-2xl sm:mt-20 lg:mt-24 lg:max-w-none">
                <dl class="grid max-w-xl grid-cols-1 gap-x-8 gap-y-16 lg:max-w-none lg:grid-cols-3">
                    <div class="flex flex-col p-8 rounded-2xl bg-white shadow-sm border border-zinc-100 hover:shadow-md transition-shadow">
                        <dt class="flex items-center gap-x-3 text-base font-semibold leading-7 text-zinc-900">
                            <flux:icon icon="chart-bar" class="h-6 w-6 flex-none text-brand-secondary" />
                            Sales CRM
                        </dt>
                        <dd class="mt-4 flex flex-auto flex-col text-base leading-7 text-zinc-600">
                            <p class="flex-auto">Close deals with confidence using our friendly pipeline management and lead tracking tools.</p>
                        </dd>
                    </div>
                    <div class="flex flex-col p-8 rounded-2xl bg-white shadow-sm border border-zinc-100 hover:shadow-md transition-shadow">
                        <dt class="flex items-center gap-x-3 text-base font-semibold leading-7 text-zinc-900">
                            <flux:icon icon="megaphone" class="h-6 w-6 flex-none text-brand-secondary" />
                            Marketing
                        </dt>
                        <dd class="mt-4 flex flex-auto flex-col text-base leading-7 text-zinc-600">
                            <p class="flex-auto">Reach your customers with cheerful campaigns and automated outreach that feels personal.</p>
                        </dd>
                    </div>
                    <div class="flex flex-col p-8 rounded-2xl bg-white shadow-sm border border-zinc-100 hover:shadow-md transition-shadow">
                        <dt class="flex items-center gap-x-3 text-base font-semibold leading-7 text-zinc-900">
                            <flux:icon icon="face-smile" class="h-6 w-6 flex-none text-brand-secondary" />
                            Helpdesk
                        </dt>
                        <dd class="mt-4 flex flex-auto flex-col text-base leading-7 text-zinc-600">
                            <p class="flex-auto">Delight your audience with support that shines. Unified chat and ticket management for SMBs.</p>
                        </dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="bg-brand-secondary">
        <div class="px-6 py-24 sm:px-6 sm:py-32 lg:px-8">
            <div class="mx-auto max-w-2xl text-center">
                <flux:heading level="2" class="text-3xl font-bold tracking-tight text-white sm:text-4xl">Ready to grow your business?</flux:heading>
                <flux:text class="mx-auto mt-6 max-w-xl text-lg leading-8 text-white/80">
                    Join thousands of SMBs using sfSaas to scale with joy. Start your 14-day free trial today.
                </flux:text>
                <div class="mt-10 flex items-center justify-center gap-x-6">
                    <flux:button href="/register" variant="primary" class="bg-white text-brand-secondary hover:bg-zinc-100">Get Started Today</flux:button>
                    <flux:button href="/pricing" variant="ghost" class="text-white hover:bg-white/10">View Pricing</flux:button>
                </div>
            </div>
        </div>
    </div>
</x-layouts.marketing>
