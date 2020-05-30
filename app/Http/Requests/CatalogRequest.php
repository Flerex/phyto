<?php

namespace App\Http\Requests;

use App\Domain\Models\Species;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;

class CatalogRequest extends FormRequest
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

        $catalog = $this->route('catalog'); // Might not have it if we're creating the model

        return [
            'name' => [
                'required',
                'min:5',
                Rule::unique('catalogs')->ignore($catalog),
            ],
            'species' => ['sometimes', 'array', 'distinct', 'min:1'],
            'domain' => ['sometimes', 'array', 'distinct', 'min:1'],
            'classis' => ['sometimes', 'array', 'distinct', 'min:1'],
            'genus' => ['sometimes', 'array', 'distinct', 'min:1'],
            'mode' => ['sometimes', Rule::in(['create', 'seal'])],
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            $validated = $validator->validated();

            $availableTypes = ['species', 'domain', 'classis', 'genera'];

            // List of the to-be-added nodes's categories
            $nodeTypesToBeAdded = collect(array_keys($validated));
            $nodeTypesToBeAdded = $nodeTypesToBeAdded->filter(function ($e) use ($availableTypes) {
                return in_array($e, $availableTypes);
            });


            if (!$this->catalogHasAtLeastOneNode($availableTypes, $nodeTypesToBeAdded)) {
                $validator->errors()->add('hierarchy', trans('validation.required', ['attribute' => 'hierarchy']));
            }

            if (!$this->allNodesExist($nodeTypesToBeAdded, $validated)) {
                $validator->errors()->add('hierarchy', trans('validation.exists', ['attribute' => 'hierarchy']));
            }

        });
    }

    private function catalogHasAtLeastOneNode(array $availableTypes, Collection $nodeTypesToBeAdded)
    {

        return $nodeTypesToBeAdded->first(function ($e) use ($availableTypes) {
                return in_array($e, $availableTypes);
            }) !== null;

    }

    private function allNodesExist(Collection $nodeTypesToBeAdded, array $validated)
    {

        foreach ($nodeTypesToBeAdded as $attribute) {

            $class = class_namespace(Species::class).'\\'.ucwords($attribute);

            $ids = collect($validated[$attribute]);

            $notValid = $ids->first(function ($id) use ($class) {
                return !$class::exists($id);
            });

            return $notValid == null;
        }

        return true;

    }
}
