<?php

namespace App\Livewire\Admin\Features;

use Livewire\Component;
use App\Models\Feature;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    protected $queryString = ['search'];

    protected $listeners = ['featureDeleted' => 'refreshFeatures'];

    public function render()
    {
        $features = Feature::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%')
                      ->orWhere('slug', 'like', '%' . $this->search . '%');
            })
            ->orderBy('name')
            ->paginate(10);

        return view('livewire.admin.features.index', compact('features'));
    }

    public function deleteFeature($featureId)
    {
        $feature = Feature::find($featureId);
        if ($feature) {
            $feature->delete();
            session()->flash('message', 'Feature deleted successfully.');
        } else {
            session()->flash('error', 'Feature not found.');
        }
    }

    public function refreshFeatures()
    {
        // Placeholder for event listener if needed.
    }
}
