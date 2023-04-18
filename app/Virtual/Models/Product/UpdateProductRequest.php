<?php

namespace App\Virtual\Models\Product;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     title="Update Product Reqeust",
 *     description="Update Product request body Data",
 *     type="object",
 *     required={"name","weight"}
 * )
 */
class UpdateProductRequest
{
    /**
     * @OA\Property(
     *     title="name",
     *     description="name of the product",
     *     example="test"
     * )
     */
    public $name;

    /**
     * @OA\Property(
     *     title="rating",
     *     description="rating of the product",
     *     example="2"
     * )
     */
    public int $rating;

    /**
     * @OA\Property(
     *     title="description",
     *     description="description of the product",
     *     example="test"
     * )
     */
    public string $description;

    /**
     * @OA\Property(
     *     title="weight",
     *     description="weight of the product",
     *     example="100"
     * )
     */
    public $weight;

    /**
     * @OA\Property(
     *     title="brand_id",
     *     description="brand_id of the product",
     *     example="1"
     * )
     */
    public $brand_id;

    /**
     * @OA\Property(
     *     title="category_id",
     *     description="category_id of the product",
     *     example="1"
     * )
     */
    public $category_id;
}
