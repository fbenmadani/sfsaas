<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;
use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;

class Tenant extends BaseTenant implements TenantWithDatabase
{
    use HasDatabase, HasDomains, HasFactory;

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function primaryDomain()
    {
        return $this->hasOne(Domain::class)->where('is_primary', true);
    }

    public function subdomains()
    {
        return $this->hasMany(Domain::class)->where('type', Domain::TYPE_SUBDOMAIN);
    }

    public function tldDomains()
    {
        return $this->hasMany(Domain::class)->where('type', Domain::TYPE_TLD);
    }
}
