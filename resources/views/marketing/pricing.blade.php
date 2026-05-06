<x-layouts.marketing>
    <x-slot:title>Pricing Plans</x-slot:title>

    <div class="bg-white py-24 sm:py-32">
        <div class="mx-auto max-w-7xl px-6 lg:px-8 text-center">
            <flux:heading level="1" class="text-3xl font-bold tracking-tight text-zinc-900 sm:text-6xl">Simple, Cheerful Pricing</flux:heading>
            <flux:text class="mt-6 text-zinc-600">
                Choose the plan that fits your business stage. No hidden fees, just pure growth.
            </flux:text>
            
            <livewire:pricing.list />
        </div>
    </div>
</x-layouts.marketing>
