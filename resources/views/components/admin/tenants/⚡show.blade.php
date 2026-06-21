<?php

use Livewire\Component;
use App\Models\Tenant;

use \Livewire\WithPagination;
use Livewire\Attributes\Computed;

new class extends Component
{
    public Tenant $tenant;

    #[Computed]
    public function users(){
        return $this->tenant->users;
    }
    
    #[Computed]
    public function subscription(){
        return $this->tenant->subscription;
    }
    
    #[Computed]
    public function subscription_plan(){
        return $this->subscription() ? $this->subscription()->plan : null;
    }   
            
    
    

    public function mount(Tenant $tenant)
    {
        $this->tenant = $tenant;
       
        
    }
};
?>

<div>
    @php $host = parse_url(config('app.url'), PHP_URL_HOST); @endphp
    <div class="flex justify-between items-center mb-6">
        <div>
            <flux:heading size="xl" level="1">{{ $tenant->name ?? 'Tenant #' . $tenant->id }}</flux:heading>
            <flux:subheading>Tenant details and key metrics</flux:subheading>
        </div>
        <flux:button href="{{ route('admin.tenants.index') }}" variant="ghost" icon="arrow-left">Back to Tenants</flux:button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        {{-- Metric 1: Core details --}}
        <flux:card>
            <flux:heading size="lg">Tenant Info</flux:heading>
            <div class="mt-4 space-y-3 text-sm">
                <div class="flex justify-between items-center">
                    <span class="text-zinc-500">Domain</span>
                    <span class="font-medium text-zinc-900 dark:text-white">
                        @if($tenant->domains && $tenant->domains->first())
                            <a href="http://{{ $tenant->domains->first()->domain }}.{{ $host }}" target="_blank" class="text-blue-600 hover:underline">
                                {{ $tenant->domains->first()->domain }}
                            </a>
                        @else
                            {{ $tenant->id }}
                        @endif
                    </span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-zinc-500">Status</span>
                    <flux:badge color="success" size="sm">Active</flux:badge>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-zinc-500">Created</span>
                    <span class="font-medium text-zinc-900 dark:text-white">{{ $tenant->created_at ? $tenant->created_at->format('M d, Y') : 'N/A' }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-zinc-500">Updated</span>
                    <span class="font-medium text-zinc-900 dark:text-white">{{ $tenant->updated_at ? $tenant->updated_at->format('M d, Y') : 'N/A' }}</span>
                </div>
            </div>
        </flux:card>

        {{-- Metric 2: Subscription --}}
        <flux:card>
            <flux:heading size="lg">Subscription</flux:heading>
            <div class="mt-4 space-y-3 text-sm">
                @if($this->subscription)
                    <div class="flex justify-between">
                        <span class="text-zinc-500">Plan</span>
                        <span class="font-medium text-zinc-900 dark:text-white">{{ $this->subscription_plan ? $this->subscription_plan->name : 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-zinc-500">Status</span>
                        <flux:badge color="zinc" size="sm">Standard</flux:badge>
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center py-4 text-zinc-500 space-y-2">
                        <flux:icon.credit-card class="size-6 text-zinc-400" />
                        <span>No active subscription</span>
                    </div>
                @endif
            </div>
        </flux:card>

        {{-- Metric 3: Users --}}
        <flux:card>
            <flux:heading size="lg">Users</flux:heading>
            <div class="mt-4 flex flex-col items-center justify-center py-2 relative">
                <div class="absolute top-0 right-0">
                    <flux:icon.users class="size-6 text-zinc-300 dark:text-zinc-600" />
                </div>
                <span class="text-5xl font-bold text-zinc-800 dark:text-white">{{ $this->users ? $this->users->count() : 0 }}</span>
                <span class="text-sm text-zinc-500 mt-2">Total Registered Users</span>
            </div>
        </flux:card>
    </div>

    {{-- Users Table --}}
    <flux:card>
        <div class="flex justify-between items-center mb-4">
            <flux:heading size="lg">Recent Users</flux:heading>
            @if($this->users && $this->users->count() > 5)
                <flux:button variant="ghost" size="sm">View all</flux:button>
            @endif
        </div>
        
        @if($this->users && $this->users->count() > 0)
            <flux:table>
                <flux:table.columns>
                    <flux:table.column>Name</flux:table.column>
                    <flux:table.column>Email</flux:table.column>
                    <flux:table.column>Joined</flux:table.column>
                </flux:table.columns>
                <flux:table.rows>
                    @foreach($this->users->take(5) as $user)
                        <flux:table.row :key="$user->id">
                            <flux:table.cell class="font-medium text-zinc-900 dark:text-white">{{ $user->name }}</flux:table.cell>
                            <flux:table.cell class="text-zinc-500">{{ $user->email }}</flux:table.cell>
                            <flux:table.cell class="whitespace-nowrap text-zinc-500">{{ $user->created_at ? $user->created_at->format('M d, Y') : 'N/A' }}</flux:table.cell>
                        </flux:table.row>
                    @endforeach
                </flux:table.rows>
            </flux:table>
        @else
            <div class="text-center py-6 text-zinc-500 border-t border-zinc-200 dark:border-zinc-700">
                <p>No users found for this tenant.</p>
            </div>
        @endif
    </flux:card>
</div>