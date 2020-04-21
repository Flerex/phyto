<?php

namespace App\Http\Controllers;

use App\BoundingBox;
use App\Domain;
use App\Image;
use App\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{

    public function show(Project $project)
    {
        $this->authorize('access', $project);

        $images = $project->samples->pluck('images')->flatten();

        return view('projects.show', compact('project', 'images'));
    }

    public function tag(Project $project, Image $image)
    {
        $this->authorize('access', $project);

        $boxes = collect($image->boundingBoxes()->with('user', 'taggable')->get()->toArray())->map(function($bb) {
            $bb['user'] = $bb['user']['name'];
            return $bb;
        });

        $lang = trans('tagger');

        return view('projects.tag', compact('project', 'image', 'boxes', 'lang'));
    }

}
