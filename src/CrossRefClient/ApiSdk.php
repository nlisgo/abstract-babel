<?php

namespace AbstractBabel\CrossRefClient;

use AbstractBabel\CrossRefClient\ApiClient\WorksClient;
use AbstractBabel\CrossRefClient\Client\Works;
use AbstractBabel\CrossRefClient\HttpClient\HttpClient;
use AbstractBabel\CrossRefClient\Serializer\WorkDenormalizer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Serializer;

final class ApiSdk
{
    private $works;

    public function __construct(HttpClient $httpClient)
    {
        $this->httpClient = $httpClient;
        $this->serializer = new Serializer([
            new WorkDenormalizer(),
        ], [new JsonEncoder()]);
        $this->works = new Works(new WorksClient($this->httpClient, []), $this->serializer);
    }

    public function works() : Works
    {
        return $this->works;
    }
}
