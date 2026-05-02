<div>
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Features</h1>
        <flux:button wire:click="$set('editingFeature', null); $set('name', ''); $set('slug', ''); $set('type', 'boolean');" x-on:click="$flux.modal('feature-modal').open()">Create Feature</flux:button>
    </div>

    <flux:table>
        <flux:table.columns>
            <flux:table.column sortable :sorted="$sortBy === 'name'" :direction="$sortDirection" wire:click="sort('name')">{{ __('Name') }}</flux:table.column>
            <flux:table.column sortable :sorted="$sortBy === 'slug'" :direction="$sortDirection" wire:click="sort('slug')">{{ __('Slug') }}</flux:table.column>
            <flux:table.column sortable :sorted="$sortBy === 'type'" :direction="$sortDirection" wire:click="sort('type')">{{ __('Type') }}</flux:table.column>
            <flux:table.column>{{ __('Actions') }}</flux:table.column>
        </flux:table.columns>
        <flux:table.rows>
            @foreach ($features as $feature)
                <flux:table.row>
                    <flux:table.cell>{{ $feature->name }}</flux:table.cell>
                    <flux:table.cell>{{ $feature->slug }}</flux:table.cell>
                    <flux:table.cell>{{ ucfirst($feature->type) }}</flux:table.cell>
                    <flux:table.cell class="flex items-center gap-2">
                        <flux:button variant="ghost" size="sm" wire:click="edit({{ $feature->id }})" x-on:click="$flux.modal('feature-modal').open()">Edit</flux:button>
                        <flux:button variant="danger" size="sm" wire:click="delete({{ $feature->id }})">Delete</flux:button>
                    </flux:table.cell>
                </flux:table.row>
            @endforeach
        </flux:table.rows>
    </flux:table>

    <flux:modal name="feature-modal" wire:ignore>
        <div class="p-6">
            <h2 class="text-lg font-medium mb-4">{{ $editingFeature ? 'Edit Feature' : 'Create Feature' }}</h2>
            <flux:input label="Name" wire:model="name" />
            <flux:input label="Slug" wire:model="slug" class="mt-4" />
            <flux:select label="Type" wire:model="type" class="mt-4">
                <option value="boolean">Boolean</option>
                <option value="limit">Limit</option>
            </flux:select>
            <div class="mt-6 flex justify-end gap-3">
                <flux:button wire:click="save">Save</flux:button>
                <flux:button variant="secondary" x-on:click="$flux.modal('feature-modal').close()">Cancel</flux:button>
            </div>
        </div>
    </flux:modal>
</div>