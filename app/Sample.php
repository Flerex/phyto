<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sample extends Model
{
    public const VALIDATION_RULES = [
        'name' => ['required', 'string', 'min:4'],
    ];
}
