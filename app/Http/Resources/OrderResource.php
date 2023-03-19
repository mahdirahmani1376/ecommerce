<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'order_id' => $this->order_id,
            'state' => $this->state,
            'products' => ProductResource::collection($this->whenLoaded('products')),
            'user' => UserResource::make($this->whenLoaded('user')),
            'vendor' => VendorResource::make($this->whenLoaded('vendor')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
