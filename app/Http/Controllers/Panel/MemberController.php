<?php

namespace App\Http\Controllers\Panel;

use App\Domain\Services\ProjectService;
use App\Http\Controllers\Controller;
use App\Http\Requests\AddUserToProjectRequest;
use App\Domain\Models\Project;
use App\Domain\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;

class MemberController extends Controller
{

    protected ProjectService $projectService;

    public function __construct(ProjectService $projectService)
    {
        $this->projectService = $projectService;
    }

    public function index(Project $project)
    {

        $members = $this->projectService->get_members($project);

        return view('panel.projects.members.index', compact('project', 'members'));
    }

    public function change_status(Project $project, User $member, Request $request)
    {
        $validated = $request->validate([
            'active' => ['required', 'int', 'min:0', 'max:1'],
        ]);

        $this->projectService->set_member_status($project, $member, (bool) $validated['active']);

        return redirect()->back();
    }

    /**
     * Add new user to project page.
     *
     * @param Project $project
     * @return string
     * @throws AuthorizationException
     */
    public function create(Project $project)
    {
        $this->authorize('add_user', $project);

        return view('panel.projects.members.create', compact('project'));
    }


    public function store(Project $project, AddUserToProjectRequest $request)
    {
        $this->authorize('view', $project);

        $validated = $request->validated();

        $this->projectService->add_members($project, collect($validated['users']));

        return redirect()->route('panel.projects.members.index', compact('project'));
    }

}
