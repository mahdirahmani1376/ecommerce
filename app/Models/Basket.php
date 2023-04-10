<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Basket extends Model
{
    use HasFactory;

    protected $primaryKey = 'basket_id';
    protected $guarded = [];

    public function products()
    {
        return $this->hasMany(BasketProduct::class,'basket_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'basket_id');
    }

    /**
     * @return array
     */
    public function getTotalValueOfBasket(): array
    {
        $productsPriceArray = [];
        $priceOfProducts = $this->products()->each(function (BasketProduct $basketProduct) use (&$productsPriceArray) {
            $productVendor = ProductVendor::where([
                'product_id' => $basketProduct->product_id,
                'vendor_id' => $basketProduct->vendor_id,
            ])->first();
            if ($productVendor->exists()) {
                $productsPriceArray[] = $productVendor->price;
            }
        });
        return $productsPriceArray;
    }
}
