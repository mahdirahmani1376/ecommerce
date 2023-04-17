<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Variation */
class VariationResource extends JsonResource
{
    /**
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'variation_id' => $this->variation_id,
            'size' => SizeResource::make($this->whenLoaded('size')),
            'color_id' => ColorResource::make($this->whenLoaded('color')),
            'variation_vendor' => VariationVendorResource::collection($this->whenLoaded('variationVendor')),
            'product' => ProductResource::make($this->whenLoaded('Product')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'variation_vendor_count' => $this->variation_vendor_count,
        ];
    }
}
