<?php

namespace App\Livewire\Admin\Domains;

use App\Models\Domain;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';

    public $typeFilter = '';

    public $sortBy = 'created_at';

    public $sortDirection = 'desc';

    protected $queryString = ['search', 'typeFilter'];

    public function sort($column)
    {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'asc';
        }
    }

    #[Computed]
    public function domains()
    {
        return Domain::query()
            ->with(['tenant'])
            ->when($this->search, function ($query) {
                $query->where('domain', 'like', '%'.$this->search.'%');
            })
            ->when($this->typeFilter, function ($query) {
                $query->where('type', $this->typeFilter);
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate(15);
    }

    public function deleteDomain($domainId)
    {
        $domain = Domain::findOrFail($domainId);
        $domain->delete();
        session()->flash('message', 'Domain deleted successfully.');
    }

    public function setPrimary($domainId)
    {
        $domain = Domain::findOrFail($domainId);
        $tenantId = $domain->tenant_id;

        Domain::where('tenant_id', $tenantId)->update(['is_primary' => false]);
        $domain->update(['is_primary' => true]);

        session()->flash('message', 'Primary domain updated.');
    }

    public function render()
    {
        return view('components.admin.domains.⚡index');
    }
}
