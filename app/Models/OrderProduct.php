<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class OrderProduct extends Pivot
{
    protected $table = 'orders_products';

    public function product()
    {
        return $this->belongsTo(Order::class, 'product_id');
    }

}
