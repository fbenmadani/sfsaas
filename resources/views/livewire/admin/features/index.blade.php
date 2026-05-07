<div class="p-6">
    <div class="flex items-center justify-between mb-6">
        <flux:heading size="xl">Features</flux:heading>
        <flux:button wire:click="create">Add Feature</flux:button>
    </div>

    <flux:card>
        <table class="w-full">
            <thead>
                <tr class="border-b border-zinc-200 dark:border-zinc-700">
                    <th class="text-left py-3 px-4 font-medium text-zinc-500 dark:text-zinc-400">Name</th>
                    <th class="text-left py-3 px-4 font-medium text-zinc-500 dark:text-zinc-400">Slug</th>
                    <th class="text-left py-3 px-4 font-medium text-zinc-500 dark:text-zinc-400">Type</th>
                    <th class="text-right py-3 px-4 font-medium text-zinc-500 dark:text-zinc-400">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($features as $feature)
                <tr class="border-b border-zinc-100 dark:border-zinc-800">
                    <td class="py-3 px-4">{{ $feature->name }}</td>
                    <td class="py-3 px-4 font-mono text-sm text-zinc-500">{{ $feature->slug }}</td>
                    <td class="py-3 px-4">
                        <flux:badge :variant="$feature->type === 'boolean' ? 'primary' : 'secondary'">
                            {{ $feature->type }}
                        </flux:badge>
                    </td>
                    <td class="py-3 px-4 text-right space-x-1">
                        <flux:button size="sm" wire:click="edit({{ $feature->id }})" variant="ghost" icon="pencil-square">Edit</flux:button>
                        <flux:button size="sm" wire:click="delete({{ $feature->id }})" variant="ghost" icon="trash" class="text-red-600 dark:text-red-400">Delete</flux:button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="p-4 border-t border-zinc-200 dark:border-zinc-700">
            {{ $features->links() }}
        </div>
    </flux:card>

    <flux:modal name="feature-modal" wire:model="showModal" class="md:max-w-2xl">
        <form wire:submit="save">
            <flux:heading size="lg" class="mb-4">
                {{ $editingFeature ? 'Edit Feature' : 'Create Feature' }}
            </flux:heading>

            <flux:field class="mb-4">
                <flux:label>Name</flux:label>
                <flux:input wire:model="name" placeholder="Feature name" />
                <flux:error name="name" />
            </flux:field>

            <flux:field class="mb-4">
                <flux:label>Slug</flux:label>
                <flux:input wire:model="slug" placeholder="feature-slug" />
                <flux:error name="slug" />
            </flux:field>

            <flux:field class="mb-6">
                <flux:label>Type</flux:label>
                <flux:select wire:model="type">
                    <flux:select.option value="boolean">Boolean</flux:select.option>
                    <flux:select.option value="limit">Limit</flux:select.option>
                </flux:select>
                <flux:error name="type" />
            </flux:field>

            <div class="flex justify-end gap-3">
                <flux:button type="button" wire:click="resetForm" variant="ghost">Cancel</flux:button>
                <flux:button type="submit" variant="primary">
                    {{ $editingFeature ? 'Update' : 'Create' }}
                </flux:button>
            </div>
        </form>
    </flux:modal>
</div>