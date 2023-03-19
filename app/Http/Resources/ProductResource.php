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
            'in_stock' => $this->in_stock,
            'weight' => $this->weight,
            'dimensions' => $this->dimensions,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'vendor' => VendorResource::collection($this->whenLoaded('vendors')),
            'orders' => OrderResource::collection($this->whenLoaded('orders')),
        ];
    }
}
