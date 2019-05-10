<?php

namespace App\Exceptions;

use App\Catalog;
use App\Utils\CatalogStatus;
use Exception;
use Throwable;

class CatalogStatusException extends Exception
{
    public function __construct(Catalog $catalog, string $status)
    {
        $message = 'Catalog “' . $catalog->name . '” is not in the “' . $status . '” state.';

        parent::__construct($message, 0, null);
    }
}
