<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class BasketProduct extends Pivot
{
    protected $table = 'baskets_products';

    public function product()
    {
        return $this->belongsTo(BasketProduct::class, 'product_id');
    }
}
