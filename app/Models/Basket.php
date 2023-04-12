<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Basket extends Model
{
    use HasFactory;

    protected $primaryKey = 'basket_id';

    protected $guarded = [];

    public function variationVendor()
    {
        return $this->belongsToMany(VariationVendor::class, 'baskets_variations', 'basket_id', 'variation_vendor_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'basket_id');
    }

    public function getTotalValueOfBasket()
    {
        return $this->variationVendor()->sum('price');
    }
}
