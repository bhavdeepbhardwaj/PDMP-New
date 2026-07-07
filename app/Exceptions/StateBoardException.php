<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class StateBoardException extends Exception
{
    /**
     * Create a new State Board Exception instance.
     */
    public function __construct(
        string $message = 'State Board operation failed.',
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
