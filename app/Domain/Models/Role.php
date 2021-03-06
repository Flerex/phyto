<?php

namespace App\Domain\Models;


use Spatie\Permission\Models\Role as RoleModel;

class Role extends RoleModel
{

    /**
     * Returns whether the role will be the default
     * when creating a user.
     *
     * @return bool
     */
    public function getIsDefaultAttribute()
    {
        return $this->name == config('phyto.default_role');
    }

}
