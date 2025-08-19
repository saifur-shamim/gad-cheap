<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validates search filter requests.
 */
class FilterSearchRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'query' => 'required|string|min:1',
            'type'  => 'nullable|in:all,products,brands,categories',
            'limit' => 'nullable|integer|min:1|max:100',
        ];
    }
}
