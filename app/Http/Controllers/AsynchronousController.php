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

    public function edit_node(Request $request)
    {


        $validator = $this->validateEditRequest($request);

        if ($validator->fails()) {
            return [
                'success' => false,
                'message' => $validator->errors()->first(),
            ];
        }

        $validated = $validator->validated();

        $className = 'App\\' . ucwords($validated['type']);

        $el = $className::find($validated['id']);

        $el->name = $validated['name'];

        $el->save();

        return [
            'success' => 'true',
            'data' => [
                'name' => $el->name,
            ]
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


        $data = [
            'name' => $validated['name']
        ];

        $el = null;

        if ($validated['type'] === 'domain') {
            $el = Domain::create($data);
        } else {
            $className = 'App\\' . ucwords($validated['type']);

            $data[self::getRelationships()[$validated['type']] . '_id'] = $validated['parent'];

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

        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
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

    private function validateEditRequest(Request $request)
    {

        $relationships = self::getRelationships();

        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'name' => 'required|min:3',
            'type' => [
                'required',
                Rule::in(array_keys($relationships)),
            ],
            'id' => 'int',
        ]);

        $this->checkNodeExists($validator);
        $this->checkNameIsUnique($validator);

        return $validator;
    }

    private function checkNodeExists(Validator $validator)
    {
        $validator->after(function ($validator) {

            $attr = $validator->attributes();

            $model = 'App\\' . ucwords($attr['type']);

            if ($model::find($attr['id']) === null) {
                $validator->errors()->add('id', trans('hierarchy_selector.errors.id.exists'));
            }


        });
    }

    private function checkParentExists(Validator $validator)
    {
        $validator->after(function ($validator) {

            $attr = $validator->attributes();

            if ($attr['type'] !== 'domain') {
                $parentModel = 'App\\' . ucwords(self::getRelationships()[$attr['type']]);

                if ($parentModel::find($attr['parent']) === null) {
                    $validator->errors()->add('parent', trans('hierarchy_selector.errors.parent.exists'));
                }
            }

        });
    }

    private function checkNameIsUnique(Validator $validator)
    {
        $validator->after(function ($validator) {

            $attr = $validator->attributes();

            $model = 'App\\' . ucwords($attr['type']);


            if ($model::whereName($attr['name'])->first() !== null) {
                $validator->errors()->add('name', trans('hierarchy_selector.errors.name.unique'));
            }

        });
    }

}
