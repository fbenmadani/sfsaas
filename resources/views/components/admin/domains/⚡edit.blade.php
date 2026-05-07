<div>
    <div class="mb-6">
        <flux:heading size="xl">Edit Domain</flux:heading>
        <flux:text class="mt-2">Update domain settings.</flux:text>
    </div>

    @if (session()->has('message'))
        <flux:callout variant="success" class="mb-6">
            <flux:callout.text>{{ session('message') }}</flux:callout.text>
        </flux:callout>
    @endif

    <form wire:submit="save" class="max-w-2xl space-y-6">
        <flux:field>
            <flux:label>Domain Name</flux:label>
            <flux:input wire:model="domain_name" placeholder="example.com" />
            <flux:error name="domain_name" />
        </flux:field>

        <flux:field>
            <flux:label>Tenant</flux:label>
            <flux:select wire:model="tenant_id" placeholder="Select a tenant...">
                @foreach ($tenants as $tenant)
                    <option value="{{ $tenant->id }}" @selected($tenant->id === $tenant_id)>{{ $tenant->id }}</option>
                @endforeach
            </flux:select>
            <flux:error name="tenant_id" />
        </flux:field>

        <flux:field>
            <flux:label>Domain Type</flux:label>
            <flux:select wire:model="type">
                <flux:select.option value="subdomain">Subdomain (e.g., myapp.saas.test)</flux:select.option>
                <flux:select.option value="tld">TLD Domain (e.g., myapp.com)</flux:select.option>
            </flux:select>
            <flux:error name="type" />
        </flux:field>

        <flux:field>
            <flux:checkbox wire:model="is_primary" label="Set as primary domain" />
            <flux:error name="is_primary" />
        </flux:field>

        <div class="flex gap-3">
            <flux:button type="submit" variant="primary">Update Domain</flux:button>
            <flux:button href="{{ route('admin.domains.index') }}" variant="ghost">Cancel</flux:button>
        </div>
    </form>
</div>
