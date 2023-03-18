<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreproductRequest;
use App\Http\Requests\UpdateproductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Response::json(ProductResource::collection(Product::with('vendors','orders')->paginate()));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreproductRequest $request)
    {
        $validated = $request->validated();

        $product = Product::create($validated);

        if ($validated->image)
        {
            $image = $validated->image;
            $product->addMedia($image)->toMediaCollection();
        }

        return Response::json(ProductResource::make($product->with('orders','vendors')));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateproductRequest $request, Product $product)
    {
        $validated = $request->validated();

        if ($validated->image)
        {
            $image = $validated->image;
            $product->addMedia($image)->toMediaCollection();
        }

        $product->update($validated);

        return Response::json(ProductResource::make($product->with('orders','vendors')));

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return Response::json();
    }

}
