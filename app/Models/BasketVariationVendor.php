<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BasketVariationVendor extends Model
{
    use HasFactory;


    protected $primaryKey = 'basket_variation_id';
    protected $guarded = [];
    protected $table = 'baskets_variations';


    public function basket(): BelongsTo
    {
        return $this->belongsTo(Basket::class,'basket_id');
    }

    public function variationVendor(): BelongsTo
    {
        return $this->belongsTo(VariationVendor::class,'variation_vendor_id');
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class,'order_id');
    }
}
