<?php

namespace App\Models;

use App\States\OrderState;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\ModelStates\HasStates;

class Order extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasStates;

    protected $primaryKey = 'order_id';
    protected $guarded = [];
    protected $casts = [
        'state' => OrderState::class,
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class,'orders_products','order_id','product_id')->using(ProductVendor::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function  vendor()
    {
        return $this->belongsTo(Vendor::class,'vendor_id');
    }

    public function delivery()
    {
        return $this->hasOne(Delivery::class,'order_id');
    }
}
