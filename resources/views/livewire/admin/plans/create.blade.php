<div>
    <h1>Create New Plan</h1>

    <form wire:submit.prevent="save">
        <div>
            <label for="name">Plan Name</label>
            <input type="text" id="name" wire:model.defer="name" class="form-input">
            @error('name') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="description">Description</label>
            <textarea id="description" wire:model.defer="description" class="form-input"></textarea>
            @error('description') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="price">Price</label>
            <input type="number" id="price" wire:model.defer="price" step="0.01" class="form-input">
            @error('price') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>

        <button type="submit" class="btn btn-primary">Save Plan</button>
    </form>
</div>
