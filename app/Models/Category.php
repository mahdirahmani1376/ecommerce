<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Traits\HasRoles;

class Category extends Model implements HasMedia
{
    use HasFactory,InteractsWithMedia,HasRoles;

    protected $guarded = [];

    public function products()
    {
        return $this->hasMany(Product::class,'category_id');
    }

    public function parentCategories()
    {
        return $this->hasMany(Category::class,'parent_category');
    }
}
