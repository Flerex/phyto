<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Project;
use App\Sample;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ImageController extends Controller
{

    /**
     * Image list of a given sample.
     *
     * @param Project $project
     * @param Sample $sample
     * @return Factory|View
     */
    public function index(Project $project, Sample $sample)
    {
        $images = $sample->images;
        return view('panel.projects.images.index', compact('project', 'sample', 'images'));
    }
}
