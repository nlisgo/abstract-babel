<?php

namespace AbstractBabel\CrossRefClient\HttpClient;

use GuzzleHttp\Promise\PromiseInterface;
use Psr\Http\Message\RequestInterface;
use Throwable;

final class NotifyingHttpClient implements HttpClient
{
    private $httpClient;
    private $listeners = [];

    public function __construct(HttpClient $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function addRequestListener(callable $listener)
    {
        $this->listeners[] = $listener;
    }

    public function send(RequestInterface $request) : PromiseInterface
    {
        $this->notifyListeners($request);

        return $this->httpClient->send($request);
    }

    private function notifyListeners($request)
    {
        foreach ($this->listeners as $listener) {
            try {
                $listener($request);
            } catch (Throwable $e) {
                error_log($e->getMessage().' at '.$e->getFile().':'.$e->getLine());
            }
        }
    }
}
