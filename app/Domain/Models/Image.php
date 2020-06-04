<?php

namespace App\Domain\Models;

use App\Domain\Traits\ManagesFiles;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Image extends Model
{

    /**
     * All the properties that link to a file in storage. This will be used
     * to remove the images when the model is deleted.
     *
     * @var string[]
     */
    protected $files = ['path', 'thumbnail_path', 'original_path'];

    /**
     * Defines which properties can be mass assigned.
     *
     * @var string[]
     */
    protected $fillable = ['path', 'sample_id', 'thumbnail_path', 'original_path'];

    public function sample()
    {
        return $this->belongsTo(Sample::class);
    }

}
