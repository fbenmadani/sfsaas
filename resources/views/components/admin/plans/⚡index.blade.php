<div>
    <div class="flex justify-between items-center mb-6">
        <flux:heading size="xl">Plans</flux:heading>
        <flux:button href="{{ route('admin.plans.create') }}" variant="primary">Create Plan</flux:button>
    </div>

    @if (session()->has('message'))
        <flux:callout variant="success" class="mb-6">
            <flux:callout.text>{{ session('message') }}</flux:callout.text>
        </flux:callout>
    @endif

    <flux:table>
        <flux:table.columns>
            <flux:table.column sortable :sorted="$sortBy === 'name'" :direction="$sortDirection" wire:click="sort('name')">Name</flux:table.column>
            <flux:table.column sortable :sorted="$sortBy === 'slug'" :direction="$sortDirection" wire:click="sort('slug')">Slug</flux:table.column>
            <flux:table.column>Description</flux:table.column>
            <flux:table.column sortable :sorted="$sortBy === 'is_active'" :direction="$sortDirection" wire:click="sort('is_active')">Status</flux:table.column>
            <flux:table.column>Prices</flux:table.column>
            <flux:table.column>Features</flux:table.column>
            <flux:table.column>Actions</flux:table.column>
        </flux:table.columns>
        <flux:table.rows>
            @foreach ($this->plans as $plan)
                <flux:table.row wire:key="plan-{{ $plan->id }}">
                    <flux:table.cell>{{ $plan->name }}</flux:table.cell>
                    <flux:table.cell class="font-mono text-sm">{{ $plan->slug }}</flux:table.cell>
                    <flux:table.cell>{{ Str::limit($plan->description, 50) }}</flux:table.cell>
                    <flux:table.cell>
                        <flux:badge :variant="$plan->is_active ? 'success' : 'warning'" size="sm" class="cursor-pointer" wire:click="toggleActive({{ $plan->id }})">
                            {{ $plan->is_active ? 'Active' : 'Inactive' }}
                        </flux:badge>
                    </flux:table.cell>
                    <flux:table.cell>
                        <flux:badge size="sm">{{ $plan->prices_count ?? 0 }}</flux:badge>
                    </flux:table.cell>
                    <flux:table.cell>
                        <flux:badge size="sm">{{ $plan->features_count ?? 0 }}</flux:badge>
                    </flux:table.cell>
                    <flux:table.cell class="flex items-center gap-2">
                        <flux:button variant="ghost" size="sm" wire:click="editPlan({{ $plan->id }})" x-on:click="$flux.modal('plan-modal').open()">
                            Edit
                        </flux:button>
                        <flux:button variant="ghost" size="sm" wire:click="manageFeatures({{ $plan->id }})" x-on:click="$flux.modal('features-modal').open()">
                            Features
                        </flux:button>
                        <flux:button variant="danger" size="sm" wire:click="deletePlan({{ $plan->id }})" wire:confirm="Are you sure you want to delete this plan?">
                            Delete
                        </flux:button>
                    </flux:table.cell>
                </flux:table.row>
            @endforeach
        </flux:table.rows>
    </flux:table>

    <div class="mt-4">
        {{ $this->plans->links() }}
    </div>

    <flux:modal name="plan-modal" class="md:max-w-2xl">
        <div class="p-6 space-y-4">
            <flux:heading size="lg">{{ $editingPlan ? 'Edit Plan' : 'Create Plan' }}</flux:heading>

            <form wire:submit="savePlan" class="space-y-6">
                <flux:field>
                    <flux:label>Plan Name</flux:label>
                    <flux:input wire:model="name" placeholder="e.g., Basic, Pro, Enterprise" />
                    <flux:error name="name" />
                </flux:field>

                <flux:field>
                    <flux:label>Plan Slug</flux:label>
                    <flux:input wire:model="slug" placeholder="e.g., basic, pro, enterprise" />
                    <flux:error name="slug" />
                </flux:field>

                <flux:field>
                    <flux:label>Description</flux:label>
                    <flux:textarea wire:model="description" rows="3" placeholder="A short description of what this plan offers."></flux:textarea>
                    <flux:error name="description" />
                </flux:field>

                <flux:field>
                    <flux:checkbox wire:model="is_active" label="Plan is Active" />
                </flux:field>

                <div class="flex justify-end gap-3">
                    <flux:button variant="ghost" x-on:click="$flux.modal('plan-modal').close()">Cancel</flux:button>
                    <flux:button type="submit" variant="primary">{{ $editingPlan ? 'Update Plan' : 'Create Plan' }}</flux:button>
                </div>
            </form>
        </div>
    </flux:modal>

    <flux:modal name="features-modal" class="md:max-w-2xl">
        <div class="p-6 space-y-4">
            <flux:heading size="lg">Manage Features — {{ $managingFeaturesPlan?->name }}</flux:heading>

            @if ($this->allFeatures->isEmpty())
                <p class="text-sm text-zinc-500">No features available. Create features first.</p>
            @else
                <div class="space-y-3 max-h-96 overflow-y-auto">
                    @foreach ($this->allFeatures as $feature)
                        @php $isSelected = array_key_exists($feature->id, $selectedFeatures); @endphp
                        <div class="flex items-center gap-4 p-3 rounded-lg border border-zinc-200 dark:border-zinc-700" wire:key="feature-{{ $feature->id }}">
                            <flux:checkbox
                                :checked="$isSelected"
                                wire:click="toggleFeature({{ $feature->id }})"
                            />
                            <div class="flex-1">
                                <div class="font-medium text-sm">{{ $feature->name }}</div>
                                <div class="text-xs text-zinc-500">{{ $feature->slug }} · {{ ucfirst($feature->type) }}</div>
                            </div>
                            @if ($isSelected && $feature->type === 'limit')
                                <flux:input
                                    type="number"
                                    wire:model="selectedFeatures.{{ $feature->id }}"
                                    placeholder="Limit"
                                    class="w-24"
                                    size="sm"
                                />
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif

            <div class="flex justify-end gap-3 pt-2">
                <flux:button variant="ghost" x-on:click="$flux.modal('features-modal').close()">Cancel</flux:button>
                <flux:button wire:click="saveFeatures">Save Features</flux:button>
            </div>
        </div>
    </flux:modal>
</div>