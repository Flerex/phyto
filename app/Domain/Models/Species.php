<?php

namespace App\Domain\Models;

use App\Traits\HierarchyNavigators;
use Illuminate\Database\Eloquent\Model;

class Species extends Model
{

    use HierarchyNavigators;

    protected $visible = ['id', 'name'];

    protected $fillable = ['name', 'genus_id'];

    public function parent()
    {
        return $this->belongsTo(Genus::class);
    }

    public function catalogs() {
        return $this
            ->belongsToMany(Catalog::class, 'catalogs_nodes', 'catalog_id', 'node_id')
            ->withTimestamps();
    }
}
