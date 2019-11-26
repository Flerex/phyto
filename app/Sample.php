<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @method static Sample create(array $array)
 */
class Sample extends Model
{
    protected $fillable = ['name', 'description', 'project_id', 'taken_on'];

    protected $dates = ['created_at', 'updated_at', 'taken_on'];

    public const VALIDATION_RULES = [
        'name' => ['required', 'string', 'min:3', 'unique:samples'],
        'description' => ['string', 'min:3'],
        'taken_on' => ['required', 'date'],
        'files' => ['required', 'array'],
        'files[]' => ['json'],
    ];


    /**
     * Relationship for the owned images of a sample.
     *
     * @return HasMany
     */
    public function images()
    {
        return $this->hasMany(Image::class);
    }

    /**
     * Relationship for the owning project of a sample
     *
     * @return BelongsTo
     */
    public function project() {
        return $this->belongsTo(Project::class);
    }
}
