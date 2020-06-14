<?php

namespace App\Jobs;

use App\Domain\Models\Image;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image as ImageManager;

class NormalizeImagePreviewJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $image;

    /**
     * Create a new job instance.
     *
     * @param Image $image
     */
    public function __construct(Image $image)
    {
        $this->image = $image;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $originalPath = $this->image->original_path;
        $originalFullPath = local_path('public/' . $originalPath);

        $basename = basename($originalPath);

        $basePath = str_replace($basename, '', $originalPath);
        $baseFullPath = str_replace($basename, '', $originalFullPath);

        preg_match('/(.*?)(?:\..*)?$/', $basename, $matches);
        $basePath .= $matches[1];
        $baseFullPath .= $matches[1];


        // Thumbnail
        $thumbnailPath = $basePath . '_thumbnail.png';
        $thumbnailFullPath = $baseFullPath . '_thumbnail.png';
        ImageManager::make($originalFullPath)->resize(500, null, function ($constraint) {
            $constraint->aspectRatio();
        })->encode('png')->save($thumbnailFullPath);
        $this->image->thumbnail_path = $thumbnailPath;

        // Compressed
        $compressedPath = $basePath . '_compressed.png';
        $compressedFullPath = $baseFullPath . '_compressed.png';
        ImageManager::make($originalFullPath)->encode('png')->save($compressedFullPath);
        $this->image->path = $compressedPath;

        $this->image->save();
    }
}
