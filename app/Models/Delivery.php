<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\ModelStates\HasStates;

class Delivery extends Model
{
    use HasFactory;
    use HasStates;

    protected $primaryKey = 'delivery_id';

    public function order()
    {
        return $this->belongsTo(Order::class,'order_id');
    }
}
