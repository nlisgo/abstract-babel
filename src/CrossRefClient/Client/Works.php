<?php

namespace AbstractBabel\CrossRefClient\Client;

use AbstractBabel\CrossRefClient\ApiClient\WorksClient;
use AbstractBabel\CrossRefClient\Model\Work as ModelWork;
use AbstractBabel\Client\Result\Result;
use GuzzleHttp\Promise\PromiseInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

final class Works
{
    private $serializer;
    private $worksClient;

    public function __construct(WorksClient $worksClient, DenormalizerInterface $serializer)
    {
        $this->worksClient = $worksClient;
        $this->serializer = $serializer;
    }

    public function get(string $doi) : PromiseInterface
    {
        return $this->worksClient
            ->getWork(
                [],
                $doi
            )
            ->then(function (Result $result) {
                return $this->serializer->denormalize($result->toArray(), ModelWork::class);
            });
    }
}
