<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'product_vendors' => ['array'],
            'product_vendors.*.id' => ['required','exists:products_vendors,id'],
            'product_vendors.*.product_id' => ['required','exists:products,product_id'],
            'product_vendors.*.vendor_id' => ['required','exists:vendors,vendor_id'],
        ];
    }
}
