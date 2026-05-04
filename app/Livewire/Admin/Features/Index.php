<?php

namespace App\Livewire\Admin\Features;

use App\Models\Feature;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $name = '';

    public string $slug = '';

    public string $type = 'boolean'; // 'boolean' or 'limit'

    public ?Feature $editingFeature = null;

    public string $sortBy = 'name';

    public string $sortDirection = 'asc';

    /**
     * Get the validation rules for the component.
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
                'unique:features,slug,'.($this->editingFeature?->id ?? 'NULL'),
            ],
            'type' => ['required', 'in:boolean,limit'],
        ];
    }

    /**
     * Sort the features by the given column.
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
     * Save the feature to the database.
     */
    public function save(): void
    {
        $data = $this->validate();

        if ($this->editingFeature) {
            $this->editingFeature->update($data);
        } else {
            Feature::create($data);
        }

        $this->resetForm();
        $this->dispatch('feature-saved');
    }

    /**
     * Load the feature for editing.
     */
    public function edit(Feature $feature): void
    {
        $this->editingFeature = $feature;
        $this->name = $feature->name;
        $this->slug = $feature->slug;
        $this->type = $feature->type;
    }

    /**
     * Delete the feature from the database.
     */
    public function delete(Feature $feature): void
    {
        $feature->delete();

        if ($this->editingFeature?->is($feature)) {
            $this->resetForm();
        }

        $this->dispatch('feature-deleted');
    }

    /**
     * Reset the form and pagination.
     */
    public function resetForm(): void
    {
        $this->reset(['name', 'slug', 'type', 'editingFeature']);
        $this->resetPage();
    }

    /**
     * Render the component.
     */
    public function render(): View
    {
        $features = Feature::orderBy($this->sortBy, $this->sortDirection)->paginate(10);

        return view('livewire.admin.features.index', [
            'features' => $features,
        ])->layout('layouts.app');
    }
}
