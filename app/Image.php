<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static create(array $array)
 */
class Image extends Model
{
    protected $fillable = ['path', 'sample_id', 'preview_path'];
}
