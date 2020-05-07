<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddUserToProjectRequest;
use App\Project;
use App\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function index(Project $project)
    {

        $members = $project->users;

        return view('panel.projects.members.index', compact('project', 'members'));
    }

    public function change_status(Project $project, User $member, Request $request)
    {
        $validated = $request->validate([
            'active' => ['required', 'int', 'min:0', 'max:1'],
        ]);

        $project->users()->updateExistingPivot($member, ['active' => (bool) $validated['active']]);

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

        $alreadyAdded = $project->users
            ->push($project->manager)
            ->pluck('id');


        $filteredUsers = collect($validated['users'])->diff($alreadyAdded);

        $project->users()->attach($filteredUsers);

        return redirect()->route('panel.projects.members.index', compact('project'));
    }

}
