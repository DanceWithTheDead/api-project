<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostUpdateRequest extends FormRequest
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
            'title' => 'sometimes|string|max:55',
            'content' => 'sometimes|string|max:255'
        ];
    }

    public function messages(): array
    {
        return [
            'title.max' => 'The title may not be greater than 55 characters.',
            'content.max' => 'The content may not be greater than 255 characters.',
        ];
    }
}
