<?php

namespace App\Http\Controllers;

use App\Domain\Models\Project;
use App\Domain\Models\TaskAssignment;
use App\Domain\Models\TaskProcess;
use Illuminate\Contracts\View\Factory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return Factory|View
     */
    public function index()
    {

        $user = Auth::user();



        // Projects
        $projects = $user->projects()->withCount([
            'unfinishedAssignments' => function (Builder $query) use ($user) {
                $query->where('user_id', $user->getKey());
            }
        ])->get();

        $projects = $projects
            ->union($user->managedProjects)
            ->unique('id')
            ->sortByDesc('unfinished_assignments_count');


        // Processes
        $processes = TaskProcess::with('task.project')
            ->withCount([
                'unfinishedAssignments' => function (Builder $query) use ($user) {
                    $query->where('user_id', $user->getKey());
                }
            ])
            ->whereHas('assignments', function (Builder $query) use ($user) {
                $query->where('user_id', $user->getKey());
            })
            ->unfinished()
            ->latest()
            ->get();



        // Assignments
        $assignmentsBuilder = TaskAssignment::with('project', 'process')
            ->where('user_id', $user->getKey())
            ->unfinished();

        $assignments = $assignmentsBuilder
            ->inRandomOrder()
            ->take(config('phyto.random_assignments_count'))
            ->get();

        $assignmentsCount = $assignmentsBuilder->count();

        return view('home', compact('projects', 'assignments', 'assignmentsCount', 'processes'));
    }
}
