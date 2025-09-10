<?php
// app/Exceptions/BaseInstructorException.php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

abstract class BaseInstructorException extends Exception
{
    protected $statusCode = 500;
    protected $errorCode = 'INSTRUCTOR_ERROR';

    public function render(): JsonResponse
    {
        return response()->json([
            'success' => false,
            'error' => [
                'code' => $this->errorCode,
                'message' => $this->getMessage(),
            ]
        ], $this->statusCode);
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getErrorCode(): string
    {
        return $this->errorCode;
    }
}

