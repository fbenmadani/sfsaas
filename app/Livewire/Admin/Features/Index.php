<?php

namespace App\Livewire\Admin\Features;

use App\Models\Feature;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $name = '';
    public string $slug = '';
    public string $type = 'boolean'; // 'boolean' or 'limit'
    public ?Feature $editingFeature = null;

    public $sortBy = 'name';
    public $sortDirection = 'asc';

    protected $listeners = [
        'featureCreated' => '$refresh',
        'featureUpdated' => '$refresh',
        'featureDeleted' => '$refresh',
    ];

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'slug' => ['required', 'string', 'unique:features,slug,' . ($this->editingFeature ? $this->editingFeature->id : 'NULL')],
            'type' => 'required|in:boolean,limit',
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

    public function save(): void
    {
        $this->validate();

        if ($this->editingFeature) {
            $this->editingFeature->update([
                'name' => $this->name,
                'slug' => $this->slug,
                'type' => $this->type,
            ]);
            $this->dispatch('featureUpdated');
        } else {
            Feature::create([
                'name' => $this->name,
                'slug' => $this->slug,
                'type' => $this->type,
            ]);
            $this->dispatch('featureCreated');
        }

        $this->resetForm();
    }

    public function edit(Feature $feature): void
    {
        $this->editingFeature = $feature;
        $this->name = $feature->name;
        $this->slug = $feature->slug;
        $this->type = $feature->type;
    }

    public function delete(Feature $feature): void
    {
        $feature->delete();
        $this->dispatch('featureDeleted');
        $this->resetForm();
    }

    public function resetForm(): void
    {
        $this->reset(['name', 'slug', 'type', 'editingFeature']);
        $this->resetPage(); // Reset pagination when form is reset
    }

    public function render()
    {
        $features = Feature::orderBy($this->sortBy, $this->sortDirection)->paginate(10);

        return view('livewire.admin.features.index', [
            'features' => $features,
        ]);
    }
}
