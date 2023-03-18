<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\ModelStates\HasStates;

class Product extends Model implements HasMedia
{
    use HasFactory;
    use SoftDeletes;
    use InteractsWithMedia;

    protected $primaryKey = 'product_id';

    public function orders(): BelongsTo
    {
        return $this->belongsTo(Order::class,'product_id');
    }

    public function vendors(): BelongsToMany
    {
        return $this->belongsToMany(Vendor::class,'vendors','product_id','vendor_id');
    }

}
