<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSmsOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => ['required', 'string', 'max:64'],
            'account_type' => ['nullable', 'string', 'max:25'],
            'quantity' => ['required', 'integer', 'min:1'],
            'pricing_scheme_id' => ['required', 'integer', 'exists:pricing_schemes,id'],
            'payment_method' => ['nullable', 'string', 'max:50'],
            'receipt' => ['nullable', 'string', 'max:64'],
            'reseller_id' => ['nullable', 'string', 'max:64'],
            'agent_id' => ['nullable', 'string', 'max:64'],
        ];
    }
}
