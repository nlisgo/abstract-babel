<?php

namespace AbstractBabel\CrossRefClient\ApiClient;

use AbstractBabel\Client\ApiClient;
use AbstractBabel\Client\HttpClient\HttpClient;
use AbstractBabel\Client\HttpClient\UserAgentPrependingHttpClient;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\Psr7\Uri;

final class WorksClient
{
    use ApiClient;

    public function __construct(HttpClient $httpClient, array $headers = [])
    {
        $this->httpClient = new UserAgentPrependingHttpClient($httpClient, 'CrossRefClient');
        $this->headers = $headers;
    }

    public function getWork(
        array $headers,
        string $doi = null
    ) : PromiseInterface {
        return $this->getRequest(
            Uri::fromParts([
                'path' => 'works/'.$doi,
            ]),
            $headers
        );
    }
}
