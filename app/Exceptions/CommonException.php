<?php

namespace App\Exceptions;

use Exception;
class CommonException extends Exception
{
    protected $message = 'Approval Error';
    public function __construct($message = null, $code = 0)
    {
        parent::__construct($message, $code);
    }
}
