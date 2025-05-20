<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class PostCreateRequest extends FormRequest
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
            'title' => 'required|string|max:55',
            'content' => 'required|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required.' => 'Заголовок поста обязателен',
            'title.max' => 'Заголовок должен быть не больше 55 символов',
            'content.required' => 'Описание поста обязательно',
            'content.max' => 'Описание поста должно быть не больше 255 символов',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'message' => 'Ошибка при создании поста',
                'errors' => $validator->errors()
            ], 422)
        );
    }

}
