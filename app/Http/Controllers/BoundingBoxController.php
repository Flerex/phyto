<?php

namespace App\Http\Controllers;

use App\BoundingBox;
use App\Image;
use App\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BoundingBoxController extends Controller
{
    public function store(Project $project, Image $image, Request $request)
    {
        $this->authorize('access', $project);

        $validated = $request->validate(BoundingBox::RULES);

        $validated['user_id'] = Auth::user()->getKey();
        $validated['image_id'] = $image->getKey();

        $bb = BoundingBox::create($validated);

        return $bb;
    }

    public function update(BoundingBox $boundingBox, Request $request)
    {
        $this->authorize('update', $boundingBox);

        $validated = $request->validate(BoundingBox::RULES);

        $boundingBox->fill($validated);
        $boundingBox->save();

        return $boundingBox;
    }
}
