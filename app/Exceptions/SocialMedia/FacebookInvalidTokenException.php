<?php

namespace App\Exceptions\SocialMedia;

use Exception;
use Throwable;

class FacebookInvalidTokenException extends Exception
{
    public function __construct($message = "Facebook token inválido!", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
