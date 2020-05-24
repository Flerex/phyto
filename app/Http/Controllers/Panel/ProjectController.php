<?php

namespace App\Http\Controllers\Panel;

use App\Domain\Models\Catalog;
use App\Domain\Enums\CatalogStatus;
use App\Domain\Enums\Permissions;
use App\Domain\Models\Sample;
use App\Domain\Models\Task;
use App\Domain\Models\TaskProcess;
use App\Http\Controllers\Controller;
use App\Http\Requests\AddUserToProjectRequest;
use App\Http\Requests\CreateProjectRequest;
use App\Domain\Models\Project;
use App\Domain\Services\ProjectService;
use App\Domain\Models\User;
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

        $canManageEverything = $user->hasPermissionTo(Permissions::MANAGE_ALL_PROJECTS()->getValue());

        $query = Project::with('manager')->withCount(['users', 'samples'])->latest();

        if(!$canManageEverything)
        {
            $query = $query->whereUserId($user->getKey());
        }

        $projects = $query->paginate(config('phyto.pagination_size'));


        return view('panel.projects.index', compact('projects', 'canManageEverything'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Factory|View
     */
    public function create()
    {
        $catalogs = Catalog::where('status', CatalogStatus::SEALED()->getValue())->get();

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
     * @param  Project  $project
     * @return Factory|View
     * @throws AuthorizationException
     */
    public function show(Project $project)
    {
        $this->authorize('view', $project);

        $totalProcesses = $project->tasks()
            ->withCount('processes')
            ->get()
            ->reduce(fn (?int $carry, Task $t) => $carry + $t->processes_count);

        $totalImages = $project->samples()
            ->withCount('images')
            ->get()
            ->reduce(fn (?int $carry, Sample $s) => $carry + $s->images_count);

        $stats = (object) [
            'totalMembers' => $project->users()->count(),
            'totalSamples' => $project->samples()->count(),
            'totalTasks' => $project->tasks()->count(),
            'totalProcesses' => $totalProcesses,
            'totalImages' => $totalImages,
            'unfinishedAssignments' => $project->unfinishedAssignments()->count(),
        ];
        return view('panel.projects.show', compact('project', 'stats'));
    }
}
