<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Product extends Model implements HasMedia
{
    use HasFactory;
    use SoftDeletes;
    use InteractsWithMedia;

    protected $primaryKey = 'product_id';

    protected $guarded = [];

    public function variation(): HasMany
    {
        return $this->hasMany(Variation::class, 'product_id');
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

    public function scopeFilter(Builder $query): Builder
    {
        $request = request();
        return Product::query()
            ->when($request->query('weight'), function (Builder $query, $weight) {
                $query->where('weight', '<=', $weight);
            })
            ->when($request->query('name'), function (Builder $query, $name) {
                $query->where('name', 'like', '%'.$name.'%');
            })
            ->when($request->query('rating'), function (Builder $query, $rating) {
                $query->where('rating', '>=', $rating);
            })
            ->when($request->query('brand'), function (Builder $query, $brand) {
                $query->whereRelation('brand', 'name', '=', $brand);
            })
            ->when($request->query('size'), function (Builder $query, $size) {
                $query->whereRelation('variation.size', 'name', '=', $size);
            })
            ->when($request->query('color'), function (Builder $query, $color) {
                $query->whereRelation('variation.color', 'name', '=', $color);
            })
            ->when($request->query('category'), function (Builder $query, $category) {
                $query->whereRelation('category', 'name', '=', $category);
            })
            ->when($request->query('min_price'), function (Builder $query, $minPrice) {
                $query->whereRelation('variation.variationVendor', 'price', '>=', $minPrice);
            })
            ->when($request->query('max_price'), function (Builder $query, $maxPrice) {
                $query->whereRelation('variation.variationVendor', 'price', '<=', $maxPrice);
            })
            ->when($request->boolean('stock'), function (Builder $query) {
                $query->whereRelation('variation.variationVendor', 'stock', '!=', 0);
            });
    }
}
