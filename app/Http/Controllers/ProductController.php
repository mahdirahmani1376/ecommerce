<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreproductRequest;
use App\Http\Requests\UpdateproductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use OpenApi\Annotations as OA;

class ProductController extends Controller
{
    /**
     * @OA\Get(
     *      path="/products",
     *      operationId="index",
     *      tags={"Products"},
     *      summary="Get list of products",
     *      description="Returns list of products",
     *      security={{"sanctum": {}}},
     *
     *      @OA\Parameter(
     *          name="weight",
     *          required=false,
     *          in="query",
     *          description="weight of product",
     *
     *          @OA\Schema(
     *          type="integer",
     *          example="250"
     *          )),
     *
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *     )
     */
    public function index(Request $request)
    {
        return Response::json(ProductResource::collection(Product::Filter()
            ->with(['variation' => ['size', 'color', 'variationVendor'], 'category', 'brand', 'media'])
            ->paginate()));
    }

    /**
     * @OA\Post(
     *     path="/products",
     *     description="store a new product",
     *     operationId="store",
     *     tags={"Products"},
     *     summary="store a new product",
     *     security={{"sanctum": {}}},
     *     @OA\RequestBody(required=true,@OA\JsonContent(ref="#/components/schemas/StoreProductRequest")),
     *     @OA\Response(response=200,description="Successful operation",@OA\JsonContent()),
     *     @OA\Response(response=401,description="Unauthenticated",),
     *     @OA\Response(response=403,description="Forbidden",)
     * )
     */
    public function store(StoreproductRequest $request)
    {

        $validated = $request->validated();

        $product = Product::create($validated);

        if ($request->file('image')) {
            $image = $request->file('image');
            $product->addMedia($image)->toMediaCollection();
        }

        return Response::json(ProductResource::make($product->load('variation', 'category', 'brand')));
    }


    /**
     * @OA\Put(
     *     path="/products/{product}",
     *     description="update product request",
     *     summary="update product details",
     *     tags={"Products"},
     *     operationId="update",
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(@OA\JsonContent(ref="#/components/schemas/UpdateProductRequest")),
     *     @OA\Parameter(name="product",required=false,in="path",description="product id of product",
     *          @OA\Schema(type="integer",example="1")),
     *      @OA\Response(response=200,description="Successful operation",),
     *      @OA\Response(response=401,description="Unauthenticated",),
     *      @OA\Response(response=403,description="Forbidden")
     *     )
     * )
     */
    public function update(UpdateproductRequest $request, Product $product)
    {
        $validated = $request->validated();

        if ($request->file('image')) {
            $image = $request->file('image');
            $product->addMedia($image)->toMediaCollection();
        }

        $product->update($validated);

        return Response::json(ProductResource::make($product->load('variation', 'category', 'brand')));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return Response::json();
    }

    public function usersWishList(Product $product)
    {
        return Response::json(
            data: $product->usersWishlist
        );
    }
}
