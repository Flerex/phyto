<?php

namespace App\Http\Controllers;

use App\Domain\Models\BoundingBox;
use App\Domain\Models\Image;
use App\Domain\Models\Project;
use App\Domain\Models\TaskAssignment;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProjectController extends Controller
{

    /**
     * Handles the page that displays the Overview of a Project to its members.
     *
     * @param  Project  $project
     *
     * @return Application|Factory|View
     * @throws AuthorizationException
     */
    public function show(Project $project)
    {
        $this->authorize('access', $project);

        $images = $project->samples->pluck('images')->flatten()->filter(fn(Image $img) => !is_null($img->path));

        return view('projects.show', compact('project', 'images'));
    }

    /**
     * Handles the page that displays the Boxer UI.
     *
     * @param  Project  $project
     * @param  Image  $image
     *
     * @return Application|Factory|View
     * @throws AuthorizationException
     */
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

    /**
     * Handles the page that display the list of Task Assignments for the current member.
     *
     * @param  Project  $project
     */
    public function assignments(Project $project)
    {
        $assignments = TaskAssignment::where('user_id', Auth::user()->getKey())
            ->orderBy('finished', 'asc')
            ->with('process')
            ->whereHas('process.task', fn (Builder $query) => $query->where('project_id', $project->getKey()))
            ->paginate(config('phyto.pagination_size'));

        return view('projects.assignments', compact('project', 'assignments'));
    }

    /**
     * Handles the page that display the list of members in the project.
     *
     * @param  Project  $project
     */
    public function members(Project $project)
    {
        $members = $project->users()
            ->with('assignments', 'unfinishedAssignments')
            ->get();

        return view('projects.members', compact('project', 'members'));
    }

}
