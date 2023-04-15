<?php

namespace App\Models;

use App\States\OrderState;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
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

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function delivery()
    {
        return $this->hasOne(Delivery::class, 'order_id');
    }

    public function basketVariationVendor(): HasMany
    {
        return $this->hasMany(BasketVariationVendor::class,'order_id');
    }

    public function orderVariations(): HasMany
    {
        return $this->hasMany(OrderVariation::class,'order_id');
    }
}
