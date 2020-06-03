<?php

namespace App\Http\Controllers\Project;

use App\Domain\Models\BoundingBox;
use App\Domain\Models\Image;
use App\Domain\Models\Project;
use App\Domain\Models\TaskAssignment;
use App\Http\Controllers\Controller;
use App\Http\Requests\TagBoundingBoxRequest;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

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
        $this->authorize('work', $boundingBox->assignment);

        $validated = $request->validate(BoundingBox::RULES);

        $boundingBox->fill($validated);
        $boundingBox->save();

        return $boundingBox;
    }

    public function destroy(BoundingBox $boundingBox)
    {
        $this->authorize('work', $boundingBox->assignment);

        $boundingBox->delete();

        return ['success' => true];
    }

    public function tag(BoundingBox $boundingBox, Request $request)
    {
        $this->authorize('work', $boundingBox->assignment);

        $validated = $request->validate([
            'type' => ['required', Rule::in(['domain', 'genus', 'classis', 'species'])],
            'id' => ['required', 'int'],
        ]);

        $class = Relation::getMorphedModel($validated['type']);

        $model = $class::findOrFail($validated['id']); // Check it exists

        $boundingBox->taggable_id = $validated['id'];
        $boundingBox->taggable_type = $validated['type'];
        $boundingBox->save();

        return ['success' => true];
    }
}
