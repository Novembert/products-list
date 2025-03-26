<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Temporarily allow all requests
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
            'name' => ['required', 'string', 'max:128'],
            'description' => ['nullable', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0.01'],
            'vatRate' => ['required', 'numeric', 'min:0.00', 'max:1.00'],
            'tag.name' => ['nullable', 'string', 'max:128'],
            'tag.color' => ['nullable', Rule::in(['red', 'blue', 'green', 'yellow', 'purple', 'orange', 'black'])],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'vat_rate' => $this->vatRate,
        ]);
    }
}
