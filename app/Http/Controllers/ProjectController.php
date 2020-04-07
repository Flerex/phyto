<?php

namespace App\Http\Controllers;

use App\BoundingBox;
use App\Image;
use App\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    public function show(Project $project)
    {
        $images = $project->samples->pluck('images')->flatten();

        return view('projects.show', compact('project', 'images'));
    }

    public function tag(Project $project, Image $image)
    {
        $boxes = collect($image->boundingBoxes()->with('user')->get()->toArray())->map(function($bb) {
            $bb['user'] = $bb['user']['name'];
            return $bb;
        });

        $lang = trans('tagger');

        return view('projects.tag', compact('project', 'image', 'boxes', 'lang'));
    }

    public function create_bounding_box(Project $project, Image $image, Request $request)
    {
        $validated = $request->validate(BoundingBox::RULES);

        $validated['user_id'] = Auth::user()->getKey();
        $validated['image_id'] = $image->getKey();

        $bb = BoundingBox::create($validated);

        return $bb;
    }
}
