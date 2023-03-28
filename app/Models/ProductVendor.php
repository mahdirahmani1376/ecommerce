<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ProductVendor extends Pivot
{
    use HasFactory;

    protected $table = 'products_vendors';
    public $incrementing = true;

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class,'vendor_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class,'product_id');
    }
}
