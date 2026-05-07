<?php

namespace App\Livewire\Admin\Domains;

use App\Models\Domain;
use App\Models\Tenant;
use Livewire\Attributes\Rule;
use Livewire\Component;

class Create extends Component
{
    #[Rule('required|string|max:255|unique:domains,domain')]
    public $domain = '';

    #[Rule('required|exists:tenants,id')]
    public $tenant_id = '';

    #[Rule('required|in:subdomain,tld')]
    public $type = 'subdomain';

    #[Rule('boolean')]
    public $is_primary = false;

    public function save()
    {
        $this->validate();

        if ($this->is_primary) {
            Domain::where('tenant_id', $this->tenant_id)->update(['is_primary' => false]);
        }

        Domain::create([
            'domain' => $this->domain,
            'tenant_id' => $this->tenant_id,
            'type' => $this->type,
            'is_primary' => $this->is_primary,
        ]);

        session()->flash('message', 'Domain created successfully.');

        return redirect()->route('admin.domains.index');
    }

    public function render()
    {
        $tenants = Tenant::orderBy('id')->get();

        return view('components.admin.domains.⚡create', compact('tenants'));
    }
}
