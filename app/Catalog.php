<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Catalog extends Model
{
    protected $fillable = ['name', 'status'];

    public function species() {
        return $this->belongsToMany(Species::class, 'catalogs_nodes', 'catalog_id', 'node_id');
    }

    public function genera() {
        return $this->belongsToMany(Genus::class, 'catalogs_nodes', 'catalog_id', 'node_id');
    }

    public function domain() {
        return $this->belongsToMany(Domain::class, 'catalogs_nodes', 'catalog_id', 'node_id');
    }

    public function classis() {
        return $this->belongsToMany(Classis::class, 'catalogs_nodes', 'catalog_id', 'node_id');
    }

    public function nodes() {
        return [
            'species' => $this->species,
            'genera' => $this->genera,
            'domains' => $this->domain,
            'classis' => $this->classis,
        ];
    }
}
