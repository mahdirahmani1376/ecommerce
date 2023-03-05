<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{

    /**
     * @var array
     */
    protected array $categories = ['Fashion', 'Electronics', 'Sweet Home', 'Appliances', 'Beauty'];

    public function run(): void
    {
        foreach ($this->categories as $category)
        {
            $filePath = public_path('category_images/'.$category.'.jpg');
            $category = Category::create([
               'name' => $category,
               'slug' => Str::slug($category),
            ]);
            $category->addMedia($filePath)->preservingOriginal()->toMediaCollection('default');
        }
    }
}
