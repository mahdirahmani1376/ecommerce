<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddToBasketRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'products' => ['array'],
            'products.*.product_id' => ['required', 'exists:products,product_id'],
            'products.*.vendor_id' => ['required', 'exists:vendors,vendor_id'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
