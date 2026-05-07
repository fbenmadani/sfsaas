<div>
    <div class="flex justify-between items-center mb-6">
        <flux:heading size="xl">Domains</flux:heading>
        <flux:button href="{{ route('admin.domains.create') }}" variant="primary">Create Domain</flux:button>
    </div>

    @if (session()->has('message'))
        <flux:callout variant="success" class="mb-6">
            <flux:callout.text>{{ session('message') }}</flux:callout.text>
        </flux:callout>
    @endif

    <div class="mb-4 flex gap-4">
        <flux:input wire:model.live="search" placeholder="Search domains..." class="max-w-sm" />
        <flux:select wire:model.live="typeFilter" class="max-w-xs">
            <flux:select.option value="">All Types</flux:select.option>
            <flux:select.option value="subdomain">Subdomains</flux:select.option>
            <flux:select.option value="tld">TLD Domains</flux:select.option>
        </flux:select>
    </div>

    <flux:table>
        <flux:table.columns>
            <flux:table.column sortable :sorted="$sortBy === 'domain'" :direction="$sortDirection" wire:click="sort('domain')">Domain</flux:table.column>
            <flux:table.column sortable :sorted="$sortBy === 'tenant_id'" :direction="$sortDirection" wire:click="sort('tenant_id')">Tenant</flux:table.column>
            <flux:table.column>Type</flux:table.column>
            <flux:table.column>Primary</flux:table.column>
            <flux:table.column sortable :sorted="$sortBy === 'created_at'" :direction="$sortDirection" wire:click="sort('created_at')">Created</flux:table.column>
            <flux:table.column>Actions</flux:table.column>
        </flux:table.columns>
        <flux:table.rows>
            @foreach ($this->domains as $domain)
                <flux:table.row wire:key="domain-{{ $domain->id }}">
                    <flux:table.cell class="font-mono text-sm">{{ $domain->domain }}</flux:table.cell>
                    <flux:table.cell>{{ $domain->tenant->id }}</flux:table.cell>
                    <flux:table.cell>
                        <flux:badge :variant="$domain->isSubdomain() ? 'info' : 'success'" size="sm">
                            {{ ucfirst($domain->type) }}
                        </flux:badge>
                    </flux:table.cell>
                    <flux:table.cell>
                        @if ($domain->is_primary)
                            <flux:badge variant="primary" size="sm">Primary</flux:badge>
                        @else
                            <flux:button variant="ghost" size="sm" wire:click="setPrimary({{ $domain->id }})" wire:confirm="Set as primary domain?">
                                Set Primary
                            </flux:button>
                        @endif
                    </flux:table.cell>
                    <flux:table.cell>{{ $domain->created_at->format('Y-m-d') }}</flux:table.cell>
                    <flux:table.cell class="flex items-center gap-2">
                        <flux:button href="{{ route('admin.domains.edit', $domain) }}" variant="ghost" size="sm">
                            Edit
                        </flux:button>
                        <flux:button variant="danger" size="sm" wire:click="deleteDomain({{ $domain->id }})" wire:confirm="Are you sure you want to delete this domain?">
                            Delete
                        </flux:button>
                    </flux:table.cell>
                </flux:table.row>
            @endforeach
        </flux:table.rows>
    </flux:table>

    <div class="mt-4">
        {{ $this->domains->links() }}
    </div>
</div>
