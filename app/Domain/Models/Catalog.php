<?php

namespace App\Domain\Models;

use App\Domain\Enums\CatalogStatus;
use Illuminate\Database\Eloquent\Model;

class Catalog extends Model
{
    protected $fillable = ['name', 'status'];

    protected $visible = ['name'];

    public function isEditable()
    {
        return $this->status === CatalogStatus::EDITING()->getValue();
    }

    public function isSealed()
    {
        return $this->status === CatalogStatus::SEALED()->getValue();
    }

    public function isObsolete()
    {
        return $this->status === CatalogStatus::OBSOLETE()->getValue();
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
            'genus' => $this->genera,
            'domain' => $this->domain,
            'classis' => $this->classis,
        ];
    }

    /**
     * Empties the content of the catalog.
     */
    public function empty(): void
    {
        $this->domains()->detach();
        $this->classis()->detach();
        $this->genera()->detach();
        $this->species()->detach();
    }
}
