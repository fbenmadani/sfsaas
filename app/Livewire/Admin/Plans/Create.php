<?php

namespace App\Livewire\Admin\Plans;

use App\Models\Plan;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Create extends Component
{
    #[Validate('required|string|max:255')]
    public $name = '';

    #[Validate('required|string|max:255|unique:plans,slug')]
    public $slug = '';

    #[Validate('nullable|string')]
    public $description = '';

    #[Validate('required|integer|min:0')]
    public $trial_days = 0;

    #[Validate('boolean')]
    public $is_active = true;

    public function save()
    {
        $this->validate();

        Plan::create([
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'trial_days' => $this->trial_days,
            'is_active' => $this->is_active,
        ]);

        session()->flash('message', 'Plan created successfully.');

        return redirect()->route('admin.plans.index'); // Assuming this route will exist
    }

    public function render()
    {
        return view('components.admin.plans.⚡create');
    }
}
