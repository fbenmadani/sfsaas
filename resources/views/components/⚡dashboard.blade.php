<?php

use Livewire\Component;
use App\Service\BillingService;
use Livewire\Attributes\Computed;

new class extends Component
{
    #[Computed]
    public function metrics()
    {
        return app(BillingService::class)->getMetrics();
    }
};
?>

<div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
    <div class="grid auto-rows-min gap-4 md:grid-cols-4">
        <!-- MRR -->
        <div class="relative overflow-hidden rounded-xl border border-neutral-200 p-6 dark:border-neutral-700 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="rounded-lg bg-zinc-100 p-2 dark:bg-zinc-800">
                    <flux:icon name="currency-dollar" class="size-6 text-zinc-500" />
                </div>
                <div>
                    <flux:heading size="sm" class="text-zinc-500">{{ __('MRR') }}</flux:heading>
                    <div class="text-2xl font-bold">{{ Number::currency($this->metrics['mrr'] ?? 0) }}</div>
                </div>
            </div>
        </div>

        <!-- ARR -->
        <div class="relative overflow-hidden rounded-xl border border-neutral-200 p-6 dark:border-neutral-700 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="rounded-lg bg-zinc-100 p-2 dark:bg-zinc-800">
                    <flux:icon name="chart-bar" class="size-6 text-zinc-500" />
                </div>
                <div>
                    <flux:heading size="sm" class="text-zinc-500">{{ __('ARR') }}</flux:heading>
                    <div class="text-2xl font-bold">{{ Number::currency($this->metrics['arr'] ?? 0) }}</div>
                </div>
            </div>
        </div>

        <!-- Customers -->
        <div class="relative overflow-hidden rounded-xl border border-neutral-200 p-6 dark:border-neutral-700 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="rounded-lg bg-zinc-100 p-2 dark:bg-zinc-800">
                    <flux:icon name="user-group" class="size-6 text-zinc-500" />
                </div>
                <div>
                    <flux:heading size="sm" class="text-zinc-500">{{ __('Customers') }}</flux:heading>
                    <div class="text-2xl font-bold">{{ $this->metrics['customer_count'] ?? 0 }}</div>
                </div>
            </div>
        </div>

        <!-- Tenants -->
        <div class="relative overflow-hidden rounded-xl border border-neutral-200 p-6 dark:border-neutral-700 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="rounded-lg bg-zinc-100 p-2 dark:bg-zinc-800">
                    <flux:icon name="square-3-stack-3d" class="size-6 text-zinc-500" />
                </div>
                <div>
                    <flux:heading size="sm" class="text-zinc-500">{{ __('Tenants') }}</flux:heading>
                    <div class="text-2xl font-bold">{{ $this->metrics['tenant_count'] ?? 0 }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Original Dashbaord Content Placeholder / Future charts -->
    <div class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
        <div class="p-6">
             <flux:heading size="lg">{{ __('Activity Overview') }}</flux:heading>
             <div class="mt-4 grid gap-4 md:grid-cols-2">
                 <div class="flex h-40 flex-col items-center justify-center gap-2 rounded-lg border border-dashed border-zinc-200 dark:border-zinc-700">
                    <flux:icon name="users" class="size-8 text-zinc-400" />
                    <flux:link :href="route('admin.users.index')" wire:navigate class="text-sm font-medium">{{ __('Manage Users') }}</flux:link>
                </div>
                <div class="flex h-40 flex-col items-center justify-center gap-2 rounded-lg border border-dashed border-zinc-200 dark:border-zinc-700">
                    <flux:icon name="building-office" class="size-8 text-zinc-400" />
                    <flux:link :href="route('admin.tenants.index')" wire:navigate class="text-sm font-medium">{{ __('Manage Tenants') }}</flux:link>
                </div>
             </div>
        </div>
    </div>
</div>