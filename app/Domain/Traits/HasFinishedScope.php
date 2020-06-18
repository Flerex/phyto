<?php

namespace App\Domain\Traits;

use App\Domain\Models\TaskAssignment;
use Illuminate\Database\Eloquent\Builder;

trait HasFinishedScope
{
    /**
     * Scope a query to only include unfinished $model.
     *
     * @param  Builder  $query
     * @return Builder
     */
    public function scopeUnfinished($query)
    {
        return $query->where('finished', false);
    }

    /**
     * Scope a query to only include finished $model.
     *
     * @param  Builder  $query
     * @return Builder
     */
    public function scopeFinished($query)
    {
        return $query->where('finished', true);
    }
}
