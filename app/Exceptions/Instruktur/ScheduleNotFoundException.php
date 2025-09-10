<?php

// app/Exceptions/Instruktur/ScheduleNotFoundException.php

namespace App\Exceptions\Instruktur;

use App\Exceptions\BaseInstructorException;

class ScheduleNotFoundException extends BaseInstructorException
{
    protected $statusCode = 404;
    protected $errorCode = 'SCHEDULE_NOT_FOUND';

    public function __construct(string $message = 'Jadwal tidak ditemukan')
    {
        parent::__construct($message);
    }
}

