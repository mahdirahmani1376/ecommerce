<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Response::json(Category::with('products','parentCategories')->get());
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        $validated = $request->validated();
        $category = Category::create($validated);

        return Response::json(CategoryResource::make($category->load('products','parentCategories')));
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        return Response::json(CategoryResource::make($category->load('products','parentCategories')));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $validated = $request->validated();
        Category::update($validated);

        return Response::json(CategoryResource::make($category->load('products','parentCategories')));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete();

        return Response::json([
            'message' => 'Category deleted successfully'
        ]);
    }
}
