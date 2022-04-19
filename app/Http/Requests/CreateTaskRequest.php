<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|max:255',
            'instructions' => 'max:255',
            'points' => 'numeric',
            'access' => Rule::in(['public', 'private']),
            'deadline' => 'date',
            'pickedStudents' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'pickedStudents.required' => 'You must assign at least one student to task.'
        ];
    }
}
