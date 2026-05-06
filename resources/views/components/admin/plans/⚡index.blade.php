<?php

use App\Models\Feature;
use App\Models\Plan;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

new class extends Component
{
    use WithPagination;

    // Plan form properties
    public string $name = '';
    public string $slug = '';
    public string $description = '';
    public bool $isActive = false;
    public ?Plan $editingPlan = null;

    // Feature management
    public ?Plan $managingFeaturesPlan = null;
    /** @var array<int, int|null> feature_id => limit_value */
    public array $selectedFeatures = [];

    // Sorting
    public string $sortBy = 'name';
    public string $sortDirection = 'asc';

    /**
     * Get the validation rules for plan fields.
     *
     * @return array<string, mixed>
     */
    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => [
                'required',
                'string',
                'max:255',
                'unique:plans,slug,' . ($this->editingPlan?->id ?? 'NULL'),
            ],
            'description' => ['nullable', 'string'],
            'isActive' => ['boolean'],
        ];
    }

    /**
     * Sort the plans table by the given column.
     */
    public function sort(string $column): void
    {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'asc';
        }
    }

    /**
     * Save a new plan or update an existing one.
     */
    public function savePlan(): void
    {
        $data = $this->validate();

        if ($this->editingPlan) {
            $this->editingPlan->update([
                'name' => $data['name'],
                'slug' => $data['slug'],
                'description' => $data['description'],
                'is_active' => $data['isActive'],
            ]);
        } else {
            Plan::create([
                'name' => $data['name'],
                'slug' => $data['slug'],
                'description' => $data['description'],
                'is_active' => $data['isActive'],
            ]);
        }

        $this->resetForm();
        $this->dispatch('plan-saved');
    }

    /**
     * Load a plan into the form for editing.
     */
    public function editPlan(Plan $plan): void
    {
        $this->editingPlan = $plan;
        $this->name = $plan->name;
        $this->slug = $plan->slug;
        $this->description = $plan->description ?? '';
        $this->isActive = $plan->is_active;
    }

    /**
     * Delete a plan.
     */
    public function deletePlan(Plan $plan): void
    {
        $plan->delete();

        if ($this->editingPlan?->is($plan)) {
            $this->resetForm();
        }

        $this->dispatch('plan-deleted');
    }

    /**
     * Toggle a plan's active status.
     */
    public function toggleActive(Plan $plan): void
    {
        $plan->update(['is_active' => ! $plan->is_active]);
        $this->dispatch('plan-updated');
    }

    /**
     * Open the feature management modal for a plan.
     */
    public function manageFeatures(Plan $plan): void
    {
        $this->managingFeaturesPlan = $plan->load('features');

        // Build selectedFeatures from current pivot data
        $this->selectedFeatures = [];
        foreach ($plan->features as $feature) {
            $this->selectedFeatures[$feature->id] = $feature->pivot->limit_value;
        }
    }

    /**
     * Toggle a feature on/off for the current plan.
     */
    public function toggleFeature(int $featureId): void
    {
        if (array_key_exists($featureId, $this->selectedFeatures)) {
            unset($this->selectedFeatures[$featureId]);
        } else {
            $this->selectedFeatures[$featureId] = null;
        }
    }

    /**
     * Save feature associations for the current plan.
     */
    public function saveFeatures(): void
    {
        if (! $this->managingFeaturesPlan) {
            return;
        }

        // Build sync data: [feature_id => ['limit_value' => value]]
        $syncData = [];
        foreach ($this->selectedFeatures as $featureId => $limitValue) {
            $syncData[$featureId] = ['limit_value' => $limitValue];
        }

        $this->managingFeaturesPlan->features()->sync($syncData);

        $this->dispatch('features-updated');
        $this->managingFeaturesPlan = null;
        $this->selectedFeatures = [];
    }

    /**
     * Reset the plan form.
     */
    public function resetForm(): void
    {
        $this->reset(['name', 'slug', 'description', 'isActive', 'editingPlan']);
        $this->resetValidation();
        $this->resetPage();
    }

    /**
     * All features for the picker.
     */
    #[Computed]
    public function allFeatures()
    {
        return Feature::orderBy('name')->get();
    }

    /**
     * Paginated, sorted plans with counts.
     */
    #[Computed]
    public function plans()
    {
        return Plan::withCount(['prices', 'features'])
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate(10);
    }
};
?>

<div>
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">{{ __('Plans') }}</h1>
        <flux:button wire:click="resetForm" x-on:click="$flux.modal('plan-modal').open()">
            {{ __('Create Plan') }}
        </flux:button>
    </div>

    {{-- Plans Table --}}
    <flux:table>
        <flux:table.columns>
            <flux:table.column sortable :sorted="$sortBy === 'name'" :direction="$sortDirection" wire:click="sort('name')">{{ __('Name') }}</flux:table.column>
            <flux:table.column sortable :sorted="$sortBy === 'slug'" :direction="$sortDirection" wire:click="sort('slug')">{{ __('Slug') }}</flux:table.column>
            <flux:table.column sortable :sorted="$sortBy === 'is_active'" :direction="$sortDirection" wire:click="sort('is_active')">{{ __('Status') }}</flux:table.column>
            <flux:table.column>{{ __('Prices') }}</flux:table.column>
            <flux:table.column>{{ __('Features') }}</flux:table.column>
            <flux:table.column>{{ __('Actions') }}</flux:table.column>
        </flux:table.columns>
        <flux:table.rows>
            @foreach ($this->plans as $plan)
                <flux:table.row wire:key="plan-{{ $plan->id }}">
                    <flux:table.cell>{{ $plan->name }}</flux:table.cell>
                    <flux:table.cell>{{ $plan->slug }}</flux:table.cell>
                    <flux:table.cell>
                        <flux:badge :variant="$plan->is_active ? 'primary' : 'danger'" size="sm" class="cursor-pointer" wire:click="toggleActive({{ $plan->id }})">
                            {{ $plan->is_active ? __('Active') : __('Inactive') }}
                        </flux:badge>
                    </flux:table.cell>
                    <flux:table.cell>{{ $plan->prices_count }}</flux:table.cell>
                    <flux:table.cell>{{ $plan->features_count }}</flux:table.cell>
                    <flux:table.cell class="flex items-center gap-2">
                        <flux:button variant="ghost" size="sm" wire:click="editPlan({{ $plan->id }})" x-on:click="$flux.modal('plan-modal').open()">
                            {{ __('Edit') }}
                        </flux:button>
                        <flux:button variant="ghost" size="sm" wire:click="manageFeatures({{ $plan->id }})" x-on:click="$flux.modal('features-modal').open()">
                            {{ __('Features') }}
                        </flux:button>
                        <flux:button variant="danger" size="sm" wire:click="deletePlan({{ $plan->id }})" wire:confirm="{{ __('Are you sure you want to delete this plan?') }}">
                            {{ __('Delete') }}
                        </flux:button>
                    </flux:table.cell>
                </flux:table.row>
            @endforeach
        </flux:table.rows>
    </flux:table>

    <div class="mt-4">
        {{ $this->plans->links() }}
    </div>

    {{-- Plan Create/Edit Modal --}}
    <flux:modal name="plan-modal">
        <div class="p-6 space-y-4">
            <flux:heading>{{ $editingPlan ? __('Edit Plan') : __('Create Plan') }}</flux:heading>

            <flux:field>
                <flux:label>{{ __('Name') }}</flux:label>
                <flux:input wire:model="name" placeholder="{{ __('Enter plan name') }}" />
                <flux:error name="name" />
            </flux:field>

            <flux:field>
                <flux:label>{{ __('Slug') }}</flux:label>
                <flux:input wire:model="slug" placeholder="{{ __('Enter unique slug') }}" />
                <flux:error name="slug" />
            </flux:field>

            <flux:field>
                <flux:label>{{ __('Description') }}</flux:label>
                <flux:textarea wire:model="description" placeholder="{{ __('Enter plan description') }}" />
                <flux:error name="description" />
            </flux:field>

            <flux:field>
                <flux:switch wire:model="isActive" label="{{ __('Active') }}" />
            </flux:field>

            <div class="flex justify-end gap-3">
                <flux:button variant="ghost" x-on:click="$flux.modal('plan-modal').close()">
                    {{ __('Cancel') }}
                </flux:button>
                <flux:button wire:click="savePlan">
                    {{ $editingPlan ? __('Update') : __('Create') }}
                </flux:button>
            </div>
        </div>
    </flux:modal>

    {{-- Feature Management Modal --}}
    <flux:modal name="features-modal">
        <div class="p-6 space-y-4">
            <flux:heading>{{ __('Manage Features') }} — {{ $managingFeaturesPlan?->name }}</flux:heading>

            @if ($this->allFeatures->isEmpty())
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('No features available. Create features first.') }}</p>
            @else
                <div class="space-y-3 max-h-96 overflow-y-auto">
                    @foreach ($this->allFeatures as $feature)
                        @php
                            $isSelected = array_key_exists($feature->id, $selectedFeatures);
                        @endphp
                        <div class="flex items-center gap-4 p-3 rounded-lg border border-gray-200 dark:border-gray-700" wire:key="feature-{{ $feature->id }}">
                            <flux:checkbox
                                :checked="$isSelected"
                                wire:click="toggleFeature({{ $feature->id }})"
                            />
                            <div class="flex-1">
                                <div class="font-medium text-sm">{{ $feature->name }}</div>
                                <div class="text-xs text-gray-500">{{ $feature->slug }} · {{ ucfirst($feature->type) }}</div>
                            </div>
                            @if ($isSelected && $feature->type === 'limit')
                                <flux:input
                                    type="number"
                                    wire:model="selectedFeatures.{{ $feature->id }}"
                                    placeholder="{{ __('Limit') }}"
                                    class="w-24"
                                    size="sm"
                                />
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif

            <div class="flex justify-end gap-3 pt-2">
                <flux:button variant="ghost" x-on:click="$flux.modal('features-modal').close()">
                    {{ __('Cancel') }}
                </flux:button>
                <flux:button wire:click="saveFeatures">
                    {{ __('Save Features') }}
                </flux:button>
            </div>
        </div>
    </flux:modal>
</div>
