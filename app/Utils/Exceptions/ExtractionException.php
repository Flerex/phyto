<?php


namespace App\Utils\Exceptions;

use Exception;
use Throwable;

class ExtractionException extends Exception
{
    public function __construct($message = null, $code = 0, Throwable $previous = null)
    {
        parent::__construct($message ?? 'Exporting that file type is not supported.', $code, $previous);
    }
}
