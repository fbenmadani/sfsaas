<?php

namespace App\Livewire\Admin\Domains;

use App\Models\Domain;
use App\Models\Tenant;
use Livewire\Attributes\Rule;
use Livewire\Component;

class Edit extends Component
{
    public $domain;

    #[Rule('required|string|max:255')]
    public $domain_name = '';

    #[Rule('required|exists:tenants,id')]
    public $tenant_id = '';

    #[Rule('required|in:subdomain,tld')]
    public $type = 'subdomain';

    #[Rule('boolean')]
    public $is_primary = false;

    public function mount($domain)
    {
        $this->domain = Domain::findOrFail($domain);
        $this->domain_name = $this->domain->domain;
        $this->tenant_id = $this->domain->tenant_id;
        $this->type = $this->domain->type;
        $this->is_primary = $this->domain->is_primary;
    }

    public function save()
    {
        $this->validate([
            'domain_name' => 'required|string|max:255|unique:domains,domain,'.$this->domain->id,
        ]);

        if ($this->is_primary && $this->domain->is_primary !== true) {
            Domain::where('tenant_id', $this->tenant_id)->update(['is_primary' => false]);
        }

        $this->domain->update([
            'domain' => $this->domain_name,
            'tenant_id' => $this->tenant_id,
            'type' => $this->type,
            'is_primary' => $this->is_primary,
        ]);

        session()->flash('message', 'Domain updated successfully.');

        return redirect()->route('admin.domains.index');
    }

    public function render()
    {
        $tenants = Tenant::orderBy('id')->get();

        return view('components.admin.domains.⚡edit', compact('tenants'));
    }
}
