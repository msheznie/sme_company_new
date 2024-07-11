<?php

namespace App\Exceptions;

use Exception;

class ContractCreationException extends Exception
{
    protected $details;

    public function __construct($message, $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
