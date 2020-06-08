<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Validator;

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
            'users' => ['required', 'array', 'min:1'],
            'users.*' => ['exists:users,id'],
            'sample' => ['required', 'exists:samples,id'],
            'process_number' => ['required', 'int', 'min:1'],
        ];
    }


    /**
     * Configure the validator instance.
     *
     * @param  Validator  $validator
     * @return void
     */
    public function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {
            $this->membersAreFromProject($validator);

            if ($this->maxProcesses($validator)) {
                $validator->errors()->add('users', trans('panel.projects.tasks.must_be_members'));
            }
        });
    }

    /**
     * Users passed must be members of the project.
     *
     * @param  Validator  $validator
     * @throws ValidationException
     */
    private function membersAreFromProject(Validator $validator): void
    {
        $validated = $validator->validated();
        $project = $this->route('project');
        $members = $project->users()->findMany($validated['users'])->unique();

        if (count($members) == $validated['users']) {
            $validator->errors()->add('users', trans('panel.projects.tasks.must_be_members'));
        }

    }

    /**
     * Processes must not exceed the number of members divided by the members per image.
     *
     * @param  Validator  $validator
     * @throws ValidationException
     */
    private function maxProcesses(Validator $validator): void
    {
        $validated = $validator->validated();

        $membersCount = count($validated['users']);

        $minNecessaryUsers = $validated['process_number'];

        if ($membersCount < $minNecessaryUsers) {
            $validator->errors()->add('process_number',
                trans('panel.projects.tasks.process_max', ['value' => $minNecessaryUsers]));
        }

        trans('panel.projects.tasks.process_max', ['value' => $minNecessaryUsers]);
    }
}
