<?php

namespace App\Http\Controllers\Project;

use App\Domain\Models\Image;
use App\Domain\Models\Project;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
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
    public function view_image(Project $project, Image $image)
    {
        $this->authorize('access', $project);

        $images = $project->samples->pluck('images')
            ->flatten()
            ->filter(fn(Image $img) => !is_null($img->path))
            ->map(function (Image $img) use ($image, $project) {
                $img->active = $image->getKey() === $img->getKey();
                $img->href = route('projects.images.show', ['project' => $project, 'image' => $img]);
                $img->thumbnail_link = asset($img->thumbnail_path);
                return $img;
            })
            ->values();

        return view('projects.view_image', compact('project', 'image', 'images'));
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
            ->withPivot('created_at')
            ->orderBy('pivot_created_at', 'desc')
            ->get();

        return view('projects.members', compact('project', 'members'));
    }

}
