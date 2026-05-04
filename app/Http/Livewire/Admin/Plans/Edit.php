<?php

namespace App\Livewire\Admin\Plans;

use Livewire\Component;
use App\Models\Plan;

class Edit extends Component
{
    public Plan $plan; // Inject the plan model
    public $name;
    public $description;
    public $price;

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'price' => 'required|numeric|min:0',
    ];

    public function mount(Plan $plan)
    {
        $this->plan = $plan;
        $this->name = $plan->name;
        $this->description = $plan->description;
        $this->price = $plan->price;
    }

    public function update()
    {
        $this->validate();

        $this->plan->update([
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
        ]);

        session()->flash('message', 'Plan updated successfully.');

        // Redirect back to the index page or stay on edit page
        return redirect()->route('admin.plans.index');
    }

    public function render()
    {
        return view('livewire.admin.plans.edit');
    }
}
