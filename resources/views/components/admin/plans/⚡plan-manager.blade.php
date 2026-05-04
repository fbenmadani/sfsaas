<?php

use App\Models\Plan;
use Livewire\Component;

new class extends Component
{
    public $plans;

    // Form properties
    public bool $modalOpen = true;
    public string $name = '';
    public string $slug = '';
    public string $description = '';
    public bool $isActive = false;

    protected function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:plans,slug'],
            'description' => ['nullable', 'string'],
            'isActive' => ['boolean'],
        ];
    }

    public function mount()
    {
        $this->plans = Plan::all();
    }

    public function create()
    {
        $this->validate();

        Plan::create([
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'is_active' => $this->isActive,
        ]);

        $this->resetForm();
        $this->modalOpen = false;
        $this->plans = Plan::all(); // Refresh the list
    }

    public function resetForm()
    {
        $this->name = '';
        $this->slug = '';
        $this->description = '';
        $this->isActive = false;
        $this->resetValidation();
    }

    public function delete(Plan $plan)
    {
        $plan->delete();
        $this->plans = Plan::all(); // Refresh the list
    }
};
?>

<div class="p-4">
    <h1 class="text-2xl font-bold mb-4">Plan Management</h1>

    <div class="mb-4">
        <flux:button.primary wire:click="$set('modalOpen', true)">New Plan</flux:button.primary>
    </div>

    <div class="overflow-x-auto relative shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="py-3 px-6">Name</th>
                    <th scope="col" class="py-3 px-6">Slug</th>
                    <th scope="col" class="py-3 px-6">Description</th>
                    <th scope="col" class="py-3 px-6">Active</th>
                    <th scope="col" class="py-3 px-6">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($plans as $plan)
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                        <td class="py-4 px-6">{{ $plan->name }}</td>
                        <td class="py-4 px-6">{{ $plan->slug }}</td>
                        <td class="py-4 px-6">{{ $plan->description }}</td>
                        <td class="py-4 px-6">
                            @if ($plan->is_active)
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Yes
                                </span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    No
                                </span>
                            @endif
                        </td>
                        <td class="py-4 px-6">
                            <flux:button.link wire:click="delete({{ $plan->id }})" class="text-red-600 hover:text-red-900" confirm="Are you sure you want to delete this plan?">
                                Delete
                            </flux:button.link>
                        </td>
                    </tr>
                @empty
                    <tr class="bg-white dark:bg-gray-800">
                        <td colspan="5" class="py-4 px-6 text-center">No plans found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <flux:modal wire:model="modalOpen" max-width="md">
        <x-slot:title>Create New Plan</x-slot:title>
        <x-slot:content>
            <form wire:submit="create" class="space-y-4">
                <flux:input.text wire:model="name" label="Name" placeholder="Enter plan name" />
                <flux:input.text wire:model="slug" label="Slug" placeholder="Enter unique slug" />
                <flux:input.textarea wire:model="description" label="Description" placeholder="Enter plan description" />
                <flux:input.toggle wire:model="isActive" label="Is Active" />
                <div class="flex justify-end space-x-2">
                    <flux:button.secondary wire:click="$set('modalOpen', false)" type="button">Cancel</flux:button.secondary>
                    <flux:button.primary type="submit">Create</flux:button.primary>
                </div>
            </form>
        </x-slot:content>
    </x-flux::modal>
</div>
