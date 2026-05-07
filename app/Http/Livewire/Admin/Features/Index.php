<?php

namespace App\Http\Livewire\Admin\Features;

use App\Models\Feature;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $name = '';

    public string $slug = '';

    public string $type = 'boolean';

    public ?Feature $editingFeature = null;

    public bool $showModal = false;

    public string $search = '';

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

    public array|string $queryString = ['search'];

    public function create(): void
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function edit(Feature $feature): void
    {
        $this->editingFeature = $feature;
        $this->name = $feature->name;
        $this->slug = $feature->slug;
        $this->type = $feature->type;
        $this->showModal = true;
    }

    public function save(): void
    {
        $data = $this->validate();

        if ($this->editingFeature) {
            $this->editingFeature->update($data);
        } else {
            Feature::create($data);
        }

        $this->resetForm();
        session()->flash('message', 'Feature saved successfully.');
    }

    public function delete(Feature $feature): void
    {
        $feature->delete();

        if ($this->editingFeature?->is($feature)) {
            $this->resetForm();
        }

        session()->flash('message', 'Feature deleted successfully.');
    }

    public function resetForm(): void
    {
        $this->reset(['name', 'slug', 'type', 'editingFeature', 'showModal']);
        $this->resetPage();
    }

    public function render()
    {
        $features = Feature::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%'.$this->search.'%')
                    ->orWhere('slug', 'like', '%'.$this->search.'%');
            })
            ->orderBy('name')
            ->paginate(10);

        return view('livewire.admin.features.index', compact('features'));
    }
}
