<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClassroomRequest extends FormRequest
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
            'name' => ['required','string','max:255', function($attribute,$value ,$fail){
                 if ($value == 'admin'){
                    return $fail('This :attribute value is forbidden!');
                 }
            }],
            'section' => 'nullable|string|max:255',
            'subject' => 'nullable|string|max:255',
            'room' => 'nullable|string|max:255',
            'cover_image' => [
                'image',
                'dimensions:min_width:200,min_height=100,max_width:4000 ,max_height=4000',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'required' => ' :attribue is important',
            'name.required' => 'The name is required',
            'cover_image.max' => 'Image size is greater than 1M',
        ];
    }
}
