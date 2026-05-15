<?php

namespace App\Livewire\Admin\Plans;

use App\Models\Feature;
use App\Models\Plan;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $name = '';

    public string $slug = '';

    public string $description = '';

    public bool $is_active = false;

    public int $trial_days = 0;

    public ?Plan $editingPlan = null;

    public ?Plan $managingFeaturesPlan = null;

    public array $selectedFeatures = [];

    public string $sortBy = 'name';

    public string $sortDirection = 'asc';

    public string $search = '';

    public bool $showingCreateModal = false;

    protected $queryString = ['search'];

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => [
                'required',
                'string',
                'max:255',
                'unique:plans,slug,'.($this->editingPlan?->id ?? 'NULL'),
            ],
            'description' => ['nullable', 'string'],
            'is_active' => ['boolean'],
            'trial_days' => ['required', 'integer', 'min:0'],
        ];
    }

    public function sort(string $column): void
    {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'asc';
        }
    }

    public function savePlan(): void
    {
        $data = $this->validate();

        if ($this->editingPlan) {
            $this->editingPlan->update($data);
            $this->editingPlan = null;
            $this->dispatch('plan-saved');
        } else {
            Plan::create($data);
            $this->showingCreateModal = false;
            $this->dispatch('plan-saved');
        }

        $this->resetForm();
    }

    public function editPlan(Plan $plan): void
    {
        $this->editingPlan = $plan;
        $this->name = $plan->name;
        $this->slug = $plan->slug;
        $this->description = $plan->description ?? '';
        $this->is_active = $plan->is_active;
        $this->trial_days = $plan->trial_days;
    }

    public function deletePlan(Plan $plan): void
    {
        $plan->delete();

        if ($this->editingPlan?->is($plan)) {
            $this->resetForm();
        }

        $this->dispatch('plan-deleted');
    }

    public function toggleActive(Plan $plan): void
    {
        $plan->update(['is_active' => !$plan->is_active]);
        $this->dispatch('plan-updated');
    }

    public function manageFeatures(Plan $plan): void
    {
        $this->managingFeaturesPlan = $plan->load('features');
        $this->selectedFeatures = [];

        foreach ($plan->features as $feature) {
            $this->selectedFeatures[$feature->id] = $feature->pivot->limit_value;
        }
    }

    public function toggleFeature(int $featureId): void
    {
        if (array_key_exists($featureId, $this->selectedFeatures)) {
            unset($this->selectedFeatures[$featureId]);
        } else {
            $this->selectedFeatures[$featureId] = null;
        }
    }

    public function saveFeatures(): void
    {
        if (!$this->managingFeaturesPlan) {
            return;
        }

        $syncData = [];
        foreach ($this->selectedFeatures as $featureId => $limitValue) {
            $syncData[$featureId] = ['limit_value' => $limitValue];
        }

        $this->managingFeaturesPlan->features()->sync($syncData);
        $this->dispatch('features-updated');
        $this->managingFeaturesPlan = null;
        $this->selectedFeatures = [];
    }

    public function createNewPlan(): void
    {
        $this->resetErrorBag();
        $this->resetValidation();
        $this->name = '';
        $this->slug = '';
        $this->description = '';
        $this->trial_days = 0;
        $this->is_active = true;
        $this->showingCreateModal = true;
    }

    public function resetForm(): void
    {
        $this->reset(['name', 'slug', 'description', 'is_active', 'editingPlan', 'trial_days']);
        $this->resetValidation();
        $this->resetPage();
    }

    #[Computed]
    public function allFeatures()
    {
        return Feature::orderBy('name')->get();
    }

    #[Computed]
    public function plans()
    {
        return Plan::withCount(['prices', 'features'])
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%'.$this->search.'%')
                    ->orWhere('slug', 'like', '%'.$this->search.'%');
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate(10);
    }

    public function render()
    {
        return view('components.admin.plans.⚡index');
    }
}
