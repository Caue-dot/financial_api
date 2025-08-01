<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTransactionRequest extends FormRequest
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
            'value' => ['sometimes','required', 'decimal:0,2'],
            'name'=> ['sometimes','required', 'string'],
            'description' => ['sometimes', 'string'],
            'type' => ['sometimes','required', Rule::in(['E', 'I']) ],
            'recurrent' => ['sometimes','required', 'boolean'],
            'category' => ['sometimes', 'required', 'string']
        ];
    }
}
