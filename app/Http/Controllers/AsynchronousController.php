<?php

namespace App\Http\Controllers;

use App\Catalog;
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

    /**
     * Searches users from a given query.
     *
     */
    public function search_users(Request $request)
    {

        $validated = $request->validate([
            'query' => 'sometimes|string',
        ]);

        if (!isset($validated['query'])) {
            return [];
        }


        $users = User::where(DB::raw('LOWER(name)'), 'like',
            '%' . strtolower($validated['query']) . '%')->limit(15)->get();

        return $users->map(function ($user) {
            return [
                'value' => $user->getKey(),
                'label' => $user->name,
            ];
        });

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

    /**
     * Handles the modification of the data of a node.
     *
     * @param Request $request
     * @return array
     * @throws \Illuminate\Validation\ValidationException
     */
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

    /**
     * Handles the addition of a node to the hierarchy.
     *
     * @param Request $request
     * @return array
     * @throws \Illuminate\Validation\ValidationException
     */
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

    /**
     * Obtains the hierarchy tree, specifying which nodes are selected in the catalog.
     *
     * @param Catalog $catalog
     * @return array
     */
    public function edit_catalog(Catalog $catalog)
    {

        $domains = Domain::with('children.children.children')->get()->toArray();

        $nodes = $catalog->nodes();

        foreach ($domains as &$domain) {
            $node = $nodes['domains']->first(function ($node) use ($domain) {
                return $domain['id'] === $node->id;
            });

            $domain['selected'] = $node !== null;


            foreach ($domain['children'] as &$classis) {
                $node = $nodes['classis']->first(function ($node) use ($classis) {
                    return $classis['id'] === $node->id;
                });

                $classis['selected'] = $node !== null;

                foreach ($classis['children'] as &$genus) {
                    $node = $nodes['genera']->first(function ($node) use ($genus) {
                        return $genus['id'] === $node->id;
                    });

                    $genus['selected'] = $node !== null;

                    foreach ($genus['children'] as &$species) {
                        $node = $nodes['species']->first(function ($node) use ($species) {
                            return $species['id'] === $node->id;
                        });

                        $species['selected'] = $node !== null;
                    }
                }
            }
        }

        return $domains;
    }

    /**
     * Searches users from a given query.
     *
     */
    public function search_users(Request $request)
    {

        $validated = $request->validate([
            'query' => 'sometimes|string',
        ]);

        if (!isset($validated['query'])) {
            return [];
        }


        $users = User::where(DB::raw('LOWER(name)'), 'like',
            '%' . strtolower($validated['query']) . '%')->limit(15)->get();

        return $users->map(function ($user) {
            return [
                'value' => $user->getKey(),
                'label' => $user->name,
            ];
        });

    }

}
