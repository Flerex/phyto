<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Species extends Model
{

    protected $fillable = ['name', 'genus_id'];

    public function genus()
    {
        return $this->belongsTo(Genus::class);
    }
}
