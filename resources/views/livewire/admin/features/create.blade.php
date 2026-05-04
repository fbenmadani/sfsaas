<div>
    <h1>Create New Feature</h1>

    <form wire:submit.prevent="save">
        <div>
            <label for="name">Feature Name</label>
            <input type="text" id="name" wire:model.defer="name" class="form-input">
            @error('name') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="description">Description</label>
            <textarea id="description" wire:model.defer="description" class="form-input"></textarea>
            @error('description') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="slug">Slug</label>
            <input type="text" id="slug" wire:model.defer="slug" class="form-input">
            @error('slug') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>

        <button type="submit" class="btn btn-primary">Save Feature</button>
    </form>
</div>
