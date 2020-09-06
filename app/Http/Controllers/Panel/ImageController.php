<?php

namespace App\Http\Controllers\Panel;

use App\Domain\Services\SampleService;
use App\Http\Controllers\Controller;
use App\Domain\Models\Image;
use App\Domain\Models\Project;
use App\Domain\Models\Sample;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ImageController extends Controller
{

    protected SampleService $sampleService;

    public function __construct(SampleService $sampleService)
    {
        $this->sampleService = $sampleService;
    }

    /**
     * Image list of a given sample.
     *
     * @param  Project  $project
     * @param  Sample  $sample
     * @return Factory|View
     * @throws AuthorizationException
     */
    public function index(Project $project, Sample $sample)
    {

        $this->authorize('viewAny', [Image::class, $project, $sample]);

        $images = $this->sampleService->get_processed_images($sample);

        $totalImages =  $this->sampleService->get_total_images($sample);

        return view('panel.projects.images.index', compact('project', 'sample', 'images', 'totalImages'));
    }
}
