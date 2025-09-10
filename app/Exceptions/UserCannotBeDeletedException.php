<?php

// app/Exceptions/UserCannotBeDeletedException.php
namespace App\Exceptions;

use Exception;

class UserCannotBeDeletedException extends Exception
{
    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
