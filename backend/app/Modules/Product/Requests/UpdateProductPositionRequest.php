<?php

namespace App\Modules\Product\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Modules\Product\Enums\TagColorEnum;

class UpdateProductPositionRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
          'oldPosition' => ['required', 'integer', 'min:0'],
          'newPosition' => ['required', 'integer', 'min:0', Rule::notIn([$this->input('oldPosition')])],
        ];
    }
}
