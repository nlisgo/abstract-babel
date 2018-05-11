<?php

namespace AbstractBabel\Client\Exception;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class BadResponse extends HttpProblem
{
    private $response;

    public function __construct(
        string $message,
        RequestInterface $request,
        ResponseInterface $response,
        Throwable $previous = null
    ) {
        parent::__construct($message, $request, $previous);

        $this->response = $response;
    }

    final public function getResponse() : ResponseInterface
    {
        return $this->response;
    }
}
