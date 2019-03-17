<?php

namespace App\Http\Controllers;

use App\Domain;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class AsynchronousController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function species()
    {
        // TODO: add permissions
        return Domain::with('children.children.children')->get();
    }

    private static function getRelationships()
    {
        /* Keys contain available node types, values are their parent relationships */
        return [
            'domain' => null,
            'classis' => 'domain',
            'genus' => 'classis',
            'species' => 'genus',
        ];
    }

    public function add_to_hierarchy(Request $request)
    {


        $validator = $this->validateRequest($request);

        if ($validator->fails()) {
            return [
                'success' => false,
                'message' => $validator->errors()->first(),
            ];
        }

        $validated = $validator->validated();


        // TODO: add validation for parent (it has to exist)
        // TODO: Refactor elements from hierarchy to only one model that has a foreign key to itself


        $data = [
            'name' => $validated['name']
        ];

        $el = null;

        if ($validated['type'] === 'domain') {
            $el = Domain::create($data);
        } else {
            $className = 'App\\' . ucwords($validated['type']);

            $data[self::getRelationships[$validated['type']] . '_id'] = $validated['parent'];

            $el = $className::create($data);
        }

        return [
            'success' => 'true',
            'data' => [
                'id' => $el->getKey(),
            ]
        ];

    }

    private function validateRequest(Request $request)
    {

        $relationships = self::getRelationships();

        // TODO: add permissions
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3',
            'type' => [
                'required',
                Rule::in(array_keys($relationships)),
            ],
            'parent' => 'int',

        ]);

        $this->checkParentExists($validator);

        $this->checkNameIsUnique($validator);

        return $validator;
    }

    private function checkParentExists(Validator $validator)
    {
        $validator->after(function ($validator) {

            $attr = $validator->attributes();

            if ($attr['type'] !== 'domain') {
                $parentModel = 'App\\' . ucwords(self::getRelationships()[$attr['type']]);

                if ($parentModel::find($attr['parent']) === null) {
                    $validator->errors()->add('parent', 'Parent does not exist.');
                }
            }

        });
    }

    private function checkNameIsUnique(Validator $validator)
    {
        $validator->after(function ($validator) {

            $attr = $validator->attributes();

            $model = 'App\\' . ucwords($attr['type']);

            if ($model::whereName($attr['name'])->first() === null) {
                $validator->errors()->add('name', 'Name is not unique.');
            }

        });
    }

}
