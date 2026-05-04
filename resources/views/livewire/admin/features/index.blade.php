<div>
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Features</h1>
        <flux:button wire:click="resetForm" x-on:click="$flux.modal('feature-modal').open()">Create Feature</flux:button>
    </div>

    <flux:table>
        <flux:table.columns>
            <flux:table.column sortable :sorted="$sortBy === 'name'" :direction="$sortDirection" wire:click="sort('name')">Name</flux:table.column>
            <flux:table.column sortable :sorted="$sortBy === 'slug'" :direction="$sortDirection" wire:click="sort('slug')">Slug</flux:table.column>
            <flux:table.column sortable :sorted="$sortBy === 'type'" :direction="$sortDirection" wire:click="sort('type')">Type</flux:table.column>
            <flux:table.column>Actions</flux:table.column>
        </flux:table.columns>
        <flux:table.rows>
            @foreach ($features as $feature)
                <flux:table.row :key="$feature->id">
                    <flux:table.cell>{{ $feature->name }}</flux:table.cell>
                    <flux:table.cell>{{ $feature->slug }}</flux:table.cell>
                    <flux:table.cell>{{ ucfirst($feature->type) }}</flux:table.cell>
                    <flux:table.cell class="flex gap-2">
                        <flux:button variant="ghost" size="sm" wire:click="edit({{ $feature->id }})" x-on:click="$flux.modal('feature-modal').open()">Edit</flux:button>
                        <flux:button variant="danger" size="sm" wire:click="delete({{ $feature->id }})" wire:confirm="Are you sure you want to delete this feature?">Delete</flux:button>
                    </flux:table.cell>
                </flux:table.row>
            @endforeach
        </flux:table.rows>
    </flux:table>

    <div class="mt-4">
        {{ $features->links() }}
    </div>

    <flux:modal name="feature-modal" class="md:w-[500px]">
        <form wire:submit="save" class="space-y-6">
            <div>
                <flux:heading size="lg">{{ $editingFeature ? 'Edit Feature' : 'Create Feature' }}</flux:heading>
                <flux:subheading>Define global feature settings.</flux:subheading>
            </div>

            <flux:input label="Name" wire:model="name" placeholder="e.g. Storage Limit" />
            <flux:input label="Slug" wire:model="slug" placeholder="e.g. storage-limit" />

            <flux:select label="Type" wire:model="type">
                <option value="boolean">Boolean (Enabled/Disabled)</option>
                <option value="limit">Limit (Numeric Quota)</option>
            </flux:select>

            <div class="flex gap-3 justify-end">
                <flux:modal.close>
                    <flux:button variant="ghost">Cancel</flux:button>
                </flux:modal.close>
                <flux:button type="submit" variant="primary" wire:loading.attr="disabled">
                    <span wire:loading.remove>Save Feature</span>
                    <span wire:loading>Saving...</span>
                </flux:button>
            </div>
        </form>
    </flux:modal>

    @script
    <script>
        $wire.on('feature-saved', () => {
            $flux.modal('feature-modal').close();
        });
    </script>
    @endscript
</div>
