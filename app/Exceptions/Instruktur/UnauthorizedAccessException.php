<?php
// app/Exceptions/Instruktur/UnauthorizedAccessException.php

namespace App\Exceptions\Instruktur;

use App\Exceptions\BaseInstructorException;

class UnauthorizedAccessException extends BaseInstructorException
{
    protected $statusCode = 403;
    protected $errorCode = 'UNAUTHORIZED_ACCESS';

    public function __construct(string $message = 'Akses tidak diizinkan')
    {
        parent::__construct($message);
    }
}
