<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'order_id' => $this->order_id,
            'state' => $this->state,
            'user' => UserResource::make($this->whenLoaded('user')),
            'delivery' => $this->whenLoaded('delivery'),
            'basket' => $this->whenLoaded('basket'),
            'variationVendor' => $this->whenLoaded('variationVendor'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
