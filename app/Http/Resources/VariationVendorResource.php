<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\VariationVendor */
class VariationVendorResource extends JsonResource
{
    /**
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'variation_vendor_id' => $this->variation_vendor_id,
            'price' => $this->price,
            'discounted_price' => $this->discounted_price,
            'stock' => $this->stock,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'baskets_variations_count' => $this->baskets_variations_count,

            'variation_id' => $this->variation_id,
            'vendor_id' => $this->vendor_id,

            'Vendor' => new VendorResource($this->whenLoaded('Vendor')),
            'variation' => new VariationResource($this->whenLoaded('variation')),
        ];
    }
}
