<?php

namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TaskProcess extends Model
{

    /**
     * List of properties that can be mass assigned.
     *
     * @var string[]
     */
    protected $fillable = ['task_id'];


    /**
     * Scope a query to only include unfinished processes.
     *
     * @param  Builder  $query
     * @return Builder
     */
    public function scopeUnfinished($query)
    {
        return $query->where('finished', false);
    }

    /**
     * Scope a query to only include finished processes.
     *
     * @param  Builder  $query
     * @return Builder
     */
    public function scopeFinished($query)
    {
        return $query->where('finished', true);
    }

    /**
     * Define the relationship to navigate to the task
     * owning this process.
     */
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }


    /**
     * Define the relationship to navigate to the assignments
     * for the current process.
     *
     * @return HasMany
     */
    public function assignments(): HasMany
    {
        return $this->hasMany(TaskAssignment::class);
    }

    /**
     * Define the relationship to navigate to the assignments
     * for the current process.
     *
     * @return HasMany
     */
    public function unfinishedAssignments(): HasMany
    {
        return $this->hasMany(TaskAssignment::class)->unfinished();
    }

    /**
     * Returns the percentage (two-digit precision) of completeness
     * of the assignments related to this process.
     */
    public function getCompletenessPercentageAttribute()
    {


        $finished = $this->assignments->filter(fn (TaskAssignment $assigment) => $assigment->finished)->count();

        $total = $this->assignments->count();

        return round($finished / $total, 2) * 100;
    }
}
