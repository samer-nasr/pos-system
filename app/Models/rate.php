<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class rate extends Model
{
    protected $fillable = [
        'currency',
        'counter_currency',
        'rate',
        'is_deleted'
    ];
}
