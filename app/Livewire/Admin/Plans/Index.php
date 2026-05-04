<?php

namespace App\Livewire\Admin\Plans;

use App\Models\Plan;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $sortBy = 'name';

    public string $sortDirection = 'asc';

    public function sort(string $column): void
    {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'asc';
        }
    }

    public function toggleActive(Plan $plan): void
    {
        $plan->update(['is_active' => ! $plan->is_active]);
        $this->dispatch('plan-updated');
    }

    public function render()
    {
        $plans = Plan::withCount(['prices', 'features'])
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate(10);

        return view('livewire.admin.plans.index', [
            'plans' => $plans,
        ])->layout('layouts.app');
    }
}
