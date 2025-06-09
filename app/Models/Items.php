<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Items extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'quantity',
        'category_id',
        'bar_code',
        'brand_id',
        'rate_id'
    ];
    /** @use HasFactory<\Database\Factories\ItemsFactory> */

    public function category()
    {
        return $this->belongsTo(Category::class , 'category_id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class , 'brand_id');
    }

    public function rate()
    {
        return $this->belongsTo(rate::class, 'rate_id');
    }
}
