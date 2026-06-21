<?php

namespace App\Models;

use Database\Factories\PriceFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Price extends Model
{
    /** @use HasFactory<PriceFactory> */
    use HasFactory;

    protected $fillable = ['plan_id', 'amount', 'currency', 'billing_interval', 'is_yearly'];

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }
}
