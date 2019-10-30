<?php

namespace App\Http\Controllers\Panel;

use App\Catalog;
use App\Enums\Permissions;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateProjectRequest;
use App\Project;
use App\Services\ProjectService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
     * @return \Illuminate\Http\Response
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
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $catalogs = Catalog::all();

        return view('panel.projects.create', compact('catalogs'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateProjectRequest $request)
    {
        $validated = $request->validated();

        $project = $this->projectService->createProject($validated['name'], $validated['description'],
            Auth::user()->getKey(),
            collect($validated['catalogs']), collect($validated['users']));

        return redirect()
            ->route('panel.projects.index')
            ->with('alert', trans('panel.projects.create_alert', ['name' => $project->name]));


    }

    /**
     * Display the specified resource.
     *
     * @param \App\Project $project
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project)
    {
        return view('panel.projects.show', compact('project'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Project $project
     * @return \Illuminate\Http\Response
     */
    public function edit(Project $project)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Project $project
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Project $project)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Project $project
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project)
    {
        //
    }


    /**
     * Add new user to project page.
     *
     * @param Project $project
     * @return string
     */
    public function add_user(Project $project)
    {
        // Fixme: Should only be accessible by project managers.

        return view('panel.projects.add-user', compact('project'));
    }


    public function add_user_store(Project $project, Request $request)
    {
        $validated = $request->validate([
            'users' => ['required', 'array', 'min:1', 'exists:users,id'],
        ]);

        $alreadyAdded = $project->users
            ->push($project->manager)
            ->pluck('id');

        $filteredUsers = collect($validated['users'])->filter(function ($id) use ($alreadyAdded) {
            return !$alreadyAdded->contains($id);
        });

        // fixme: filtering not working

        $project->users()->attach($filteredUsers);

        return redirect()->route('panel.projects.show', compact('project'));
    }
}
