<?php

namespace App\Http\Controllers;

use App\Classis;
use App\Domain;
use App\Genus;
use App\Species;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;

class AsynchronousController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function species()
    {
        // TODO: add permissions
        return Domain::with('classis.genera.species')->get();
    }

    public function add_to_hierarchy(Request $request)
    {
        // TODO: add permissions
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3',
            'type' => [
                'required',
                Rule::in(['genus', 'classis', 'species', 'domain']), // Valid hierarchy members to be created
            ],
            'parent' => 'int',

        ]);

        // TODO: add validation for parent (it has to exist)
        // TODO: Refactor elements from hierarchy to only one model that was a foreign key to itself

        if ($validator->fails()) {
            abort(400);
        }

        $validated = $validator->validated();


        $data = [
            'name' => $validated['name']
        ];

        $el = null;

        switch ($validated['type']) {

            case 'domain':
                $el = Domain::create($data);
                break;

            case 'classis':
                $data['domain_id'] = $validated['parent'];
                $el = Classis::create($data);
                break;

            case 'genus':
                $data['classis_id'] = $validated['parent'];
                $el = Genus::create($data);
                break;

            case 'species':
                $data['genus_id'] = $validated['parent'];
                $el = Species::create($data);
                break;
        }

        return [
            'data' => [
                'id' => $el->getKey(),
            ]
        ];

    }
}
