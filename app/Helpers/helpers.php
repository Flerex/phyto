<?php

/**
 * Returns a full path inside the default home for
 * the local driving for a giving $path.
 *
 * @param string $path
 * @return string
 */
function local_path(string $path)
{
    return storage_path('app/' . $path);
}

