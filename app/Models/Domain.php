<?php

namespace App\Models;

use Stancl\Tenancy\Database\Models\Domain as BaseDomain;

class Domain extends BaseDomain
{
    public const TYPE_SUBDOMAIN = 'subdomain';

    public const TYPE_TLD = 'tld';

    protected $fillable = [
        'domain',
        'tenant_id',
        'type',
        'is_primary',
    ];

    protected function casts(): array
    {
        return [
            'is_primary' => 'boolean',
        ];
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function scopeSubdomains($query)
    {
        return $query->where('type', self::TYPE_SUBDOMAIN);
    }

    public function scopeTlds($query)
    {
        return $query->where('type', self::TYPE_TLD);
    }

    public function scopePrimary($query)
    {
        return $query->where('is_primary', true);
    }

    public function isSubdomain(): bool
    {
        return $this->type === self::TYPE_SUBDOMAIN;
    }

    public function isTld(): bool
    {
        return $this->type === self::TYPE_TLD;
    }
}
