<?php

namespace App\Http\Controllers\Panel;

use App\Catalog;
use App\Enums\CatalogStatus;
use App\Enums\Permissions;
use App\Http\Controllers\Controller;
use App\Http\Requests\AddUserToProjectRequest;
use App\Http\Requests\CreateProjectRequest;
use App\Project;
use App\Services\ProjectService;
use App\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProjectController extends Controller
{

    /** @var ProjectService $projectService */
    protected $projectService;

    public function __construct(ProjectService $projectService)
    {
        $this->projectService = $projectService;

    }


    /**
     * Display a listing of the resource.
     *
     * @return Factory|View
     */
    public function index()
    {

        $user = Auth::user();

        $canManageEverything = $user->hasPermissionTo(Permissions::MANAGE_ALL_PROJECTS);

        $projects = $canManageEverything
            ? Project::orderBy('id')->paginate(config('phyto.pagination_size'))
            : Project::whereUserId($user->getKey())->orderBy('id')->paginate(config('phyto.pagination_size'));

        return view('panel.projects.index', compact('projects', 'canManageEverything'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Factory|View
     */
    public function create()
    {
        $catalogs = Catalog::where('status', CatalogStatus::SEALED)->get();

        return view('panel.projects.create', compact('catalogs'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateProjectRequest $request
     * @return RedirectResponse
     */
    public function store(CreateProjectRequest $request)
    {
        $validated = $request->validated();

        $currentUser = Auth::user();

        $filteredUsers = collect($validated['users'])->diff(collect($currentUser->getKey()));

        if($filteredUsers->count() === 0) {
            return redirect()->back()->withErrors(['users' => trans('panel.projects.cannot_be_a_member_yourself')]);
        }

        $project = $this->projectService->createProject($validated['name'], $validated['description'],
            $currentUser->getKey(), collect($validated['catalogs']), $filteredUsers);

        return redirect()
            ->route('panel.projects.index')
            ->with('alert', trans('panel.projects.create_alert', ['name' => $project->name]));


    }

    /**
     * Display the specified resource.
     *
     * @param \App\Project $project
     * @return Factory|View
     * @throws AuthorizationException
     */
    public function show(Project $project)
    {
        $this->authorize('view', $project);

        $stats = (object) [
            'totalMembers' => $project->users()->count(),
            'totalSamples' => $project->samples()->count(),
        ];
        return view('panel.projects.show', compact('project', 'stats'));
    }


    /**
     * Add new user to project page.
     *
     * @param Project $project
     * @return string
     * @throws AuthorizationException
     */
    public function add_user(Project $project)
    {
        $this->authorize('add_user', $project);

        return view('panel.projects.add-user', compact('project'));
    }


    public function add_user_store(Project $project, AddUserToProjectRequest $request)
    {
        $this->authorize('view', $project);

        $validated = $request->validated();

        $alreadyAdded = $project->users
            ->push($project->manager)
            ->pluck('id');


        $filteredUsers = collect($validated['users'])->diff($alreadyAdded);

        $project->users()->attach($filteredUsers);

        return redirect()->route('panel.projects.members.index', compact('project'));
    }

    public function before(User $user, $ability)
    {
        if ($user->can(Permissions::MANAGE_ALL_PROJECTS)) {
            return true;
        }
    }
}
