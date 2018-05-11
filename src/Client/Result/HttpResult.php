<?php

namespace AbstractBabel\Client\Result;

use Iterator;
use IteratorAggregate;
use Psr\Http\Message\ResponseInterface;
use UnexpectedValueException;

final class HttpResult implements IteratorAggregate, Result
{
    private $result;
    private $response;

    private function __construct(ArrayResult $result, ResponseInterface $response)
    {
        $this->result = $result;
        $this->response = $response;
    }

    public static function fromResponse(ResponseInterface $response) : Result
    {
        $data = json_decode($response->getBody(), true);
        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new UnexpectedValueException('Could not decode JSON: '.json_last_error_msg());
        }

        return new self(new ArrayResult($data), $response);
    }

    public function toArray() : array
    {
        return $this->result->toArray();
    }

    public function getResponse() : ResponseInterface
    {
        return $this->response;
    }

    public function offsetExists($offset) : bool
    {
        return isset($this->result[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->result[$offset];
    }

    public function offsetSet($offset, $value)
    {
        $this->result[$offset] = $value;
    }

    public function offsetUnset($offset)
    {
        unset($this->result[$offset]);
    }

    public function getIterator() : Iterator
    {
        return $this->result->getIterator();
    }

    public function count() : int
    {
        return count($this->result);
    }
}
