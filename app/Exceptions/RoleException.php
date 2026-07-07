<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class RoleException extends Exception
{
    /**
     * Create a new Role Exception instance.
     */
    public function __construct(
        string $message = 'Role operation failed.',
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
