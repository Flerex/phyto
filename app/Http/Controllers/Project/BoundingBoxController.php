<?php

namespace App\Http\Controllers\Project;

use App\Domain\Models\BoundingBox;
use App\Domain\Models\Image;
use App\Domain\Models\Project;
use App\Domain\Models\TaskAssignment;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BoundingBoxController extends Controller
{
    public function store(TaskAssignment $assignment, Request $request)
    {
        $this->authorize('work', $assignment);

        $validated = $request->validate(BoundingBox::RULES);

        $validated['user_id'] = Auth::user()->getKey();
        $validated['task_assignment_id'] = $assignment->getKey();

        return BoundingBox::create($validated);
    }

    public function update(BoundingBox $boundingBox, Request $request)
    {
        $this->authorize('update', $boundingBox);

        $validated = $request->validate(BoundingBox::RULES);

        $boundingBox->fill($validated);
        $boundingBox->save();

        return $boundingBox;
    }

    public function destroy(BoundingBox $boundingBox)
    {
        $this->authorize('destroy', $boundingBox);

        $boundingBox->delete();

        return ['success' => true];
    }
}
