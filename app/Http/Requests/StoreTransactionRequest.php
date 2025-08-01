<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreTransactionRequest extends FormRequest
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
            'value' => ['required', 'decimal:0,2'],
            'name'=> ['required', 'string'],
            'description'=> ['string'],
            'type' => ['required', Rule::in(['E', 'I']) ],
            'recurrent' => ['required', 'boolean'],
            'category' => ['required', 'string']
        ];
    }
}
