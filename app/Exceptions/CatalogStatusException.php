<?php

namespace App\Exceptions;

use App\Catalog;
use Exception;

class CatalogStatusException extends Exception
{
    public function __construct(Catalog $catalog, string $status)
    {
        $message = 'Catalog “' . $catalog->name . '” is not in the “' . $status . '” state.';

        parent::__construct($message, 0, null);
    }
}
