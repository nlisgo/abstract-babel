<?php

namespace AbstractBabel\CrossRefClient;

use AbstractBabel\Client\HttpClient\HttpClient;
use AbstractBabel\CrossRefClient\ApiClient\WorksClient;
use AbstractBabel\CrossRefClient\Client\Works;
use AbstractBabel\CrossRefClient\Serializer\WorkDenormalizer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Serializer;

final class CrossRefSdk
{
    private $works;

    public function __construct(HttpClient $httpClient)
    {
        $serializer = new Serializer([
            new WorkDenormalizer(),
        ], [new JsonEncoder()]);
        $this->works = new Works(new WorksClient($httpClient, []), $serializer);
    }

    public function works() : Works
    {
        return $this->works;
    }
}
