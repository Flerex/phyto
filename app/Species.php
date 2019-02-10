<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Species extends Model
{
    public function genus()
    {
        return $this->belongsTo(Genus::class);
    }
}
