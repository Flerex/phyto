<?php

namespace App\Http\Controllers;

use App\BoundingBox;
use App\Image;
use App\Project;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{

    public function show(Project $project)
    {
        $this->authorize('access', $project);

        $images = $project->samples->pluck('images')->flatten()->filter(fn(Image $img) => !is_null($img->path));

        return view('projects.show', compact('project', 'images'));
    }

    public function tag(Project $project, Image $image)
    {
        $this->authorize('access', $project);

        $boxes = collect($image->boundingBoxes()->with('user')->get()->toArray())->map(function ($bb) {
            $bb['user'] = $bb['user']['name'];
            return $bb;
        });

        $images = $project->samples->pluck('images')
            ->flatten()
            ->filter(fn(Image $img) => !is_null($img->path))
            ->map(function (Image $img) use ($image, $project) {
                $img->active = $image->getKey() === $img->getKey();
                $img->href = route('projects.images.tag', ['project' => $project, 'image' => $img]);
                $img->thumbnail_link = asset($img->thumbnail_path);
                return $img;
            })
            ->values();

        return view('projects.tag', compact('project', 'image', 'boxes', 'images'));
    }

}
