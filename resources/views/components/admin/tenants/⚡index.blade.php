<?php

use Livewire\Component;
use App\Models\Tenant;

use \Livewire\WithPagination;
use Livewire\Attributes\Computed;

new class extends Component
{
    //
    public $sortBy = 'created_at';
    public $sortDirection = 'desc';
    public $search = '';
    
    public function sort($column) {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'asc';
        }
    }
    public function show($tenant_id) {

       // why $tenant_id is null ?
     return redirect()->route('admin.tenants.show', ['tenant' => $tenant_id]);
    }

    #[Computed]
    public function tenants(){
        return Tenant::query()
            ->tap(fn ($query) => $this->sortBy ? $query->orderBy($this->sortBy, $this->sortDirection) : $query)
            ->when($this->search, function ($query) {
                $query->where('name', 'like', "%{$this->search}%");
            })
            ->paginate(10);
    }
};
?>

<div>
    {{-- List all tenants --}}
    <div class="flex justify-between items-center mb-6">
        <flux:heading size="xl">Tenants</flux:heading>
        <flux:button href="#" variant="primary">Create Tenant</flux:button>
    </div>

    @if (session()->has('message'))
        <flux:callout variant="success" class="mb-6">
            <flux:callout.text>{{ session('message') }}</flux:callout.text>
        </flux:callout>
    @endif

        <div class="mb-4 flex gap-4">
        <flux:input wire:model.live="search" placeholder="Search domains..." class="max-w-sm" />
        <flux:select wire:model.live="typeFilter" class="max-w-xs">
            <flux:select.option value="">Status</flux:select.option>
            <flux:select.option value="active">Active</flux:select.option>
            <flux:select.option value="inactive">Inactive</flux:select.option>
        </flux:select>
    </div>

   
    <flux:table>
      <flux:table.columns>
        <flux:table.column sortable :sorted="$sortBy === 'name'" :direction="$sortDirection" wire:click="sort('name')">Account</flux:table.column>
      <flux:table.column>Domain</flux:table.column>
      
      <flux:table.column >Status</flux:table.column>
      <flux:table.column >URL</flux:table.column>
      <flux:table.column sortable :sorted="$sortBy === 'created_date'" :direction="$sortDirection" wire:click="sort('created_date')">Date</flux:table.column>
      <flux:table.column>Actions</flux:table.column> 
      </flux:table.columns>
      <flux:table.rows>
        @foreach ($this->tenants as $tenant)
            <flux:table.row :table.row :key="$tenant->id">
                <flux:table.cell>{{ $tenant->name }}</flux:table.cell>
                <flux:table.cell>{{ $tenant->domains->first()->domain }}</flux:table.cell>
                <flux:table.cell>{{ $tenant->status }}</flux:table.cell>
                <flux:table.cell><a href="http://{{ $tenant->domains->first()->domain }}.sfsaas.test">{{ $tenant->domains->first()->domain }}</a></flux:table.cell>
                <flux:table.cell class="whitespace-nowrap">{{ $tenant->created_at->format('Y-m-d') }}</flux:table.cell>
                <flux:table.cell>
                    <flux:button wire:click="edit({{ $tenant->id }})">Edit</flux:button>
                </flux:table.cell>
          
          
                <flux:table.cell>
                <a href ="{{ route('admin.tenants.show', ['tenant' => $tenant->id]) }}" style="color: blue;">View</a>

                   
                </flux:table.cell>
                <flux:table.cell>
                    <flux:button variant="ghost" size="sm" icon="ellipsis-horizontal" inset="top bottom"></flux:button>
                </flux:table.cell>
            </flux:table.row>
        @endforeach
      </flux:table.rows>  
    
    </flux:table>    
       {{ $this->tenants()->links() }}


</div>