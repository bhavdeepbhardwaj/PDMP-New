<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class DepartmentException extends Exception
{
    /**
     * Create a new Department Exception instance.
     */
    public function __construct(
        string $message = 'Department operation failed.',
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
