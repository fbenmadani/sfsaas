<?php

namespace App\Models;

use Database\Factories\PriceFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    /** @use HasFactory<PriceFactory> */
    use HasFactory;

    protected $fillable = ['plan_id', 'amount', 'currency', 'billing_interval'];

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }
}
