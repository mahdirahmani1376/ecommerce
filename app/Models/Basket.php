<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Basket extends Model
{
    use HasFactory;

    protected $primaryKey = 'basket_id';

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'basket_id');
    }

    public function basketVariationVendor(): HasMany
    {
        return $this->hasMany(BasketVariationVendor::class,'basket_id');
    }

    public function getTotalValueOfBasket()
    {
        return $this->basketVariationVendor()->sum('price');
    }
}
