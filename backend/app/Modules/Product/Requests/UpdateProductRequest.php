<?php

namespace App\Modules\Product\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Modules\Product\Enums\TagColorEnum;

class UpdateProductRequest extends FormRequest
{
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
            'tag.color' => ['nullable', Rule::enum((TagColorEnum::class))],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'vat_rate' => $this->vatRate,
        ]);
    }
}
