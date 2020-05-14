<?php

namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
     * Defines the relationship to navigate to the image of
     * this assignment.
     *
     * @return BelongsTo
     */
    public function image() {
        return $this->belongsTo(Image::class);
    }

    /**
     * Defines the relationship to navigate to the user of
     * this assignment.
     *
     * @return BelongsTo
     */
    public function user() {
        return $this->belongsTo(User::class);
    }

    /**
     * Defines the relationship to navigate to the task process
     * of this assignment.
     *
     * @return BelongsTo
     */
    public function process() {
        return $this->belongsTo(TaskProcess::class, 'task_process_id');
    }

}
