<?php

namespace App\Utils;

use Spatie\Permission\Models\Role;

final class CatalogStatus
{
    public const EDITING = 'editing';
    public const SEALED = 'sealed';
    public const OBSOLETE = 'obsolete';

    // TODO: Create enum common class. Generics? Extends implies overriding isValid method each time.
    private static function getConstants()
    {
        $oClass = new \ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }

    /**
     * Checks whether the provided string is an actual value in the enumeration.
     *
     * This is used because, due to PHP limitations, there's no way to type hint an enumeration, so strings must be
     * passed. To check string is an actual enum value we must use this method.
     *
     * @param string $str
     * @return bool
     */
    public static function isValid(Role $role): bool
    {
        return in_array(strtolower($role->name), static::getConstants());
    }
}