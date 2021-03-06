<?php

namespace App\Http\Controllers;

use App\Domain\Models\BoundingBox;
use App\Domain\Models\TaskAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class AutomatedServiceController extends Controller
{

    public function __construct()
    {
        $this->middleware('signed');
    }

    /**
     * Expected a POST request for the automated service with a `boxes` parameter as an array
     * of objects with, at least, `width`, `height`, `top` and `left` properties.
     *
     * @param TaskAssignment $assignment
     * @param Request $request
     * @return bool[]
     */
    public function receiveBoundingBoxes(TaskAssignment $assignment, Request $request): array
    {

        if($assignment->finished) {
            abort(Response::HTTP_FORBIDDEN, 'This assignment was already processed.');
        }

        $boxes = json_decode($request->getContent(), true);


        if ($boxes === null) {
            abort(Response::HTTP_BAD_REQUEST, 'JSON expected as POST request body');
        }

        $boxes = collect($boxes);

        // Minimum required properties for a bounding box
        $expectedProperties = collect(['top', 'left', 'width', 'height']);

        $wellFormed = $boxes->every(function (array $box) use ($expectedProperties) {
            $keys = collect(array_keys($box));
            return $expectedProperties->every(fn(string $prop) => $keys->contains($prop));
        });

        if (!$wellFormed) {
            abort(Response::HTTP_BAD_REQUEST, 'Boxes are not well formed.');
        }

        DB::transaction(function () use ($assignment, $boxes) {
            foreach ($boxes as $box) {
                $boundingBox = BoundingBox::make([
                    'left' => $box['left'],
                    'top' => $box['top'],
                    'width' => $box['width'],
                    'height' => $box['height'],
                ]);

                $boundingBox->task_assignment_id = $assignment->getKey();

                if (isset($box['taggable_id']) && isset($box['taggable_type'])) {
                    $boundingBox->taggable_id = $box['taggable_id'];
                    $boundingBox->taggable_type = $box['taggable_type'];
                }

                if (isset($box['rotation'])) {
                    $boundingBox->rotation = $box['rotation'];
                }

                $boundingBox->save();
            }

            $assignment->finished = true;
            $assignment->save();

        });

        return ['success' => true];
    }
}
