<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Basket extends Model
{
    use HasFactory;

    protected $primaryKey = 'basket_id';

    public function products()
    {
        return $this->belongsToMany(Product::class,'baskets_products','basket_id','product_id');
    }
}
