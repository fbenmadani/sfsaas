<div>
    <h1>Features Management</h1>

    <!-- Search Bar -->
    <div class="mb-4">
        <input type="text" wire:model.debounce.300ms="search" placeholder="Search features..." class="form-input">
    </div>

    <!-- Table of Features -->
    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Description</th>
                <th>Slug</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($features as $feature)
                <tr>
                    <td>{{ $feature->name }}</td>
                    <td>{{ $feature->description }}</td>
                    <td>{{ $feature->slug }}</td>
                    <td>
                        <a href="{{ route('admin.features.edit', $feature) }}" class="btn btn-sm btn-secondary">Edit</a>
                        <button wire:click="deleteFeature({{ $feature->id }})" class="btn btn-sm btn-danger">Delete</button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">No features found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $features->links() }}
    </div>
</div>
</div>