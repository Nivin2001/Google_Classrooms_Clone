<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClassworkRequest extends FormRequest
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
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'topic_id' => ['nullable','int', 'exists:topics,id'],
            'options.grade' => ['required_if:type,assignment','nullable','numeric','min:0'],
            'options.due' => ['required_if:type,assignment','nullable' ,'date',' after:published_at']
        ];
    }
}
