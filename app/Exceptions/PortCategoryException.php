<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class PortCategoryException extends Exception
{
    /**
     * Create a new Port Category Exception instance.
     */
    public function __construct(
        string $message = 'Port Category operation failed.',
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
