<?php

namespace App\Exceptions\SocialMedia;

use Exception;
use Throwable;

class GoogleInvalidTokenException extends Exception
{
    public function __construct($message = "Google token inválido!", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
