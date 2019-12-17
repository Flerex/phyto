<?php


namespace App\Enums;


use Illuminate\Support\Collection;
use Spatie\Permission\Models\Role;

abstract class Enum
{

    private static $constCacheArray = NULL;

    final private function __construct()
    {
    }

    private static function getConstants() {
        if (self::$constCacheArray == NULL) {
            self::$constCacheArray = [];
        }

        $calledClass = get_called_class();
        if (!array_key_exists($calledClass, self::$constCacheArray)) {
            $reflect = new \ReflectionClass($calledClass);
            self::$constCacheArray[$calledClass] = $reflect->getConstants();
        }
        return self::$constCacheArray[$calledClass];
    }


    /**
     * Checks whether the provided string is an actual value in the enumeration.
     *
     * This is used because, due to PHP limitations, there's no way to type hint an enumeration, so strings must be
     * passed. To check string is an actual enum value we must use this method.
     *
     * @param string $value
     * @return bool
     */
    public static function hasValue(string $value): bool
    {
        return in_array($value, static::getConstants());
    }

    /**
     * Return all valid values for this enumeration.
     *
     * @return Collection
     */
    public static function getValues() : Collection
    {
        return collect(self::getConstants())->values();
    }
}
