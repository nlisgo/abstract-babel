<?php

namespace AbstractBabel\CrossRefClient\Exception;

use RuntimeException;
use Throwable;

/**
 * @SuppressWarnings(ForbiddenExceptionSuffix)
 */
class ApiException extends RuntimeException
{
    public function __construct(string $message, Throwable $previous = null)
    {
        parent::__construct($message, 0, $previous);
    }
}
