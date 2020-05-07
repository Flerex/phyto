<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Domain\Models\Project;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Project $project)
    {
        $tasks = $project->tasks;
        return view('panel.projects.tasks.index', compact('project', 'tasks'));
    }
}
