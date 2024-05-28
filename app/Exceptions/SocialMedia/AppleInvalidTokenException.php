<?php

namespace App\Exceptions\SocialMedia;

use Exception;
use Throwable;

class AppleInvalidTokenException extends Exception
{
    public function __construct($message = "Apple token inválido!", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
