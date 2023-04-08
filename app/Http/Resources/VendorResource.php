<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Vendor */
class VendorResource extends JsonResource
{
    /**
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'vendor_id' => $this->vendor_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'media_count' => $this->media_count,
            'products_count' => $this->products_count,
            'products' => ProductResource::collection($this->whenLoaded('products')),
        ];
    }
}
