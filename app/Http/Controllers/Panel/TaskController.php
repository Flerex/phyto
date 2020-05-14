<?php

namespace App\Http\Controllers\Panel;

use App\Domain\Models\Sample;
use App\Domain\Models\Task;
use App\Domain\Models\TaskAssignment;
use App\Domain\Models\TaskProcess;
use App\Domain\Models\User;
use App\Domain\Services\TaskService;
use App\Http\Controllers\Controller;
use App\Domain\Models\Project;
use App\Http\Requests\CreateTaskRequest;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class TaskController extends Controller
{

    protected TaskService $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
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

        return view('panel.projects.tasks.show', compact('project', 'task'));
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
     * @throws AuthorizationException
     */
    public function store(Project $project, CreateTaskRequest $request)
    {
        $this->authorize('view', $project);

        $validated = $request->validated();

        // We make sure we get only users and samples belonging to the project
        $users = $project->users()->findMany($validated['users'])->unique();
        $sample = $project->samples()->find($validated['sample']);

        $this->taskService->create_task($sample, $users, $validated['repeat_images'], $validated['process_number']);


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

        $assignees = $process->assignments->groupBy(fn(TaskAssignment $assignment) => $assignment->user->getKey())
            ->map(function (Collection $group) {
                $images = count($group);
                $finished = $group->filter(fn(TaskAssignment $assignment) => $assignment->finished);
                $percentage = round($finished->count() / $images, 2);
                return (object) [
                    'user' => $group[0]->user->name,
                    'images' => $images,
                    'percentage' => $percentage,
                ];
            });

        return view('panel.projects.tasks.show_process', compact('project', 'process', 'assignments', 'assignees'));
    }
}
