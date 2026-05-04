<?php

namespace App\Livewire\Admin\Plans;

use Livewire\Component;
use App\Models\Plan; // Assuming Plan model exists and has appropriate fillable fields

class Create extends Component
{
    public $name;
    public $description;
    public $price; // Assuming price is a relevant field

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'price' => 'required|numeric|min:0',
    ];

    public function save()
    {
        $this->validate();

        Plan::create([
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
        ]);

        session()->flash('message', 'Plan created successfully.');

        // Redirect to the index page after creation
        return redirect()->route('admin.plans.index');
    }

    public function render()
    {
        return view('livewire.admin.plans.create');
    }
}
