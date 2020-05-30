<?php

namespace App\Domain\Models;

use App\Traits\HierarchyNavigators;
use Illuminate\Database\Eloquent\Model;

class Classis extends Model
{
    use HierarchyNavigators;

    protected $table = 'classis';

    protected $visible = ['id', 'name', 'children'];

    protected $fillable = ['name', 'domain_id'];

    public function parent()
    {
        return $this->belongsTo(Domain::class);
    }

    public function children()
    {
        return $this->hasMany(Genus::class);
    }

}
