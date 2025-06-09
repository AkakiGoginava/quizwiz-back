<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CheckUniqueRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'field' => ['required', Rule::in(['name', 'email'])],
            'value' => [
                'required',
                'string',
                'min:3',
                Rule::when($this->input('field') === 'email', ['email'])],
        ];
    }

    public function messages(): array
    {
        return [
            'field.in' => 'Invalid field for uniqueness check.',
        ];
    }
}
