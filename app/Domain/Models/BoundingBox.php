<?php

namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class BoundingBox extends Model
{
    public const RULES = [
        'left' => ['required', 'integer', 'min:0'],
        'top' => ['required', 'integer', 'min:0'],
        'width' => ['required', 'integer', 'min:5'],
        'height' => ['required', 'integer', 'min:5'],
        'rotation' => ['number', 'between:0,360'],
    ];

    protected $fillable = ['left', 'top', 'width', 'height', 'rotation', 'user_id', 'task_assignment_id'];

    protected $visible = ['id', 'left', 'top', 'width', 'height', 'rotation', 'author', 'taggable'];

    protected $appends = ['author'];

    protected $with = ['user', 'taggable'];

    public function getAuthorAttribute()
    {
        return $this->user ? $this->user->name : config('automated_identification.services.'.$this->assignment->service.'.name');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function image()
    {
        return $this->belongsTo(Image::class);
    }

    public function assignment()
    {
        return $this->belongsTo(TaskAssignment::class, 'task_assignment_id');
    }

    /**
     * Defines the relationship to navigate to the tagged taxonomy node (element from the hierarchy).
     *
     * @return MorphTo
     */
    public function taggable(): MorphTo
    {
        return $this->morphTo();
    }
}
