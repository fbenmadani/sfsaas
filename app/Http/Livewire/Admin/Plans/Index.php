<?php

namespace App\Livewire\Admin\Plans;

use Livewire\Component;
use App\Models\Plan;
use Livewire\WithPagination; // For pagination

class Index extends Component
{
    use WithPagination;

    public $search = '';
    protected $queryString = ['search']; // Make search query persistent in URL

    protected $listeners = ['planDeleted' => 'refreshPlans']; // Listen for deletion events

    public function render()
    {
        $plans = Plan::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%');
            })
            ->orderBy('name')
            ->paginate(10); // Paginate results

        return view('livewire.admin.plans.index', compact('plans'));
    }

    public function deletePlan($planId)
    {
        $plan = Plan::find($planId);
        if ($plan) {
            $plan->delete();
            session()->flash('message', 'Plan deleted successfully.');
            // Refresh the component to show updated list and trigger events if needed
            // No need to emit 'planDeleted' if we are redirecting or refreshing directly.
            // For this index component, a simple refresh is often enough.
        } else {
            session()->flash('error', 'Plan not found.');
        }
    }

    // This method is called after a deletion event if we were using emit/listen for confirmation modals.
    // Since direct delete is implemented in this component, it might not be strictly needed here.
    public function refreshPlans()
    {
        // This method is called by the 'planDeleted' event, but deletePlan itself updates the data.
        // If deletePlan were in a modal component, this would be necessary.
        // For now, it's a placeholder. The deletePlan method itself handles the update.
    }
}
