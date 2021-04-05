<?php

namespace App\Http\Controllers\Panel;

use App\Domain\Models\Project;
use App\Domain\Models\Sample;
use App\Domain\Models\Task;
use App\Domain\Models\TaskAssignment;
use App\Domain\Models\TaskAutomatedAssignment;
use App\Domain\Models\TaskProcess;
use App\Domain\Services\TaskService;
use App\Http\Controllers\Controller;
use App\Jobs\SendAutomatedIdentificationRequestJob;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class AutomatedTaskController extends Controller
{

    protected TaskService $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;

        $this->middleware('automated-services');
    }

    public function create(Project $project)
    {
        $this->authorize('view', $project);

        $options = $this->getEnabledServices()->map(fn(array $service, $serviceCode) => [
            'label' => $service['name'] ?? $serviceCode,
            'value' => $serviceCode,
        ])->values();

        return view('panel.projects.automated-tasks.create', compact('project', 'options'));

    }

    public function store(Project $project, Request $request)
    {
        $this->authorize('view', $project);

        $enabledServices = $this->getEnabledServices();

        $validated = $request->validate([
            'description' => ['required', 'unique:tasks', 'string', 'min:3', 'max:25'],
            'sample' => ['required', 'exists:samples,id'],
            'services' => ['required', 'array', 'min:1'],
            'services.*' => [Rule::in($enabledServices->keys())],
        ]);

        $this->taskService->create_automated_task($validated['description'], Sample::find($validated['sample']),
            collect($validated['services']));


        return redirect()->route('panel.projects.tasks.index', compact('project'))
            ->with('alert', trans('panel.projects.tasks.created_alert'));;
    }

    /**
     * Gets a subset of the services that are currently enabled.
     *
     * @return Collection
     */
    private function getEnabledServices()
    {
        return $this->getServices(config('automated_identification.enabled'));
    }

    /**
     * Gets a subset of the services by the given keys.
     *
     * @param  array  $keys
     * @return Collection
     */
    private function getServices(array $keys)
    {
        return collect(config('automated_identification.services'))->intersectByKeys(collect($keys)->flip());
    }
}
