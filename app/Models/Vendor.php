<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Vendor extends Model implements HasMedia
{
    use HasFactory,InteractsWithMedia,Notifiable;

    protected $guarded = [];

    protected $primaryKey = 'vendor_id';

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'vendor_id');
    }

    public function variation(): HasMany
    {
        return $this->hasMany(VariationVendor::class, 'vendor_id');
    }
}
