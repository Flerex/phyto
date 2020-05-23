<?php

namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{

    /**
     * Properties that can be autocompleted.
     *
     * @var string[] $fillable
     */
    protected $fillable = ['project_id', 'sample_id'];


    /**
     * A task will always load its processes and their
     * processes' assignments.
     * @var string[]
     */
    protected $with = ['processes.assignments'];

    /**
     * Defines the relationship that allows to navigate to the
     * owning Project for a Tasks.
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }


    /**
     * Defines the relationship that allows to access all of
     * a Task's processes.
     */
    public function processes()
    {
        return $this->hasMany(TaskProcess::class);
    }

    /**
     * Defines the relationship that allows to access the
     * sample of this task.
     */
    public function sample()
    {
        return $this->belongsTo(Sample::class);
    }

    /**
     * Returns the percentage (two-digit precision) of completeness
     * of the assignments related to this task.
     */
    public function getCompletenessPercentageAttribute()
    {


        $finished = TaskAssignment::whereHas('process', function(Builder $query) {
            $query->where('task_id', $this->getKey());
        })->finished()->count();

        $total = TaskAssignment::whereHas('process', function(Builder $query) {
            $query->where('task_id', $this->getKey());
        })->count();

        return round($finished / $total, 2) * 100;
    }
}
