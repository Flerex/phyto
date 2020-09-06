<?php

namespace App\Http\Controllers\Project;

use App\Domain\Models\BoundingBox;
use App\Domain\Models\Catalog;
use App\Domain\Models\Domain;
use App\Domain\Models\Project;
use App\Domain\Models\TaskAssignment;
use App\Domain\Models\TaskProcess;
use App\Domain\Services\TaxonomyService;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\Factory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AssignmentController extends Controller
{


    protected TaxonomyService $taxonomyService;

    /**
     * AssignmentController constructor.
     * @param  TaxonomyService  $taxonomyService
     */
    public function __construct(TaxonomyService $taxonomyService)
    {
        $this->taxonomyService = $taxonomyService;
    }

    /**
     * Handles the page that display the list of Task Assignments for the current member.
     *
     * @param  Project  $project
     * @param  Request  $request
     * @return Application|Factory|View
     */
    public function index(Project $project, Request $request)
    {
        $validated = $request->validate([
            'process' => ['sometimes', 'exists:task_processes,id']
        ]);

        $assignments = $this->getAssignmentsForProcess($project->getKey(), $validated['process'] ?? null);

        $processes = $this->getProcessesForCurrentUser();

        $process = $request->get('process');

        return view('projects.assignments.index', compact('project', 'assignments', 'processes', 'process'));
    }

    /**
     * Handles the request that sets the state of an assignment to finished.
     *
     * @param  TaskAssignment  $assignment
     * @param  Request  $request
     * @return Application|Factory|RedirectResponse|View
     * @throws AuthorizationException
     */
    public function finish(TaskAssignment $assignment, Request $request)
    {

        $this->authorize('work', $assignment);

        $assignment->finished = true;
        $assignment->save();

        return redirect()->back();
    }

    /**
     * Handles the request for the page that allows to tag an image in a given assignment.
     * @param  Project  $project
     * @param  TaskAssignment  $assignment
     * @param  Request  $request
     * @return Application|Factory|View
     */
    public function show(Project $project, TaskAssignment $assignment, Request $request)
    {

        $filtered = array_key_exists('filtered', $request->all()) ? $assignment->process->getKey() : null;

        $image = $assignment->image;

        $boxes = $assignment->boxes()->get();

        $images = $this->getAssignmentsForProcess($project->getKey(), $filtered, false, false)
            ->map(fn(TaskAssignment $a) => [
                'active' => $assignment->getKey() === $a->getKey(),
                'thumbnail_link' => asset($a->image->thumbnail_path),
                'finished' => $a->finished,
                'href' => route('projects.assignments.show',
                    ['project' => $project, 'assignment' => $a, 'filtered' => $filtered]),
            ]);

        $catalogs = $project->catalogs->map(function (Catalog $c) {
            $c->nodes = $c->nodes();
            $c->makeVisible('nodes');
            return $c;
        });

        $tree = $this->taxonomyService->getTree();

        return view('projects.assignments.show',
            compact('project', 'assignment', 'image', 'boxes', 'images', 'catalogs', 'tree'));
    }

    /**
     * Retrieves all the proccesses where the current user is involved and returns them in a format
     * compatible with the React component used in the filtering UI.
     *
     * @return mixed
     */
    private function getProcessesForCurrentUser()
    {
        return TaskProcess::whereHas('assignments', function (Builder $query) {
                $query->where('user_id', Auth::user()->getKey());
            })->get()
            ->map(function (TaskProcess $process) {
                return [
                    'label' => $process->getKey(),
                    'value' => $process->getKey(),
                ];
            })->prepend([
                'label' => trans('general.all'),
                'value' => null,
            ]);
    }


    /**
     * Retrieves all the assignments of the current user in the given project. If the process is given,
     * then only the assignments for that process will be returned.
     *
     * @param  int  $projectId
     * @param  int|null  $process
     * @param  bool  $paginated
     * @param  bool  $ordered
     * @return LengthAwarePaginator|Collection
     */
    private function getAssignmentsForProcess(int $projectId, int $process = null, bool $paginated = true, bool $ordered = true)
    {

        $assignmentsBuilder = TaskAssignment::where('user_id', Auth::user()->getKey())
            ->with('process')
            ->withCount([
                'boxes', 'boxes as untagged_count' => function (Builder $query) {
                    $query->whereNotNull('taggable_id');
                }
            ])
            ->whereHas('process.task', fn(Builder $query) => $query->where('project_id', $projectId));

        if ($process) {
            $assignmentsBuilder = $assignmentsBuilder->where('task_process_id', $process);
        }

        if($ordered) {
            $assignmentsBuilder= $assignmentsBuilder->orderBy('finished', 'asc');
        }

        return $paginated
            ? $assignmentsBuilder->paginate(config('phyto.pagination_size'))
            : $assignmentsBuilder->get();

    }
}
