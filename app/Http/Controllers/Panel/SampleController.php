<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Project;
use Illuminate\Http\Request;

class SampleController extends Controller
{
    /**
     * Add new sample page.
     *
     * @param Project $project
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Project $project) {
        return view('panel.projects.samples.create', compact('project'));
    }

    /**
     * Add new sample page's form request.
     *
     * @param Project $project
     * @param Request $request
     * @return string
     */
    public function store(Project $project, Request $request)
    {



        return route('panel.projects.show', compact('project'));
    }
}
