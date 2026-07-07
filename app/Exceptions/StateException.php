<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class StateException extends Exception
{
    /**
     * Create a new State Exception instance.
     */
    public function __construct(
        string $message = 'State operation failed.',
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
