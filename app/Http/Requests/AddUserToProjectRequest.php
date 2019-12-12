<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddUserToProjectRequest extends FormRequest
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

        $project = $this->route('project');

        return [
            'users' => ['required', 'array', 'min:1'],
            'users[]' => ['exists:users,id', 'unique:project_user,user_id'],
        ];
    }
}
