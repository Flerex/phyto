<?php

namespace App\Http\Controllers\Panel;

use App\Enums\Permissions;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateSampleRequest;
use App\Http\Requests\SampleRequest;
use App\Domain\Models\Project;
use App\Domain\Models\Sample;
use App\Domain\Services\ProjectService;
use App\Domain\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;
use Pion\Laravel\ChunkUpload\Exceptions\UploadMissingFileException;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;

class SampleController extends Controller
{

    /** @var ProjectService $projectService */
    protected $projectService;

    public function __construct(ProjectService $projectService)
    {
        $this->projectService = $projectService;
    }

    /**
     * Sample list for a project.
     *
     * @param Project $project
     * @return Factory|View
     * @throws AuthorizationException
     */
    public function index(Project $project)
    {

        $this->authorize('viewAny', [Sample::class, $project]);

        $samples = Sample::withCount('images')
            ->where('project_id', $project->getKey())
            ->latest()
            ->paginate(config('phyto.pagination_size'));

        return view('panel.projects.samples.index', compact('project', 'samples'));
    }

    /**
     * Add new sample page.
     *
     * @param Project $project
     * @return Factory|View
     * @throws AuthorizationException
     */
    public function create(Project $project)
    {
        $this->authorize('create', [Sample::class, $project]);

        return view('panel.projects.samples.create', compact('project'));
    }

    /**
     * Add new sample page's form request.
     *
     * @param Project $project
     * @param SampleRequest $request
     * @return string
     * @throws AuthorizationException
     */
    public function store(Project $project, SampleRequest $request)
    {
        $this->authorize('create', [Sample::class, $project]);

        $validated = $request->validated();

        $files = collect($validated['files'])->map(function($file) {
            return $file->path . $file->name;
        });

        $takenOn = Carbon::parse($validated['taken_on']);

        $sample = $this->projectService->addSampleToProject($validated['name'], $validated['description'], $takenOn, $files, $project);

        return redirect()->route('panel.projects.images.index', compact('project', 'sample'));
    }

    /**
     * Handles the check request that Resumable.js does to ensure that a chunk hasn't
     * already been uploaded (for example, when the connection was interrupted or the
     * browser has been closed)
     *
     * @param Request $request
     * @return ResponseFactory|Response
     */
    public function checkChunk(Request $request)
    {
        $path = storage_path('app/'
            . config('chunk-upload.storage.chunks')
            . '/*' . $request->input('resumableIdentifier')
            . '.' . $request->input('resumableChunkNumber')
            . '.part');

        if (!File::glob($path)) {
            // Let resumable.js know that the chunk exists
            return response('ko', 204); // The chunk will be uploaded
        }

        return response('ok', 200); // The chunk will not be re-uploaded
    }

    /**
     * Handle file upload
     *
     * @param FileReceiver $receiver
     * @return ResponseFactory|JsonResponse|Response
     * @throws UploadMissingFileException
     */
    public function upload(FileReceiver $receiver)
    {

        if ($receiver->isUploaded() === false) {
            throw new UploadMissingFileException();
        }

        $save = $receiver->receive();

        if ($save->isFinished()) {
            return $this->saveFile($save->getFile());
        }

        $handler = $save->handler();

        return response()->json([
            "done" => $handler->getPercentageDone()
        ]);
    }

    /**
     * Saves the file
     *
     * @param UploadedFile $file
     *
     * @return ResponseFactory|JsonResponse|Response
     */
    protected function saveFile(UploadedFile $file)
    {

        $validTypes = collect(config('phyto.valid_sample_mimes'))->flatten();

        if (!$validTypes->contains($file->getMimeType())) {
            return response(trans('validation.custom.unreachable'), 412);
        }

        $fileName = $this->createFilename($file);
        // Group files by mime type
        $mime = str_replace('/', '-', $file->getMimeType());
        // Group files by the date (week
        $dateFolder = date("Y-m-W");

        // Build the file path
        $filePath = "upload/{$mime}/{$dateFolder}/";
        $finalPath = storage_path("app/" . $filePath);

        // move the file name
        $file->move($finalPath, $fileName);

        return response()->json([
            'path' => $filePath,
            'name' => $fileName,
            'mime_type' => $mime
        ]);
    }

    /**
     * Create unique filename for uploaded file
     * @param UploadedFile $file
     * @return string
     */
    protected function createFilename(UploadedFile $file)
    {
        $extension = $file->getClientOriginalExtension();
        $filename = str_replace("." . $extension, "", $file->getClientOriginalName()); // Filename without extension

        // Add timestamp hash to name of the file
        $filename .= "_" . md5((string) time()) . "." . $extension;

        return $filename;
    }
}
