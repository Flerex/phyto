<?php

/**
 * Returns a full path inside the default home for
 * the local driving for a giving $path.
 *
 * @param  string  $path
 * @return string
 */
function local_path(string $path): string
{
    return storage_path('app/'.$path);
}

/**
 * Retrieves the namespace from a given class by
 * its fully qualified name.
 *
 * @param  string  $fqn
 * @return string
 */
function class_namespace(string $fqn): string
{
    $components = explode('\\', $fqn);
    array_pop($components);
    return implode('\\', $components);

}
