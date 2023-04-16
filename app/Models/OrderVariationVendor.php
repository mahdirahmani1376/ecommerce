<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderVariationVendor extends Model
{
    protected $primaryKey = 'order_variation_vendor_id';

    protected $table = 'order_variation_vendors';

    protected $guarded = [];

    public function Order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function variationVendor(): BelongsTo
    {
        return $this->belongsTo(VariationVendor::class, 'variation_vendor_id');
    }
}
