<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'product_id' => $this->product_id,
            'name' => $this->name,
            'rating' => $this->rating,
            'description' => $this->description,
            'weight' => $this->weight,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'category' => CategoryResource::make($this->whenLoaded('category')),
            'vendor' => VendorResource::collection($this->whenLoaded('vendors')),
            'orders' => OrderResource::collection($this->whenLoaded('orders')),
        ];
    }
}
