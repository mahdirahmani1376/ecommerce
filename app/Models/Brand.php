<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Brand extends Model implements HasMedia
{
    protected $guarded = [];

    protected $primaryKey = 'brand_id';

    use HasFactory,InteractsWithMedia;

    public function products()
    {
        return $this->hasMany(Product::class, 'brand_id');
    }
}
