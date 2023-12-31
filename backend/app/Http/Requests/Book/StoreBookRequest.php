<?php

namespace App\Http\Requests\Book;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name'  => 'required|string|max:255',
            'isbn'  => [
                'required',
                'numeric',
                'regex:/^(?=(?:.{10}|.{13})$)[0-9]*$/', //ISBN must be a number with 10 or 13 chars
                'unique:books' //no duplicated ISBN are allowed
            ],
            'value' => 'required|numeric|gt:0',
        ];
    }
}
