<?php

namespace App\Checkout\Presentation\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'items'                   => ['required', 'array', 'min:1'],
            'items.*.product_id'      => ['required', 'string'],
            'items.*.quantity'        => ['required', 'integer', 'min:1'],
            'items.*.price'           => ['required', 'integer', 'min:0'],
        ];
    }
}
