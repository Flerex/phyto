<?php

namespace App\Domain\Models;

use App\Domain\Traits\HasFinishedScope;
use App\Domain\Traits\MarksParentProcessAsCompleted;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TaskAssignment extends Model
{
    use HasFinishedScope, MarksParentProcessAsCompleted;

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
    protected $fillable = ['task_process_id', 'user_id', 'image_id', 'project_id', 'service'];


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
     * Defines the relationship to navigate to the bounding boxes created for the image in this assignment.
     *
     * @return HasMany
     */
    public function boxes()
    {
        return $this->hasMany(BoundingBox::class);
    }

}
