<?php

namespace AbstractBabel\Client\HttpClient;

use GuzzleHttp\Promise\PromiseInterface;
use Psr\Http\Message\RequestInterface;
use function GuzzleHttp\Promise\all;

final class BatchingHttpClient implements HttpClient
{
    private $httpClient;
    private $batchSize;
    private $batch = [];

    public function __construct(HttpClient $httpClient, int $batchSize = 10)
    {
        $this->httpClient = $httpClient;
        $this->batchSize = $batchSize;
    }

    public function send(RequestInterface $request) : PromiseInterface
    {
        $this->ifBatchIsFull(function () {
            $this->filterResolvedRequests();
        });
        $this->ifBatchIsFull(function () {
            $this->waitOnBatch();
        });

        return $this->batch[] = $this->httpClient->send($request);
    }

    private function filterResolvedRequests()
    {
        $this->batch = array_filter($this->batch, function (PromiseInterface $promise) {
            return PromiseInterface::PENDING === $promise->getState();
        });
    }

    private function waitOnBatch()
    {
        all($this->batch)->wait();

        $this->batch = [];
    }

    private function ifBatchIsFull(callable $callable)
    {
        if ($this->batchSize === count($this->batch)) {
            $callable();
        }
    }
}
