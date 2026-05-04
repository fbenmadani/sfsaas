<?php

namespace App\Livewire\Admin\Features;

use Livewire\Component;
use App\Models\Feature;

class Edit extends Component
{
    public Feature $feature; // Inject the feature model
    public $name;
    public $description;
    public $slug;

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        // Slug uniqueness rule will be dynamically set in mount/update to exclude current record
    ];

    public function mount(Feature $feature)
    {
        $this->feature = $feature;
        $this->name = $feature->name;
        $this->description = $feature->description;
        $this->slug = $feature->slug;
    }

    public function update()
    {
        // Ensure the slug uniqueness validation accounts for the current record being edited
        $this->rules['slug'] = 'required|string|max:255|unique:features,slug,' . $this->feature->id;
        $this->validate();

        $this->feature->update([
            'name' => $this->name,
            'description' => $this->description,
            'slug' => $this->slug,
        ]);

        session()->flash('message', 'Feature updated successfully.');

        return redirect()->route('admin.features.index');
    }

    public function render()
    {
        return view('livewire.admin.features.edit');
    }
}
