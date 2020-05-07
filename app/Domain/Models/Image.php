<?php

namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static create(array $array)
 */
class Image extends Model
{
    protected $fillable = ['path', 'sample_id', 'thumbnail_path', 'original_path'];

    public function boundingBoxes()
    {
        return $this->hasMany(BoundingBox::class);
    }

    public function sample() {
        return $this->belongsTo(Sample::class);
    }
}
