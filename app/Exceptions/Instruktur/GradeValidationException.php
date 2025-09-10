<?php
// app/Exceptions/Instruktur/GradeValidationException.php

namespace App\Exceptions\Instruktur;

use App\Exceptions\BaseInstructorException;

class GradeValidationException extends BaseInstructorException
{
    protected $statusCode = 422;
    protected $errorCode = 'GRADE_VALIDATION_ERROR';

    public function __construct(string $message = 'Data nilai tidak valid')
    {
        parent::__construct($message);
    }
}