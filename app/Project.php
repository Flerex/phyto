<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{

    protected $fillable = ['name', 'description', 'user_id'];


    /**
     * Defines the relationship that allows to navigate from the Project
     * model to the users that have been assigned to it.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users() {
        return $this->belongsToMany(User::class);
    }

    public function catalogs() {
        return $this->belongsToMany(Catalog::class);
    }

    public function manager() {
        return $this->belongsTo(User::class, 'user_id');
    }
}
