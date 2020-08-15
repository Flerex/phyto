<?php

namespace App\Jobs;

use App\Domain\Models\TaskAssignment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Zttp\Zttp;

class SendAutomatedIdentificationRequestJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var TaskAssignment
     */
    private TaskAssignment $assignment;

    /**
     * Create a new job instance.
     *
     * @param  TaskAssignment  $assignment
     */
    public function __construct(TaskAssignment $assignment)
    {
        $this->assignment = $assignment;
    }

/**
 * Execute the job.
 *
 * @return void
 */
public function handle()
{
    $service = (object) config('automated_identification.services.'.$this->assignment->service);

    Zttp::asMultipart()->post($service->endpoint, [
        [
            'name' => 'callback',
            'contents' => URL::signedRoute('automated_services.receive_bounding_boxes',
                ['assignment' => $this->assignment]),
        ],
        [
            'name' => 'image',
            'contents' => Storage::get('public/'.$this->assignment->image->original_path),
            'filename' => basename($this->assignment->image->original_path),
        ],
    ]);

}
}
