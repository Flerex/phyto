<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Domain extends Model
{
    protected $visible = ['id', 'name', 'classis'];

    protected $fillable = ['name'];

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
