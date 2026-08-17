<?php

namespace App\Http\Requests\APIs\Post;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => [
                'required',
                'string',
            ],

            'description' => [
                'required',
                'string',
            ],

            'image' => [
                'required',
                'file',
                'mimes:png,jpg,jpeg,gif',
                'max:2048',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Title is required.',
            'title.string' => 'Title must be a string.',

            'description.required' => 'Description is required.',
            'description.string' => 'Description must be a string.',

            'image.required' => 'Image is required.',
            'image.file' => 'The uploaded image is invalid.',
            'image.mimes' => 'Image must be a png, jpg, jpeg, or gif file.',
            'image.max' => 'Image size must not exceed 2 MB.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'status' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422)
        );
    }
}
