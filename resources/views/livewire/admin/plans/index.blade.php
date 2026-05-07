<div class="p-6">
    <div class="flex items-center justify-between mb-6">
        <flux:heading size="xl">Plans</flux:heading>
        <flux:button wire:click="$set('showModal', true)">Add Plan</flux:button>
    </div>

    <flux:card>
        <table class="w-full">
            <thead>
                <tr class="border-b border-zinc-200 dark:border-zinc-700">
                    <th class="text-left py-3 px-4 font-medium text-zinc-500 dark:text-zinc-400">Name</th>
                    <th class="text-left py-3 px-4 font-medium text-zinc-500 dark:text-zinc-400">Slug</th>
                    <th class="text-left py-3 px-4 font-medium text-zinc-500 dark:text-zinc-400">Features</th>
                    <th class="text-left py-3 px-4 font-medium text-zinc-500 dark:text-zinc-400">Status</th>
                    <th class="text-right py-3 px-4 font-medium text-zinc-500 dark:text-zinc-400">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($plans as $plan)
                <tr class="border-b border-zinc-100 dark:border-zinc-800">
                    <td class="py-3 px-4">{{ $plan->name }}</td>
                    <td class="py-3 px-4 font-mono text-sm text-zinc-500">{{ $plan->slug }}</td>
                    <td class="py-3 px-4">{{ $plan->features_count }}</td>
                    <td class="py-3 px-4">
                        <flux:badge :variant="$plan->is_active ? 'primary' : 'secondary'">
                            {{ $plan->is_active ? 'Active' : 'Inactive' }}
                        </flux:badge>
                    </td>
                    <td class="py-3 px-4 text-right space-x-1">
                        <flux:button size="sm" wire:click="manageFeatures({{ $plan->id }})" variant="ghost" icon="sparkles">Features</flux:button>
                        <flux:button size="sm" wire:click="deletePlan({{ $plan->id }})" variant="ghost" icon="trash" class="text-red-600 dark:text-red-400">Delete</flux:button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="p-4 border-t border-zinc-200 dark:border-zinc-700">
            {{ $plans->links() }}
        </div>
    </flux:card>

    <flux:modal name="features-modal" wire:model="managingFeaturesPlan" class="md:max-w-2xl">
        @if($managingFeaturesPlan)
        <flux:heading size="lg" class="mb-4">
            Manage Features - {{ $managingFeaturesPlan->name }}
        </flux:heading>

        <div class="space-y-2 mb-6 max-h-96 overflow-y-auto">
            @foreach($this->allFeatures() as $feature)
                <div class="flex items-center justify-between p-3 border rounded-lg dark:border-zinc-700">
                    <div class="flex items-center gap-3">
                        <flux:checkbox 
                            wire:click="toggleFeature({{ $feature->id }})" 
                            :checked="isset($selectedFeatures[$feature->id])"
                        />
                        <div>
                            <div class="font-medium">{{ $feature->name }}</div>
                            <div class="text-sm text-zinc-500">{{ $feature->type }}</div>
                        </div>
                    </div>
                    @if($feature->type === 'limit' && isset($selectedFeatures[$feature->id]))
                        <flux:input 
                            type="number" 
                            wire:model="selectedFeatures.{{ $feature->id }}" 
                            class="w-20"
                            placeholder="Limit"
                        />
                    @endif
                </div>
            @endforeach
        </div>

        <div class="flex justify-end gap-3">
            <flux:button wire:click="$set('managingFeaturesPlan', null)" variant="ghost">Cancel</flux:button>
            <flux:button wire:click="saveFeatures()" variant="primary">Save Features</flux:button>
        </div>
        @endif
    </flux:modal>
</div>