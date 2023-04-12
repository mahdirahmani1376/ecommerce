<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Traits\HasRoles;

class Category extends Model implements HasMedia
{
    use HasFactory,InteractsWithMedia,HasRoles;

    protected $guarded = [];

    protected $primaryKey = 'category_id';

    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }

    public function parentCategories(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_category');
    }

    public function coupon()
    {
        return $this->morphToMany(Voucher::class, 'couponnnable', 'couponnable', 'product_id', 'category_id');
    }
}
