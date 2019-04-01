<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateCatalogRequest extends FormRequest
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
            'name' => ['required', 'min:5'],
            'species' => ['sometimes', 'array', 'distinct', 'min:1'],
            'domain' => ['sometimes', 'array', 'distinct', 'min:1'],
            'classis' => ['sometimes', 'array', 'distinct', 'min:1'],
            'genera' => ['sometimes', 'array', 'distinct', 'min:1'],
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            $validated = $validator->validated();

            foreach (['species', 'domain', 'classis', 'genera'] as $attribute) {

                if (!in_array($attribute, array_keys($validated))) {
                    continue;
                }

                $class = 'App\\' . ucwords($attribute);

                $ids = collect($validated[$attribute]);

                $notValid = $ids->first(function ($id) use ($class) {
                    return !$class::exists($id);
                });

                if ($notValid) {
                    $validator->errors()->add($attribute, trans('validation.exists', compact('attribute')));
                }


            }
        });
    }
}
