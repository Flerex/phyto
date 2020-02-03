<?php


namespace App\Utils\Exceptions;

use Exception;
use Throwable;

class ExtractorNotImplementedException extends Exception
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
