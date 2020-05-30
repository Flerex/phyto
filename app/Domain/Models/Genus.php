<?php

namespace App\Domain\Models;

use App\Traits\HierarchyNavigators;
use Illuminate\Database\Eloquent\Model;

class Genus extends Model
{

    use HierarchyNavigators;

    protected $visible = ['id', 'name', 'children'];

    protected $fillable = ['name', 'classis_id'];

    public function parent()
    {
        return $this->belongsTo(Classis::class, 'classis_id');
    }

    public function children()
    {
        return $this->hasMany(Species::class);
    }
}
