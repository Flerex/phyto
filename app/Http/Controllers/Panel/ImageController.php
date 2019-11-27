<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Image;
use App\Project;
use App\Sample;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ImageController extends Controller
{

    /**
     * Image list of a given sample.
     *
     * @param Project $project
     * @param Sample $sample
     * @return Factory|View
     * @throws AuthorizationException
     */
    public function index(Project $project, Sample $sample)
    {

        $this->authorize('viewAny', [Image::class, $project, $sample]);

        $images = $sample->images()->whereNotNull('preview_path')->get();

        $totalImages = $sample->images()->count();

        return view('panel.projects.images.index', compact('project', 'sample', 'images', 'totalImages'));
    }
}
