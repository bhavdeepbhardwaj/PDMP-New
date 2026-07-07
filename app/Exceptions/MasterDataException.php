<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class MasterDataException extends Exception
{
    /**
     * Create a new exception instance.
     */
    public function __construct(
        string $message = 'Master data error.',
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
