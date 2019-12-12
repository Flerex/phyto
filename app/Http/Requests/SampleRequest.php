<?php

namespace App\Http\Requests;

use App\Sample;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Validator;

class SampleRequest extends FormRequest
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
        return Sample::VALIDATION_RULES;
    }

    /**
     * Configure the validator instance.
     *
     * @param Validator $validator
     * @return void
     */
    public function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {
            $validated = $validator->validated();

            $files = collect($validated['files'])->map(function ($file) {
                return json_decode($file);
            });

            $existingFiles = $files->filter(function ($file) {
                return Storage::exists($file->path . $file->name);
            });

            if (count($files) !== count($existingFiles)) {
                $validator->errors()->add('files', trans('validation.custom.unreachable'));
            }

            $validated['files'] = $existingFiles;

            $validator->setData($validated);
        });
    }
}
