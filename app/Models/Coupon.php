<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function product()
    {
        return $this->morphedByMany(Product::class,'couponnable','couponnables','coupon_id','product_id');
    }

    public function user()
    {
        return $this->morphedByMany(User::class,'couponnable','couponnables','coupon_id','user_id');
    }

    public function category()
    {
        return $this->morphedByMany(Category::class,'couponnable','couponnables','coupon_id','category_id');
    }


}
