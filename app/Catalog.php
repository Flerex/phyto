<?php

namespace App;

use App\Enums\CatalogStatus;
use Illuminate\Database\Eloquent\Model;

class Catalog extends Model
{
    protected $fillable = ['name', 'status'];

    public function isEditable()
    {
        return $this->status === CatalogStatus::EDITING;
    }

    public function isSealed()
    {
        return $this->status === CatalogStatus::SEALED;
    }

    public function isObsolete()
    {
        return $this->status === CatalogStatus::OBSOLETE;
    }

    public function species()
    {
        return $this->belongsToMany(Species::class, 'catalogs_nodes', 'catalog_id', 'node_id')
            ->withPivot('node_type')
            ->withTimestamps()
            ->wherePivot('node_type', 'species');
    }

    public function genera()
    {
        return $this->belongsToMany(Genus::class, 'catalogs_nodes', 'catalog_id', 'node_id')
            ->withPivot('node_type')
            ->withTimestamps()
            ->wherePivot('node_type', 'genus');
    }

    public function domains()
    {
        return $this->belongsToMany(Domain::class, 'catalogs_nodes', 'catalog_id', 'node_id')
            ->withPivot('node_type')
            ->withTimestamps()
            ->wherePivot('node_type', 'domain');
    }

    public function classis()
    {
        return $this->belongsToMany(Classis::class, 'catalogs_nodes', 'catalog_id', 'node_id')
            ->withPivot('node_type')
            ->withTimestamps()
            ->wherePivot('node_type', 'classis');
    }


    /*
     * Singular method names are used when dynamically using the type name to add new nodes in catalog creation & update
     */
    public function genus()
    {
        return $this->genera();
    }

    public function domain()
    {
        return $this->domains();
    }

    public function nodes()
    {
        return [
            'species' => $this->species,
            'genera' => $this->genera,
            'domains' => $this->domain,
            'classis' => $this->classis,
        ];
    }

    public function empty()
    {
        $this->domains()->detach();
        $this->classis()->detach();
        $this->genera()->detach();
        $this->species()->detach();
    }
}
