<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Variation extends Model
{
    use HasFactory;

    protected $primaryKey = 'variation_id';

    protected $guarded = [];

    public function size()
    {
        return $this->hasOne(Size::class, 'size_id');
    }

    public function color()
    {
        return $this->hasOne(Color::class, 'color_id');
    }

    public function variationVendor(): HasMany
    {
        return $this->hasMany(VariationVendor::class, 'variation_id');
    }

    public function Product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
