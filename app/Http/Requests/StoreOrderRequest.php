<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'     => 'required_without:customer_id|string|max:255',
            'mobile'   => 'required_without:customer_id|string|max:20',
            'note'     => 'nullable|string',
            'items'    => 'required|array|min:1',
            'items.*.item_id'  => 'required|exists:items,id',
            'items.*.size'     => 'required|string',
            'items.*.quantity' => 'required|integer|min:1',
        ];
    }
}
