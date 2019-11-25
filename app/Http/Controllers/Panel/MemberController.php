<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Project;
use App\User;
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
}
