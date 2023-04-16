<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Stringable;
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

    public function variation(): HasMany
    {
        return $this->hasMany(Variation::class, 'variation_id');
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
        return $this->morphToMany(Voucher::class, 'couponnnable', 'couponnable', 'product_id', 'coupon_id');
    }

    public function baskets()
    {
        return $this->belongsToMany(Basket::class, 'baskets_products', 'product_id', 'basket_id');
    }

    public static function filter(Request $request)
    {
        $results = Product::query()
            ->when($request->string('weight'),function (Builder $query,Stringable $weight){
                $query->where('weight','<=',$weight->value());
            })
            ->when($request->string('brand'),function (Builder $query,Stringable $brand){
                $query->whereRelation('brand','name','LIKE',$brand);
            })
            ->when($request->string('category'),function (Builder $query,Stringable $category){
                $query->whereRelation('category','name','LIKE',$category);
            })
            ->get();

        return $results;
    }

}
