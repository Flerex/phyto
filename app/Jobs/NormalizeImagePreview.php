<?php

namespace App\Jobs;

use App\Image;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image as ImageManager;

class NormalizeImagePreview implements ShouldQueue
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
        $path = $this->image->path;
        $newFileFullPath = local_path('public/' . $path);
        $mimeType = File::mimeType($newFileFullPath);

        $previewPath = $path;
        if(!in_array($mimeType, ['image/jpeg', 'image/png'])) {
            ImageManager::make($newFileFullPath)->encode('png')->save($newFileFullPath . '_preview.png');
            $previewPath = $path . '_preview.png';
        }

        $this->image->preview_path = $previewPath;
        $this->image->save();
    }
}
