<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Lcobucci\JWT\Token\Builder;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\ModelStates\HasStates;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class Product extends Model implements HasMedia
{
    use HasFactory;
    use SoftDeletes;
    use InteractsWithMedia;

    protected $primaryKey = 'product_id';

    public function orders()
    {
        return $this->belongsToMany(Order::class,'orders_products','product_id','order_id');
    }

    public function vendors(): BelongsToMany
    {
        return $this->belongsToMany(Vendor::class,'products_vendors','product_id','vendor_id')->using(ProductVendor::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class,'category_id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class,'product_id');
    }

    public function productsVendors()
    {
        return $this->hasMany(ProductVendor::class,'product_id');
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
