<?php

namespace App\Http\Controllers\Panel;

use App\Domain\Models\Project;
use App\Domain\Models\Sample;
use App\Domain\Models\Task;
use App\Domain\Models\TaskAssignment;
use App\Domain\Models\TaskAutomatedAssignment;
use App\Domain\Models\TaskProcess;
use App\Http\Controllers\Controller;
use App\Jobs\SendAutomatedIdentificationRequestJob;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class AutomatedTaskController extends Controller
{

    public function __construct()
    {
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
            'sample' => ['required', 'exists:samples,id'],
            'services' => ['required', 'array', 'min:1'],
            'services.*' => [Rule::in($enabledServices->keys())],
        ]);

        DB::transaction(function () use ($project, $validated) {

            $sample = Sample::find($validated['sample']);

            $task = Task::create([
                'project_id' => $sample->project->getKey(),
                'sample_id' => $sample->getKey(),
                'automated' => true,
            ]);

            $assignments = collect();
            foreach ($validated['services'] as $service) {
                $process = TaskProcess::create(['task_id' => $task->getKey()]);
                foreach ($sample->images as $image) {
                    $assignment = TaskAssignment::create([
                        'task_process_id' => $process->getKey(),
                        'project_id' => $project->getKey(),
                        'service' => $service,
                        'image_id' => $image->getKey(),
                    ]);

                    $assignments->push($assignment);
                }
            }

            // We loop again to make we are not dispatching a job when the transaction rolls back.
            foreach ($assignments as $assignment) {
                SendAutomatedIdentificationRequestJob::dispatch($assignment);
            }
        });


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
