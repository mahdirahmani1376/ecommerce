<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VariationVendor extends Model
{
    use HasFactory;

    protected $table = 'variations_vendors';

    protected $guarded = [];

    protected $primaryKey = 'variation_vendor_id';

    public function variation(): BelongsTo
    {
        return $this->belongsTo(Variation::class, 'variation_id');
    }

    public function Vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    public function basketsVariations(): HasMany
    {
        return $this->hasMany(BasketVariationVendor::class, 'variation_vendor_id');
    }
}
