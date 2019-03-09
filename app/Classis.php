<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Classis extends Model
{
    protected $table = 'classis';

    protected $visible = ['id', 'name', 'genera'];

    public function domain()
    {
        return $this->belongsTo(Domain::class);
    }

    public function genera()
    {
        return $this->hasMany(Genus::class);
    }

    public function species()
    {
        return $this->hasManyThrough(Species::class, Genus::class);
    }
}
