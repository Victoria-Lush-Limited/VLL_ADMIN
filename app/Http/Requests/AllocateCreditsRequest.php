<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AllocateCreditsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'username' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:255'],
        ];
    }
}
