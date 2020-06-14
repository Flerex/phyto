<?php

namespace App\Domain\Traits;

use App\Domain\Models\TaskAssignment;
use Illuminate\Database\Eloquent\Builder;

trait MarksParentProcessAsCompleted
{

    /**
     * When we update the finished attribute, we check if it was the last assignment of the process to be marked as
     * completed. If it was, we mark the process as completed.
     */
    protected static function boot()
    {
        parent::boot();

        static::updated(function (self $model) {
            if ($model->finished === $model->getOriginal('finished') || $model->finished === false) {
                return;
            }

            $process = $model->process()->withCount([
                'assignments AS unfinished_assignments_count' => function (Builder $query) {
                    $query->unfinished();
                }
            ])->first();

            if (!$process->unfinished_assignments_count) {
                $process->finished = true;
                $process->save();
            }
        });
    }
}
