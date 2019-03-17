<?php

namespace App;

use App\Traits\HierarchyNavigators;
use Illuminate\Database\Eloquent\Model;

class Domain extends Model
{

    use HierarchyNavigators;

    protected $visible = ['id', 'name', 'children'];

    protected $fillable = ['name'];

    public function children()
    {
        return $this->hasMany(Classis::class);
    }


}
