<?php

use Livewire\Component;

use App\Models\User;
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
    public function mount() 
    {
       
            
    }
    #[Computed]
    public function users(){
        return User::query()
            ->tap(fn ($query) => $this->sortBy ? $query->orderBy($this->sortBy, $this->sortDirection) : $query)
            ->paginate(2);
    }

   
    
        
};
?>

<div>


    <flux:table>
      <flux:table.columns>
        <flux:table.column sortable :sorted="$sortBy === 'name'" :direction="$sortDirection" wire:click="sort('name')">User</flux:table.column>
      <flux:table.column sortable :sorted="$sortBy === 'email'" :direction="$sortDirection" wire:click="sort('email')">Email</flux:table.column>
      <flux:table.column sortable :sorted="$sortBy === 'created_date'" :direction="$sortDirection" wire:click="sort('created_date')">Date</flux:table.column>
      <flux:table.column>Actions</flux:table.column> 
      </flux:table.columns>
      <flux:table.rows>
        @foreach ($this->users as $user)
            <flux:table.row :table.row :key="$user->id">
                <flux:table.cell class="flex items-center gap-3">{{ $user->name }}</flux:table.cell>
                <flux:table.cell>{{ $user->email }}</flux:table.cell>
                <flux:table.cell class="whitespace-nowrap">{{ $user->created_at->format('Y-m-d') }}</flux:table.cell>
                <flux:table.cell>
                    <flux:button wire:click="edit({{ $user->id }})">Edit</flux:button>
                </flux:table.cell>
                <flux:table.cell>
                    <flux:button variant="ghost" size="sm" icon="ellipsis-horizontal" inset="top bottom"></flux:button>
                </flux:table.cell>
            </flux:table.row>
        @endforeach
      </flux:table.rows>  
    
    </flux:table>    
       {{ $this->users()->links() }}


</div>