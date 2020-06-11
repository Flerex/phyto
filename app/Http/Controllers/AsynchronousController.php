<?php

namespace App\Http\Controllers;

use App\Domain\Models\Catalog;
use App\Domain\Models\Domain;
use App\Domain\Models\Image;
use App\Domain\Models\Project;
use App\Domain\Models\Sample;
use App\Domain\Models\Species;
use App\Domain\Models\Task;
use App\Domain\Models\User;
use App\Domain\Services\TaxonomyService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class AsynchronousController extends Controller
{

    protected TaxonomyService $taxonomyService;

    /**
     * AsynchronousController constructor.
     * @param  TaxonomyService  $taxonomyService
     */
    public function __construct(TaxonomyService $taxonomyService)
    {
        $this->middleware('auth');

        $this->taxonomyService = $taxonomyService;

    }

    /**
     * Search users from a given query.
     * @param  Request  $request
     * @return Collection
     */
    public function search_users(Request $request)
    {
        $validated = $request->validate([
            'query' => ['sometimes', 'nullable', 'string'],
            'ids' => ['sometimes', 'array', 'min:1'],
            'ids.*' => ['exists:users,id'],
        ]);


        if (key_exists('query', $validated)) {
            $users = User::where(DB::raw('LOWER(name)'), 'like',
                '%'.strtolower($validated['query']).'%')->limit(15)->get();
        } elseif (key_exists('ids', $validated)) {
            $users = User::whereIn('id', $validated['ids'])->get();
        } else {
            $users = User::latest()->limit(15)->get();
        }

        return $users->map(function ($user) {
            return [
                'value' => $user->getKey(),
                'label' => $user->name,
            ];
        });

    }

    /**
     * Retrieves the taxonomy tree.
     *
     * @return Collection
     */
    public function species(): Collection
    {
        return $this->taxonomyService->getTree();
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
     * @param  Request  $request
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


        $namespace = class_namespace(Species::class); // Obtain the entity namespace from an example entity.

        $className = $namespace.'\\'.ucwords($validated['type']);

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
     * @param  Request  $request
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
            $namespace = class_namespace(Species::class);
            $className = $namespace.'\\'.ucwords($validated['type']);

            $data[self::getRelationships()[$validated['type']].'_id'] = $validated['parent'];

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

            $model = class_namespace(Species::class).'\\'.ucwords($attr['type']);

            if ($model::find($attr['id']) === null) {
                $validator->errors()->add('id', trans('taxonomy.errors.id.exists'));
            }


        });
    }

    private function checkParentExists(Validator $validator)
    {
        $validator->after(function ($validator) {

            $attr = $validator->attributes();

            if ($attr['type'] !== 'domain') {
                $parentModel = class_namespace(Species::class).'\\'.ucwords(self::getRelationships()[$attr['type']]);

                if ($parentModel::find($attr['parent']) === null) {
                    $validator->errors()->add('parent', trans('taxonomy.errors.parent.exists'));
                }
            }

        });
    }

    private function checkNameIsUnique(Validator $validator)
    {
        $validator->after(function ($validator) {

            $attr = $validator->attributes();

            $model = class_namespace(Species::class).'\\'.ucwords($attr['type']);


            if ($model::whereName($attr['name'])->first() !== null) {
                $validator->errors()->add('name', trans('taxonomy.errors.name.unique'));
            }

        });
    }

    /**
     * Searches samples from a given query.
     * @param  Project  $project
     * @param  Request  $request
     * @return Collection
     * @throws AuthorizationException
     */
    public function search_samples(Project $project, Request $request)
    {
        $validated = $request->validate([
            'query' => ['sometimes', 'nullable', 'string'],
            'ids' => ['sometimes', 'array', 'min:1'],
            'ids.*' => ['exists:samples,id'],
        ]);

        $this->authorize('view', $project);

        $samples = Sample::where('project_id', $project->getKey())->latest();

        if (key_exists('query', $validated)) {
            $samples = $samples->where(DB::raw('LOWER(name)'), 'like',
                '%'.strtolower($validated['query']).'%')->limit(15)->get();
        } elseif (key_exists('ids', $validated)) {
            $samples = Sample::whereIn('id', $validated['ids'])->get();
        } else {
            $samples = $samples->limit(15)->get();
        }

        return $samples->map(function ($sample) {
            return [
                'value' => $sample->getKey(),
                'label' => $sample->name,
            ];
        });
    }

    /**
     * Searches tasks for a given sample.
     * @param  Project  $project
     * @param  Request  $request
     * @return Collection
     * @throws AuthorizationException
     */
    public function search_tasks(Project $project, Request $request)
    {
        $validated = $request->validate([
            'sample' => ['required', 'exists:samples,id'],
        ]);

        $this->authorize('view', $project);

        return Task::without('processes')
            ->with('sample')
            ->where('sample_id', $validated['sample'])
            ->get()
            ->map(function (Task $task) {
                $task->date = $task->created_at->format(config('phyto.date_format'));
                return $task;
            });
    }

    /**
     * Searches samples from a given query.
     * @param  Project  $project
     * @param  Request  $request
     * @return Collection
     * @throws AuthorizationException
     */
    public function search_members(Project $project, Request $request)
    {
        $validated = $request->validate([
            'query' => ['sometimes', 'nullable', 'string'],
            'ids' => ['sometimes', 'array', 'min:1'],
            'ids.*' => ['exists:users,id'],
        ]);

        $this->authorize('view', $project);

        $members = $project->users()->latest();


        if (key_exists('query', $validated)) {
            $members = $members->where(DB::raw('LOWER(name)'), 'like',
                '%'.strtolower($validated['query']).'%')->limit(15)->get();
        } elseif (key_exists('ids', $validated)) {
            $members = $members->whereIn('users.id', $validated['ids'])->get();
        } else {
            $members = $members->limit(15)->get();
        }

        return $members->map(function ($sample) {
            return [
                'value' => $sample->getKey(),
                'label' => $sample->name,
            ];
        });
    }
}
