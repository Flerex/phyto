<?php

namespace App\Utils;

final class Roles
{
    public const ADMIN = 'admin';
    public const SUPERVISOR = 'supervisor';
    public const MANAGER = 'manager';
    public const TAGGER = 'tagger';

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
    public static function isValid(string $str): bool
    {
        return in_array(strtolower($str), static::getConstants());
    }
}