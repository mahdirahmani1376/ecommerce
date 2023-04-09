<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class Product extends Model implements HasMedia
{
    use HasFactory;
    use SoftDeletes;
    use InteractsWithMedia;

    protected $primaryKey = 'product_id';

    protected $guarded = [];

    public function orders()
    {
    }

    public function vendors(): BelongsToMany
    {
        return $this->belongsToMany(Vendor::class, 'products_vendors', 'product_id', 'vendor_id')
            ->withPivot('stock', 'price')
            ->using(ProductVendor::class);
    }

    public function productVendor(): HasMany
    {
        return $this->hasMany(ProductVendor::class, 'product_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'product_id');
    }

    public function usersWishlist()
    {
        return $this->belongsToMany(User::class, 'products_users', 'product_id', 'user_id');
    }

    public function coupon()
    {
        return $this->morphToMany(Coupon::class,'couponnnable','couponnable','product_id','coupon_id');
    }

    public function baskets()
    {
        return $this->belongsToMany(Basket::class,'baskets_products','product_id','basket_id');
    }

    public static function filter(): QueryBuilder
    {
        return QueryBuilder::for(Product::class)
            ->allowedFilters([
                AllowedFilter::exact('category_id'),
                AllowedFilter::partial('name'),
            ])
//            ->allowedIncludes('category','vendors','orders')
;
    }
}
