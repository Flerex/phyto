<?php

use Illuminate\Support\Collection;

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

/**
 * Creates an empty collection of size $count. A $fill value can be provided to initialize the collection with that
 * value.
 *
 * @param  int  $count
 * @param  mixed|null  $fill
 * @return Collection
 */
function empty_collection(int $count, $fill = null): Collection
{
    return collect(array_fill(0, $count, $fill));

}
