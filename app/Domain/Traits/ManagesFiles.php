<?php

namespace App\Domain\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * Trait ManagesFiles
 *
 * Adding this trait to an Eloquent model allows to simultaneously manage
 * a model and their images.
 *
 * This means that when an instance of the model gets deleted, its associated
 * files are deleted too.
 *
 * The model must define a $files attribute with the properties that are paths
 * to files.
 *
 * @package App\Domain\Traits
 */
trait ManagesFiles
{
    protected static function boot()
    {
        static::deleted(function (Model $model) {
            foreach ($model->files as $path) {
                Storage::delete($path);
            }
        });
    }
}
