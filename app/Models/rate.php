<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class rate extends Model
{
    protected $fillable = [
        'currency_id',
        'counter_currency_id',
        'rate',
        'is_deleted'
    ];

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    public function counter_currency()
    {
        return $this->belongsTo(Currency::class, 'counter_currency_id');
    }
}
