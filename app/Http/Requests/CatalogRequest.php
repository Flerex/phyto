<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Request;
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
            'genera' => ['sometimes', 'array', 'distinct', 'min:1'],
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param \Illuminate\Validation\Validator $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            $validated = $validator->validated();

            $availableTypes = ['species', 'domain', 'classis', 'genera'];

            // List of the to-be-added nodes's categories
            $nodeTypesToBeAdded = collect(array_keys($validated));
            $nodeTypesToBeAdded = $nodeTypesToBeAdded->filter(function ($e) {
                return $e !== 'name';
            });


            if (!$this->catalogHasAtLeastOneNode($availableTypes, $nodeTypesToBeAdded)) {
                $validator->errors()->add('hierarchy', trans('validation.required', ['attribute' => 'hierarchy']));
            }

            if (!$this->allNodesExist($availableTypes, $nodeTypesToBeAdded, $validated)) {
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

    private function allNodesExist(array $availableTypes, Collection $nodeTypesToBeAdded, array $validated)
    {

        foreach ($availableTypes as $attribute) {

            // We check for this because unwanted fields can be sent through a request
            if (!$nodeTypesToBeAdded->contains($attribute)) {
                continue;
            }

            $class = 'App\\' . ucwords($attribute);

            $ids = collect($validated[$attribute]);

            $notValid = $ids->first(function ($id) use ($class) {
                return !$class::exists($id);
            });

            return $notValid == null;
        }

        return true;

    }
}
