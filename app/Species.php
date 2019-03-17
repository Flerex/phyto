<?php

namespace App;

use App\Traits\HierarchyNavigators;
use Illuminate\Database\Eloquent\Model;

class Species extends Model
{

    use HierarchyNavigators;

    protected $visible = ['name'];

    protected $fillable = ['name', 'genus_id'];

    public function parent()
    {
        return $this->belongsTo(Genus::class);
    }
}
