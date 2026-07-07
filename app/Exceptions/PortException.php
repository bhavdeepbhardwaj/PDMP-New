<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class PortException extends Exception
{
    /**
     * Create a new Port Exception instance.
     */
    public function __construct(
        string $message = 'Port operation failed.',
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
