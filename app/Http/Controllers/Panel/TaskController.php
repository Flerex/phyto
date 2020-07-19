<?php

namespace App\Http\Controllers\Panel;

use App\Domain\Models\Sample;
use App\Domain\Models\Task;
use App\Domain\Models\TaskAssignment;
use App\Domain\Models\TaskProcess;
use App\Domain\Models\User;
use App\Domain\Services\TaskService;
use App\Domain\Services\TaxonomyService;
use App\Exceptions\NotEnoughMembersForProcessException;
use App\Http\Controllers\Controller;
use App\Domain\Models\Project;
use App\Http\Requests\CreateTaskRequest;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class TaskController extends Controller
{

    protected TaskService $taskService;

    protected TaxonomyService $taxonomyService;

    public function __construct(TaskService $taskService, TaxonomyService $taxonomyService)
    {
        $this->taskService = $taskService;
        $this->taxonomyService = $taxonomyService;
    }

    /**
     * List of tasks of a project.
     *
     * @param  Project  $project
     * @return Application|Factory|View
     * @throws AuthorizationException
     */
    public function index(Project $project)
    {
        $this->authorize('view', $project);

        $tasks = $project->tasks;

        return view('panel.projects.tasks.index', compact('project', 'tasks'));
    }

    /**
     * Show a specific task of a project.
     *
     * @param  Project  $project
     * @param  Task  $task
     * @return Application|Factory|View
     * @throws AuthorizationException
     */
    public function show(Project $project, Task $task)
    {
        $this->authorize('view', $task->project);

        $task->with(['processes.assignments.user']);

        $processes = $task->processes;

        return view('panel.projects.processes.index', compact('project', 'processes'));
    }

    /**
     * Create a new task in a project.
     *
     * @param  Project  $project
     * @return Application|Factory|View
     */
    public function create(Project $project)
    {
        $this->authorize('view', $project);

        return view('panel.projects.tasks.create', compact('project'));
    }

    /**
     * Manage the create task request.
     *
     * @param  Project  $project
     * @param  CreateTaskRequest  $request
     * @return RedirectResponse
     * @throws AuthorizationException|ValidationException
     */
    public function store(Project $project, CreateTaskRequest $request)
    {
        $this->authorize('view', $project);

        $validated = $request->validated();

        // We make sure we get only users and samples belonging to the project
        $users = $project->users()->findMany($validated['users'])->unique();

        /** @var Sample $sample */
        $sample = $project->samples()->find($validated['sample']);

        $compatibility = isset($validated['compatibility'])
            ? $project->tasks()->findMany($validated['compatibility'])->unique()
            : collect();

        try {

            $this->taskService->create_task($validated['description'], $sample, $users, $compatibility, $validated['process_number']);
        } catch (NotEnoughMembersForProcessException $e) {
            throw ValidationException::withMessages([
                'process_number' => [trans('panel.projects.tasks.not_enough_members_for_assignments')]
            ]);
        }

        return redirect()->route('panel.projects.tasks.index', compact('project'))
            ->with('alert', trans('panel.projects.tasks.created_alert'));;
    }

    /**
     * Show a list of assignments inside a process.
     *
     * @param  Project  $project
     * @param  Task  $task
     * @param  TaskProcess  $process
     * @return Application|Factory|View
     * @throws AuthorizationException
     */
    public function show_process(Project $project, Task $task, TaskProcess $process)
    {
        $this->authorize('view', $task->project);

        $assignments = $process->assignments()->paginate(config('phyto.pagination_size'));

        $assignees = null;

        if (!$task->automated) {
            $assignees = $process->assignments
                ->groupBy(fn(TaskAssignment $assignment) => $assignment->user->getKey())
                ->map(function (Collection $group) {
                    $images = count($group);
                    $finished = $group->filter(fn(TaskAssignment $assignment) => $assignment->finished);
                    $percentage = round($finished->count() / $images, 2) * 100;

                    return (object) [
                        'user' => $group[0]->user->name,
                        'images' => $images,
                        'percentage' => $percentage,
                    ];
                });
        }

        return view('panel.projects.processes.show', compact('project', 'process', 'task', 'assignments', 'assignees'));
    }

    /**
     * Show the progress of the assignment.
     *
     * @param  Project  $project
     * @param  Task  $task
     * @param  TaskProcess  $process
     * @param  TaskAssignment  $assignment
     * @return Application|Factory|View
     * @throws AuthorizationException
     */
    public function show_assignment(Project $project, Task $task, TaskProcess $process, TaskAssignment $assignment)
    {
        $this->authorize('view', $task->project);

        $image = $assignment->image;
        $boxes = $assignment->boxes()->get();
        $tree = $this->taxonomyService->getTree();
        $assignments = $process->assignments()->paginate(config('phyto.pagination_size'));

        return view('panel.projects.assignments.show',
            compact('project', 'process', 'assignment', 'assignments', 'tree', 'boxes', 'image'));
    }
}
