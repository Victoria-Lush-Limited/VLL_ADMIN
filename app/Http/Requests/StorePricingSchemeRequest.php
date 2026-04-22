<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePricingSchemeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'owner_user_id' => ['nullable', 'string', 'max:64'],
            'is_default' => ['sometimes', 'boolean'],
            'tiers' => ['nullable', 'array'],
            'tiers.*.min_sms' => ['required_with:tiers', 'integer', 'min:1'],
            'tiers.*.max_sms' => ['nullable', 'integer', 'min:1'],
            'tiers.*.price' => ['required_with:tiers', 'numeric', 'min:0'],
        ];
    }
}
