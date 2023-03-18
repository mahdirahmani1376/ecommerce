<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Vendor extends Model implements HasMedia
{
    use HasFactory,InteractsWithMedia;

    protected $guarded = [];

    protected $primaryKey = 'vendor_id';

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class,'products_vendors','vendor_id','product_id');
    }
}
