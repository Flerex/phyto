<?php

namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TaskAssignment extends Model
{
    /**
     * Eager load image and user field.
     * @var string[]
     */
    protected $with = ['user', 'image'];

    /**
     * Fields that can be mass assigned.
     *
     * @var string[]
     */
    protected $fillable = ['task_process_id', 'user_id', 'image_id'];


    /**
     * When we update the finished attribute, we check if it was the last assignment of the process to be marked as
     * completed. If it was, we mark the process as completed.
     */
    protected static function boot()
    {
        parent::boot();

        static::updated(function (TaskAssignment $assignment) {
            if ($assignment->finished === $assignment->getOriginal('finished') || $assignment->finished === false) {
                return;
            }

            $process = $assignment->process()->withCount([
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


    /**
     * Scope a query to only include unfinished assignments.
     *
     * @param  Builder  $query
     * @return Builder
     */
    public function scopeUnfinished($query)
    {
        return $query->where('finished', false);
    }

    /**
     * Scope a query to only include finished assignments.
     *
     * @param  Builder  $query
     * @return Builder
     */
    public function scopeFinished($query)
    {
        return $query->where('finished', true);
    }


    /**
     * Defines the relationship to navigate to the image of this assignment.
     *
     * @return BelongsTo
     */
    public function image()
    {
        return $this->belongsTo(Image::class);
    }

    /**
     * Defines the relationship to navigate to the user of this assignment.
     *
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Defines the relationship to navigate to the project of this assignment.
     *
     * @return BelongsTo
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Defines the relationship to navigate to the task process of this assignment.
     *
     * @return BelongsTo
     */
    public function process()
    {
        return $this->belongsTo(TaskProcess::class, 'task_process_id');
    }

    /**
     * Defines the relationship to naviagate to the bounding boxes created for the image in this assignment.
     *
     * @return HasMany
     */
    public function boxes()
    {
        return $this->hasMany(BoundingBox::class);
    }

}
