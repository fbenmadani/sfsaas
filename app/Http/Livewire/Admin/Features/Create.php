<?php

namespace App\Livewire\Admin\Features;

use Livewire\Component;
use App\Models\Feature; // Assuming Feature model exists and has appropriate fillable fields

class Create extends Component
{
    public $name;
    public $description;
    public $slug; // Assuming slug is a relevant field

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'slug' => 'required|string|max:255|unique:features,slug', // Ensure slug is unique
    ];

    public function save()
    {
        $this->validate();

        Feature::create([
            'name' => $this->name,
            'description' => $this->description,
            'slug' => $this->slug,
        ]);

        session()->flash('message', 'Feature created successfully.');

        // Redirect to the index page after creation
        return redirect()->route('admin.features.index');
    }

    public function render()
    {
        return view('livewire.admin.features.create');
    }
}
