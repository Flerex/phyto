<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sample extends Model
{
    protected $fillable = ['name', 'description'];

    public const VALIDATION_RULES = [
        'name' => ['required', 'string', 'min:3'],
        'description' => ['required', 'string', 'min:3'],
        'files' => ['required', 'array'],
        'files[]' => ['json'],
    ];

    public function images()
    {
        return $this->hasMany(Image::class);
    }
}
