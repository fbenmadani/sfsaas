<div>
    <h1>Plans Management</h1>

    <!-- Search Bar -->
    <div class="mb-4">
        <input type="text" wire:model.debounce.300ms="search" placeholder="Search plans..." class="form-input">
    </div>

    <a href="{{ route('admin.plans.create') }}" class="btn btn-sm btn-primary">Create Plan</a>
    <!-- Table of Plans -->
    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Description</th>
                <th>Price</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($plans as $plan)
                <tr>
                    <td>{{ $plan->name }}</td>
                    <td>{{ $plan->description }}</td>
                    <td>{{ $plan->price }}</td>
                    <td>
                        <a href="{{ route('admin.plans.edit', $plan) }}" class="btn btn-sm btn-secondary">Edit</a>
                        <!-- Delete button/action would go here, potentially triggering a modal or an event -->
                        <button wire:click="deletePlan({{ $plan->id }})" class="btn btn-sm btn-danger">Delete</button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">No plans found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $plans->links() }}
    </div>

    <!-- Livewire component for delete confirmation modal could be included here or globally -->
</div>
