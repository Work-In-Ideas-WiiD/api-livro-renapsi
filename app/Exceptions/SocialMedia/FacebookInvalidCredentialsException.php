<?php

namespace App\Exceptions\SocialMedia;

use Exception;
use Throwable;

class FacebookInvalidCredentialsException extends Exception
{
    public function __construct($message = "Credenciais do facebook inválidas!", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
