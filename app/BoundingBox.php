<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BoundingBox extends Model
{
    public const RULES = [
        'left' => ['required', 'integer', 'min:0'],
        'top' => ['required', 'integer', 'min:0'],
        'width' => ['required', 'integer', 'min:5'],
        'height' => ['required', 'integer', 'min:5'],
    ];

    protected $fillable = ['left', 'top', 'width', 'height', 'user_id', 'image_id'];

    protected $visible = ['id', 'left', 'top', 'width', 'height', 'user', 'taggable'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function image()
    {
        return $this->belongsTo(Image::class);
    }

    public function taggable()
    {
        return $this->morphTo();
    }
}
