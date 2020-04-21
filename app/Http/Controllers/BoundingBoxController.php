<?php

namespace App\Http\Controllers;

use App\BoundingBox;
use App\Classis;
use App\Domain;
use App\Genus;
use App\Image;
use App\Project;
use App\Species;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

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

    public function tag(BoundingBox $boundingBox, Request $request)
    {

        $validated = $request->validate([
            'type' => ['required', Rule::in([morph_class(Domain::class), morph_class(Genus::class),
                morph_class(Classis::class), morph_class(Species::class),])],
            'id' => ['required'],
        ]);

        $model = Model::getActualClassNameForMorph($validated['type']);
        $model = $model::findOrFail((int) $validated['id']);

        $boundingBox->taggable()->associate($model);
        $boundingBox->save();

        return ['success' => true];
    }
}
