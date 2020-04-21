<?php

/**
 * Returns a full path inside the default home for
 * the local driving for a giving $path.
 *
 * @param string $path
 * @return string
 */
if (!function_exists('local_path')) {
    function local_path(string $path)
    {
        return storage_path('app/' . $path);
    }
}

/**
 * Returns the morph class for a given Model.
 *
 * @param $class
 * @return mixed
 */
if (!function_exists('morph_class')) {
    function morph_class($class)
    {
        return (new $class)->getMorphClass();
    }
}
