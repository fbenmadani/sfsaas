<div>
    <div class="mb-6">
        <flux:heading size="xl">Create Plan</flux:heading>
        <flux:text class="mt-2">Define a new subscription plan.</flux:text>
    </div>

    @if (session()->has('message'))
        <flux:callout variant="success" class="mb-6">
            <flux:callout.text>{{ session('message') }}</flux:callout.text>
        </flux:callout>
    @endif

    <form wire:submit="save" class="max-w-2xl space-y-6">
        <flux:field>
            <flux:label for="name">Plan Name</flux:label>
            <flux:input wire:model="name" id="name" placeholder="e.g., Basic, Pro, Enterprise" />
            <flux:error name="name" />
        </flux:field>

        <flux:field>
            <flux:label for="slug">Plan Slug</flux:label>
            <flux:input wire:model="slug" id="slug" placeholder="e.g., basic, pro, enterprise" />
            <flux:error name="slug" />
            <flux:description>Unique identifier for the plan, used in URLs or internal logic.</flux:description>
        </flux:field>

        <flux:field>
            <flux:label for="description">Description</flux:label>
            <flux:textarea wire:model="description" id="description" rows="3" placeholder="A short description of what this plan offers."></flux:textarea>
            <flux:error name="description" />
        </flux:field>

        <flux:field>
            <flux:label for="trial_days">Trial Days</flux:label>
            <flux:input type="number" wire:model="trial_days" id="trial_days" min="0" />
            <flux:error name="trial_days" />
            <flux:description>Number of free trial days for this plan (0 for no trial).</flux:description>
        </flux:field>

        <flux:field>
            <flux:checkbox wire:model="is_active" id="is_active" label="Plan is Active" />
            <flux:error name="is_active" />
            <flux:description>Toggle to make the plan available for subscription.</flux:description>
        </flux:field>

        <div class="flex gap-3">
            <flux:button type="submit" variant="primary">Create Plan</flux:button>
            <flux:button href="{{ route('admin.plans.index') }}" variant="ghost">Cancel</flux:button>
        </div>
    </form>
</div>
