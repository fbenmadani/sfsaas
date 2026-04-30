<?php

namespace App\Models;

use Database\Factories\SubscriptionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    /** @use HasFactory<SubscriptionFactory> */
    use HasFactory;

    protected $fillable = ['tenant_id', 'price_id', 'status', 'trial_ends_at', 'ends_at'];

    public function price()
    {
        return $this->belongsTo(Price::class);
    }

    // Helper for MRR calculation
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
