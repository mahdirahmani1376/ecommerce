<?php

namespace App\Http\Resources;

use App\Models\ProductVendor;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            'order_id' => $this->order_id,
            'state' => $this->state,
            'products_vendors' => ProductVendorResource::collection($this->whenLoaded('productsVendors')),
            'user' => UserResource::make($this->whenLoaded('user')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
