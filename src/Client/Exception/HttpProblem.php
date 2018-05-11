<?php

namespace AbstractBabel\Client\Exception;

use Psr\Http\Message\RequestInterface;
use Throwable;

abstract class HttpProblem extends ApiException
{
    private $request;

    public function __construct(string $message, RequestInterface $request, Throwable $previous = null)
    {
        parent::__construct($message, $previous);

        $this->request = $request;
    }

    final public function getRequest() : RequestInterface
    {
        return $this->request;
    }
}
