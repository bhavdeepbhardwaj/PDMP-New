<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class OrganizationException extends Exception
{
    /**
     * Create a new Organization Exception instance.
     */
    public function __construct(
        string $message = 'Organization operation failed.',
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
