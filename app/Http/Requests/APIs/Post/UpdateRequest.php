<?php

namespace App\Http\Requests\APIs\Post;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => [
                'required',
                'string',
                Rule::unique('posts', 'title')->ignore($this->route('post')),
            ],

            'description' => [
                'required',
                'string',
            ],

            'image' => [
                'nullable',
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
            'title.unique' => 'This title is already being used.',

            'description.required' => 'Description is required.',
            'description.string' => 'Description must be a string.',

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
