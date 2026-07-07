<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class EmployeeException extends Exception
{
    /**
     * Create a new Employee Exception instance.
     */
    public function __construct(
        string $message = 'Employee operation failed.',
        int $code = 0,
        ?Throwable $previous = null
    ) {
        parent::__construct(

            $message,

            $code,

            $previous

        );
    }
}
