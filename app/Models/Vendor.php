<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Notifications\Notifiable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Vendor extends Model implements HasMedia
{
    use HasFactory,InteractsWithMedia,Notifiable;

    protected $guarded = [];

    protected $primaryKey = 'vendor_id';

    public function products(): BelongsToMany
    {
//        return $this->belongsToMany(Product::class,'products_vendors','vendor_id','product_id');
        return $this->belongsToMany(Product::class, 'products_vendors', 'vendor_id', 'product_id')->using(ProductVendor::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'vendor_id');
    }
}
