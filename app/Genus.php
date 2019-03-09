<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Genus extends Model
{
    protected $visible = ['id', 'name', 'species'];

    public function class()
    {
        return $this->belongsTo(Classis::class, 'classis_id');
    }

    public function species()
    {
        return $this->hasMany(Species::class);
    }
}
