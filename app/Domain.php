<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Domain extends Model
{
    public function classis()
    {
        return $this->hasMany(Classis::class);
    }

    public function species()
    {
        return Species::whereHas('genus.class.domain', function ($q) {
            return $q->where('id', $this->getKey());
        });
    }
}
