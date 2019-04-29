<?php

namespace App\Policies;

use App\User;
use App\Catalog;
use Illuminate\Auth\Access\HandlesAuthorization;

class CatalogPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can edit a catalog.
     *
     * @param User $user
     * @param Catalog $catalog
     * @return bool
     */
    public function edit(User $user, Catalog $catalog) {
        return $catalog->isEditable();
    }

    /**
     * Determine whether the user can create a new catalog from a given one.
     *
     * @param User $user
     * @param Catalog $catalog
     * @return bool
     */
    public function create_from(User $user, Catalog $catalog) {
        return true;
    }

    /**
     * Determine whether the user can seal a catalog.
     *
     * @param User $user
     * @param Catalog $catalog
     * @return bool
     */
    public function seal(User $user, Catalog $catalog) {
       return $catalog->isEditable();
    }

    /**
     * Determine whether the user can mark as obsolete a catalog.
     *
     * @param User $user
     * @param Catalog $catalog
     * @return bool
     */
    public function markAsObsolete(User $user, Catalog $catalog) {
        return $catalog->isSealed();
    }

    /**
     * Determine whether the user can restore a catalog.
     *
     * @param User $user
     * @param Catalog $catalog
     * @return bool
     */
    public function restore(User $user, Catalog $catalog) {
        return $catalog->isObsolete();
    }
}
