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

    public function sort($column) {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'asc';
        }
    }


    #[Computed]
    public function tenants(){
        return Tenant::query()
            ->tap(fn ($query) => $this->sortBy ? $query->orderBy($this->sortBy, $this->sortDirection) : $query)
            ->paginate(10);
    }
};
?>

<div>
    {{-- List all tenants --}}

   
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
                    <flux:button wire:click="edit({{ $tenant->id }})">Edit</flux:button>
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