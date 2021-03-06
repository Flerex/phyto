<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Exists;

class CreateProjectRequest extends FormRequest
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
            'name' => ['required', 'unique:projects', 'min:5', 'string'],
            'description' => ['required', 'min:5', 'string'],
            'catalogs' => ['required', 'array', 'min:1', 'exists:catalogs,id'],
            'users' => ['required', 'array', 'min:1'],
            'users.*' => ['exists:users,id'],
        ];
    }
}
